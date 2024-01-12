<?php
    namespace App\Controllers;

    use App\Core\ApiController;
    use App\Models\ChartModel;
    use App\Models\UserModel;

    class ApiChartController extends ApiController {
        public function chartByTime() {
            $chartModel = new ChartModel($this->getDatabaseConnection());

            $table = filter_input(INPUT_POST, 'table', FILTER_UNSAFE_RAW); //TODO: takodje unsafe raw
            $dimension = filter_input(INPUT_POST, 'dimension', FILTER_UNSAFE_RAW);

            $tableName = $this->normaliseKeywords($table);
            $dimensionName = $this->normaliseKeywords($dimension);

            switch ($dimensionName) {
                case 'year':
                    $chartData = $chartModel->countAllByYears($tableName);
                    $label = '# of entries for ' . $tableName . ' by years';
                    break;
                case 'quarter':
                    $chartData = $chartModel->countAllByQuarter($tableName);
                    $label = '# of entries for ' . $tableName . ' by quarters';
                    break;
                case 'month':
                    $chartData = $chartModel->countAllByMonth($tableName);
                    $label = '# of entries for ' . $tableName . ' by month';
                    break;
                case 'week':
                    $chartData = $chartModel->countAllByWeek($tableName);
                    $label = '# of entries for ' . $tableName . ' by week';
                    break;
                default:
                    $chartData = null;
                    $label = 'error';
            }

//            $chartData = $chartModel->countAllByYears($tableName);
//            $chartData = $chartModel->countAllByDimension($tableName, $dimension);

            $this->set('type', $dimensionName);
            $this->set('label', $label);
            $this->set('data', $chartData);
        }



        private function normaliseKeywords(string $keywords): string {
            $keywords = trim($keywords);
            $keywords = preg_replace('/ +/', ' ', $keywords);
            return $keywords;
        }
    }

    //SELECT YEAR(created_at) AS creation_year, COUNT(*) AS record_count
    //FROM your_table
    //GROUP BY creation_year
    //ORDER BY creation_year;