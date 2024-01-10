<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\AuctionModel;
use App\Models\AuctionViewModel;
use App\Models\OfferModel;

class AuctionController extends Controller {
    public function show($id) {
        $auctionModel = new AuctionModel($this->getDatabaseConnection());
        $auction = $auctionModel->getById($id);

        if (!$auction) {
            header('Location: /');
            exit;
        }

        $this->set('auction', $auction);

        $offerModel = new OfferModel($this->getDatabaseConnection());
        $lastOfferPrice = $offerModel->getLastOfferPrice($auction);
        $this->set('lastOfferPrice', $lastOfferPrice);

        $auctionViewModel = new AuctionViewModel($this->getDatabaseConnection());

        $ipAddress = filter_input(INPUT_SERVER, 'REMOTE_ADDR');
        $userAgent = filter_input(INPUT_SERVER, 'HTTP_USER_AGENT');

        $auctionViewModel->add([
            'auction_id' => $id,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent
        ]);

        $this->set('showBidForm', 'true');

        if ($this->getSession()->get('user_id') === null) {
            $this->set('showBidForm', 'false');
        }

        $auctionEndsAtTimestamp = strtotime($auction->expires_at);
        $currentTimestamp = time();

        if ($currentTimestamp > $auctionEndsAtTimestamp) {
            $this->set('showBidForm', 'false');
        }
    }

    private function normaliseKeywords(string $keywords): string {
        $keywords = trim($keywords);
        $keywords = preg_replace('/ +/', ' ', $keywords);
        return $keywords;
    }

    public function postSearch() {
        $auctionModel = new AuctionModel($this->getDatabaseConnection());

        $q = filter_input(INPUT_POST, 'q', FILTER_UNSAFE_RAW); //TODO: takodje unsafe raw

        $keywords = $this->normaliseKeywords($q);

        $auctions = $auctionModel->getAllBySearch($keywords);

        $this->set('auctions', $auctions);
    }
}