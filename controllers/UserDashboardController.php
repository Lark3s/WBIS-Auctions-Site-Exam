<?php
    namespace App\Controllers;

    use App\Core\Controller;
    use App\Core\Role\UserRoleController;
    use App\Models\AuctionModel;
    use App\Models\AuctionViewModel;
    use App\Models\CategoryModel;
    use App\Models\OfferModel;
    use App\Models\ReportModel;
    use App\Models\UserModel;

    class UserDashboardController extends UserRoleController {
        public function index() {
            $this->authorize();
        }

        public function graphs() {
            $this->authorize();
        }

        public function tables() {
            $this->authorize();
        }

        public function user($page) {
            $this->authorize();
            $userModel = new UserModel($this->getDatabaseConnection());

            $itemsPerPage = 15;

            $users = $userModel->getByPageAndTable($page, $itemsPerPage);
            $totalPages = $userModel->getTotalPagesByTable($itemsPerPage);

            if ($page > $totalPages) {
                header('Location: /notFound');
                exit;
            }

            $fieldNames = $userModel->getTableFields();

            $this->set('users', $users);
            $this->set('totalPages', $totalPages);
            $this->set('currentPage', $page);


        }

        public function auction($page) {
            $this->authorize();

            $auctionModel = new AuctionModel($this->getDatabaseConnection());

            $itemsPerPage = 15;

            $auctions = $auctionModel->getByPageAndTable($page, $itemsPerPage);
            $totalPages = $auctionModel->getTotalPagesByTable($itemsPerPage);

            if ($page > $totalPages) {
                header('Location: /notFound');
                exit;
            }

//            var_dump($auctions);
//            exit;

            $this->set('auctions', $auctions);
            $this->set('totalPages', $totalPages);
            $this->set('currentPage', $page);
        }

        public function auctionView($page) {
            $this->authorize();

            $auctionViewModel = new AuctionViewModel($this->getDatabaseConnection());

            $itemsPerPage = 15;

            $orderingAndSorting = $this->getOrderingAndSorting($_GET["URL"]);
            $orderBy = $orderingAndSorting[1];
            $sortBy = $orderingAndSorting[0];

            $auctionViews = $auctionViewModel->getByPageAndTableAndSortAndOrder($page, $itemsPerPage, $orderBy, $sortBy);
//            $auctionViews = $auctionViewModel->getByPageAndTable($page, $itemsPerPage);
            $totalPages = $auctionViewModel->getTotalPagesByTable($itemsPerPage);

            if ($page > $totalPages) {
                header('Location: /notFound');
                exit;
            }

            $this->set('auctionViews', $auctionViews);
            $this->set('totalPages', $totalPages);
            $this->set('currentPage', $page);
            $this->set('sort', $sortBy);
            $this->set('order', $orderBy);

        }

        public function offer($page) {
            $this->authorize();

            $offerModel = new OfferModel($this->getDatabaseConnection());

            $itemsPerPage = 15;

            $orderingAndSorting = $this->getOrderingAndSorting($_GET["URL"]);
            $orderBy = $orderingAndSorting[1];
            $sortBy = $orderingAndSorting[0];

            $offers = $offerModel->getByPageAndTableAndSortAndOrder($page, $itemsPerPage, $orderBy, $sortBy);
            $totalPages = $offerModel->getTotalPagesByTable($itemsPerPage);

            if ($page > $totalPages) {
                header('Location: /notFound');
                exit;
            }

            $this->set('offers', $offers);
            $this->set('totalPages', $totalPages);
            $this->set('currentPage', $page);
            $this->set('sort', $sortBy);
            $this->set('order', $orderBy);
        }

        public function category($page) {
            $this->authorize();

            $categoryModel = new CategoryModel($this->getDatabaseConnection());

            $itemsPerPage = 15;

            $orderingAndSorting = $this->getOrderingAndSorting($_GET["URL"]);
            $orderBy = $orderingAndSorting[1];
            $sortBy = $orderingAndSorting[0];

            $categories = $categoryModel->getByPageAndTableAndSortAndOrder($page, $itemsPerPage, $orderBy, $sortBy);
            $totalPages = $categoryModel->getTotalPagesByTable($itemsPerPage);

            if ($page > $totalPages) {
                header('Location: /notFound');
                exit;
            }

            $this->set('categories', $categories);
            $this->set('totalPages', $totalPages);
            $this->set('currentPage', $page);
            $this->set('sort', $sortBy);
            $this->set('order', $orderBy);
        }

        public function report() {
            $this->authorize();



        }
        public function reportYear($year) {
            $this->authorize();

            $reportModel = new ReportModel($this->getDatabaseConnection());

            $noOfNewUsers = $reportModel->newUsers($year, null, 'year');
            $noOfNewAuctions = $reportModel->newAuctions($year, null, 'year');
            $revenue = $reportModel->revenue($year, null, 'year');
            $categoryWithMostNewAuctions = $reportModel->mostPopularCategory($year, null, 'year');
            $auctionWithMostViews = $reportModel->mostPopularAuction($year, null, 'year');

            $reportData = array(
                $year,
                $noOfNewUsers,
                $noOfNewAuctions,
                $revenue,
                $categoryWithMostNewAuctions,
                $auctionWithMostViews
            );

            $json_data = json_encode($reportData, JSON_PRETTY_PRINT);

            $file_path = \Configuration::REPORT_PATH;

            file_put_contents($file_path, $json_data);
            $this->set('reportData', $reportData);
        }
        public function reportQuarter($year) {
            $this->authorize();

            preg_match_all('|\d+|', $_GET["URL"], $digitsFromURL);
            $digitsFromURL = $digitsFromURL[0];

            $quarter = $digitsFromURL[1];

            $reportModel = new ReportModel($this->getDatabaseConnection());

            $noOfNewUsers = $reportModel->newUsers($year, $quarter, 'quarter');
            $noOfNewAuctions = $reportModel->newAuctions($year, $quarter, 'quarter');
            $revenue = $reportModel->revenue($year, $quarter, 'quarter');
            $categoryWithMostNewAuctions = $reportModel->mostPopularCategory($year, $quarter, 'quarter');
            $auctionWithMostViews = $reportModel->mostPopularAuction($year, $quarter, 'quarter');

            $reportData = array(
                $year,
                $quarter,
                $noOfNewUsers,
                $noOfNewAuctions,
                $revenue,
                $categoryWithMostNewAuctions,
                $auctionWithMostViews
            );

            $json_data = json_encode($reportData, JSON_PRETTY_PRINT);

            $file_path = \Configuration::REPORT_PATH;

            file_put_contents($file_path, $json_data);
            return $reportData;

        }
        public function reportMonth($year) {
            $this->authorize();

            preg_match_all('|\d+|', $_GET["URL"], $digitsFromURL);
            $digitsFromURL = $digitsFromURL[0];

            $month = $digitsFromURL[1];

            $reportModel = new ReportModel($this->getDatabaseConnection());

            $noOfNewUsers = $reportModel->newUsers($year, $month, 'month');
            $noOfNewAuctions = $reportModel->newAuctions($year, $month, 'month');
            $revenue = $reportModel->revenue($year, $month, 'month');
            $categoryWithMostNewAuctions = $reportModel->mostPopularCategory($year, $month, 'month');
            $auctionWithMostViews = $reportModel->mostPopularAuction($year, $month, 'month');

            $reportData = array(
                $year,
                $month,
                $noOfNewUsers,
                $noOfNewAuctions,
                $revenue,
                $categoryWithMostNewAuctions,
                $auctionWithMostViews
            );

            $json_data = json_encode($reportData, JSON_PRETTY_PRINT);

            $file_path = \Configuration::REPORT_PATH;

            file_put_contents($file_path, $json_data);
            return $reportData;

        }
        public function reportWeek($year) {
            $this->authorize();

            preg_match_all('|\d+|', $_GET["URL"], $digitsFromURL);
            $digitsFromURL = $digitsFromURL[0];

            $week = $digitsFromURL[1];

            $reportModel = new ReportModel($this->getDatabaseConnection());

            $noOfNewUsers = $reportModel->newUsers($year, $week, 'week');
            $noOfNewAuctions = $reportModel->newAuctions($year, $week, 'week');
            $revenue = $reportModel->revenue($year, $week, 'week');
            $categoryWithMostNewAuctions = $reportModel->mostPopularCategory($year, $week, 'week');
            $auctionWithMostViews = $reportModel->mostPopularAuction($year, $week, 'week');

            $reportData = array(
                $year,
                $week,
                $noOfNewUsers,
                $noOfNewAuctions,
                $revenue,
                $categoryWithMostNewAuctions,
                $auctionWithMostViews
            );

            $json_data = json_encode($reportData, JSON_PRETTY_PRINT);

            $file_path = \Configuration::REPORT_PATH;

            file_put_contents($file_path, $json_data);
            return $reportData;
        }


        public function getOrderingAndSorting($url) {
            $urlComponents = parse_url($url);
            $result = null;
            if (isset($urlComponents['path'])) {
                $pathParts = explode('/', trim($urlComponents['path'], '/'));
                $result[0] = array_slice($pathParts, -1)[0];
                $result[1] = array_slice($pathParts, -2)[0];
            }
            return $result;
        }

        public function authorize() {
            if ($this->checkIfAdmin() === false) {
                $this->set('isAdmin', false);
                $this->set('message', 'Nemate pristup ovoj stranici');
                return;
            }
            $this->set('isAdmin', true);
        }
    }