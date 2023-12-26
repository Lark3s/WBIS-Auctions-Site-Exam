<?php
    namespace App\Controllers;

    use App\Core\ApiController;
    use App\Core\Controller;
    use App\Models\AuctionModel;

    class ApiAuctionController extends ApiController {
        public function show($id) {
            $auctionModel = new AuctionModel($this->getDatabaseConnection());
            $auction = $auctionModel->getById($id);
            $this->set('auction', $auction);
        }
    }