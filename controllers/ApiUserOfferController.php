<?php
    namespace App\Controllers;

    use App\Core\UserApiController;
    use App\EventHandlers\EmailEventHandler;
    use App\Models\AuctionModel;
    use App\Models\EventModel;
    use App\Models\OfferModel;
    use App\Models\UserModel;
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    class ApiUserOfferController extends UserApiController {
        public function postMakeOffer() {  //TODO: Ovo bi moglo da bude vise metoda, za proveru, slanje i izvestavanje
            $userId = $this->getSession()->get('user_id');

            $auctionId = filter_input(INPUT_POST, 'auction_id', FILTER_SANITIZE_NUMBER_INT);
            $offerPrice = floatval(filter_input(INPUT_POST, 'price', FILTER_UNSAFE_RAW));

            $auctionModel = new AuctionModel($this->getDatabaseConnection());
            $auction = $auctionModel->getById($auctionId);

            if (!$auction) {
                $this->set('error', -20001);
                $this->set('message', 'This auction does not exist.');
                return;
            }

//            if (!$auction->is_active) {
//                $this->set('error', -20002);
//                $this->set('message', 'This auction is not active.');  //TODO: ovo obavezno da se resi, nemam is_active u bazi
//                return;
//            }

            $auctionEndsAtTimestamp = strtotime($auction->expires_at);
            $currentTimestamp = time();

            if ($currentTimestamp > $auctionEndsAtTimestamp) {
                $this->set('error', -20003);
                $this->set('message', 'This auction has ended.');
                return;
            }

            $auctionStartsAtTimestamp = strtotime($auction->created_at);
            if ($currentTimestamp < $auctionStartsAtTimestamp) {
                $this->set('error', -20004);
                $this->set('message', 'This auction has not yet started.');
                return;
            }

            $offerModel = new OfferModel($this->getDatabaseConnection());
            $currentAuctionPrice = $offerModel->getLastOfferPrice($auction);

            if ($currentAuctionPrice + 2.0 > $offerPrice) {
                $this->set('error', -20005);
                $this->set('message', 'This offer price is too low.');
                return;
            }

            if ($userId == $auction->user_id) {
                $this->set('error', -20006);
                $this->set('message', 'You cannot make an offer for your auction.');
                return;
            }

            $offerModel = new OfferModel($this->getDatabaseConnection());

            $offerPriceString = sprintf('%.2f', $offerPrice);

            $offerId = $offerModel->add([
                'auction_id' => $auction->auction_id,
                'user_id'    => $userId,
                'price'      => $offerPriceString
            ]);

            if (!$offerId) {
                $this->set('error', -10002);
                $this->set('message', 'There was an error trying to add this offer.');
                return;
            }

            $this->set('error', 0);
            $this->set('message', 'Success.');
            $this->set('offer_id', $offerId);

            $this->notifyUser($auction, $offerId);
        }

        private function notifyUser(&$auction, int $offerId) {
            $offerModel = new OfferModel($this->getDatabaseConnection());
            $offer = $offerModel->getById($offerId);

            $userModel = new UserModel($this->getDatabaseConnection());
            $user = $userModel->getById($auction->user_id);

            $html = '<!doctype html><html><meta charset="utf-8"><head></head><body>';
            $html .= 'Neko je licitirao na Vasu aukciju &quot;';
            $html .= htmlspecialchars($auction->title);
            $html .= '&quot; sa iznosom ' . sprintf("%.2f", $offer->price);
            $html .= '</body></html>';

            //TODO: trebao bi da se napravi helper za slanje mejlova, ako to ostane kao funkcionalnost u kranjoj verziji, factory/builder ili singleton pattern
            // ^- ovo je donekle reseno

            $event = new EmailEventHandler();
            $event->setSubject('Nova licitacija');
            $event->setBody($html);
            $event->addAddress($user->email);

            $eventModel = new EventModel($this->getDatabaseConnection());
            $eventModel->add([
                'type' => 'email',
                'data' => $event->getData()
            ]);
        }
    }