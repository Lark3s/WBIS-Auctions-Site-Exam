<?php
    namespace App\Controllers;

    use App\Core\ApiController;
    use App\Models\ChartModel;
    use App\Models\UserModel;

    class ApiChartController extends ApiController {
        public function chartByYears() {
            $chartModel = new ChartModel($this->getDatabaseConnection());

            $q = filter_input(INPUT_POST, 'user', FILTER_UNSAFE_RAW); //TODO: takodje unsafe raw

            $tableName = $this->normaliseKeywords($q);

            $chartData = $chartModel->countAllByYears($tableName);

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