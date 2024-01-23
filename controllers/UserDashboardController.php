<?php
    namespace App\Controllers;

    use App\Core\Controller;
    use App\Core\Role\UserRoleController;
    use App\Models\AuctionModel;
    use App\Models\AuctionViewModel;
    use App\Models\OfferModel;
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

            $auctionViews = $auctionViewModel->getByPageAndTable($page, $itemsPerPage);
            $totalPages = $auctionViewModel->getTotalPagesByTable($itemsPerPage);

            if ($page > $totalPages) {
                header('Location: /notFound');
                exit;
            }

            $this->set('auctionViews', $auctionViews);
            $this->set('totalPages', $totalPages);
            $this->set('currentPage', $page);
        }

        public function offer($page) {
            $this->authorize();

            $offerModel = new OfferModel($this->getDatabaseConnection());

            $itemsPerPage = 15;

            $offers = $offerModel->getByPageAndTable($page, $itemsPerPage);
            $totalPages = $offerModel->getTotalPagesByTable($itemsPerPage);

            if ($page > $totalPages) {
                header('Location: /notFound');
                exit;
            }

            $this->set('offers', $offers);
            $this->set('totalPages', $totalPages);
            $this->set('currentPage', $page);
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