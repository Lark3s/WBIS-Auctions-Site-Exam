<?php
    namespace App\Controllers;

    use App\Core\ApiController;
    use App\Core\Controller;
    use App\Models\AuctionModel;
    use App\Models\OfferModel;

    class ApiAuctionController extends ApiController {
        public function show($id) {
            $auctionModel = new AuctionModel($this->getDatabaseConnection());
            $auction = $auctionModel->getById($id);

//            $lastOfferPrice = $this->getLastOfferPrice($id);
//            if (!$lastOfferPrice) {
//                $lastOfferPrice = $auction->starting_price;
//            }
            $offerModel = new OfferModel($this->getDatabaseConnection());
            $lastOfferPrice = $offerModel->getLastOfferPrice($auction);
            $auction->last_offer_price = $lastOfferPrice;

            $this->set('auction', $auction);
        }

//        private function getLastOfferPrice($auctionId) {
//            $offerModel = new OfferModel($this->getDatabaseConnection());
//            $offers = $offerModel->getAllByAuctionId($auctionId);
//            $lastPrice = 0;
//
//            foreach ($offers as $offer) {
//                if ($lastPrice < $offer->price) {
//                    $lastPrice = $offer->price;
//                }
//            }
//
//            return $lastPrice;
//        }
    }