<?php
    namespace App\Controllers;

    use App\Core\ApiController;
    use App\Models\CategoryModel;
    use App\Models\ChartModel;
    use App\Models\UserModel;

    class ApiChartController extends ApiController {
        public function chartByTime() {
            $chartModel = new ChartModel($this->getDatabaseConnection());

            $table = filter_input(INPUT_POST, 'table', FILTER_UNSAFE_RAW);
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

            $this->set('type', $dimensionName);
            $this->set('label', $label);
            $this->set('data', $chartData);
        }

        //lazily calculated as 15% of all sales
        public function chartRevenueByCategoryAndTime() {
            $chartModel = new ChartModel($this->getDatabaseConnection());

            $category = filter_input(INPUT_POST, 'category', FILTER_UNSAFE_RAW);
            $dimension = filter_input(INPUT_POST, 'dimension', FILTER_UNSAFE_RAW);

            $categoryName = $this->normaliseKeywords($category);
            $dimensionName = $this->normaliseKeywords($dimension);

            if ($categoryName == 'All') {
                switch ($dimensionName) {
                    case 'year':
                        $chartData = $chartModel->revenueByYear();
                        $label = 'Revenue by years';
                        break;
                    case 'quarter':
                        $chartData = $chartModel->revenueByQuarter();
                        $label = 'Revenue by quarters';
                        break;
                    case 'month':
                        $chartData = $chartModel->revenueByMonth();
                        $label = 'Revenue by month';
                        break;
                    case 'week':
                        $chartData = $chartModel->revenueByWeek();
                        $label = 'Revenue by week';
                        break;
                    default:
                        $chartData = null;
                        $label = 'error';
                }

                $this->set('type', $dimensionName);
                $this->set('label', $label);
                $this->set('data', $chartData);
                return;
            }

            $categoryModel = new CategoryModel($this->getDatabaseConnection());
            $categoryId = $categoryModel->getCategoryIdFromName($categoryName);
            $categoryId = $categoryId->category_id;

            switch ($dimensionName) {
                case 'year':
                    $chartData = $chartModel->revenueByYearAndCategory($categoryId);
                    $label = 'Revenue for ' . $categoryName . ' by years';
                    break;
                case 'quarter':
                    $chartData = $chartModel->revenueByQuarterAndCategory($categoryId);
                    $label = 'Revenue for ' . $categoryName . ' by quarters';
                    break;
                case 'month':
                    $chartData = $chartModel->revenueByMonthAndCategory($categoryId);
                    $label = 'Revenue for ' . $categoryName . ' by month';
                    break;
                case 'week':
                    $chartData = $chartModel->revenueByWeekAndCategory($categoryId);
                    $label = 'Revenue ' . $categoryName . ' by week';
                    break;
                default:
                    $chartData = null;
                    $label = 'error';
            }

            $this->set('type', $dimensionName);
            $this->set('label', $label);
            $this->set('data', $chartData);
        }

//        public function chartRevenueByCategory($id) {
//            $sql = 'SELECT SUM(`starting_price`)*0.15 AS `sum_price` FROM `aukcije.auction` WHERE category_id = '.$id.' AND is_sold = 1';
//        }



        private function normaliseKeywords(string $keywords): string {
            $keywords = trim($keywords);
            $keywords = preg_replace('/ +/', ' ', $keywords);
            return $keywords;
        }
    }
