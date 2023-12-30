<?php
    namespace App\Controllers;

    use App\Core\Role\UserRoleController;
    use App\Models\AuctionModel;
    use App\Models\CategoryModel;
    use App\Models\OfferModel;

    class UserAuctionManagementController extends UserRoleController {

        public function auctions() {
            $userId = $this->getSession()->get('user_id');

            $auctionModel = new AuctionModel($this->getDatabaseConnection());
            $auctions = $auctionModel->getAllByUserId($userId);

            $this->set('auctions', $auctions);
        }

        public function getAdd() {
            $categoryModel = new CategoryModel($this->getDatabaseConnection());
            $categories = $categoryModel->getAll();
            $this->set('categories', $categories);
        }

        public function postAdd() {
            $addData = [
                'title'          => filter_input(INPUT_POST, 'title', FILTER_UNSAFE_RAW), // TODO: I ovde je gomila unsafe raw filtera
                'description'    => filter_input(INPUT_POST, 'description', FILTER_UNSAFE_RAW),
                'starting_price' => sprintf("%.2f", filter_input(INPUT_POST, 'starting_price', FILTER_UNSAFE_RAW)),
//                'starts_at'      => filter_input(INPUT_POST, 'starts_at', FILTER_UNSAFE_RAW),
                'expires_at'        => filter_input(INPUT_POST, 'expires_at', FILTER_UNSAFE_RAW),
                'category_id'    => filter_input(INPUT_POST, 'category_id', FILTER_SANITIZE_NUMBER_INT),
                'user_id'        => $this->getSession()->get('user_id')
            ];

            $auctionModel = new AuctionModel($this->getDatabaseConnection());

            $auctionId = $auctionModel->add($addData);

            if (!$auctionId) {
                $this->set('message', 'Nije uspesno dodata aukcija.');
                return;
            }

            $uploadStatus = $this->uploadImage('image', $auctionId . '');
            if (!$uploadStatus) {
                return;
            }

            $this->redirect(\Configuration::BASE . 'user/auctions');
        }

        public function getEdit($auctionId) {
            $auctionModel = new AuctionModel($this->getDatabaseConnection());
            $auction = $auctionModel->getById($auctionId);

            if (!$auction) {
                $this->redirect( \Configuration::BASE . 'user/auctions' );
                return;
            }

            if ($auction->user_id != $this->getSession()->get('user_id')) {
                $this->redirect(\Configuration::BASE . 'user/auctions');
                return;
            }

            $offerModel = new OfferModel($this->getDatabaseConnection());
            $offer = $offerModel->getAllByAuctionId($auctionId);
            if (count($offer) > 0) {
                $this->redirect( \Configuration::BASE . 'user/auctions' ); // TODO: Trenutno radi samo redirect ako aukcija nema ponuda, ali treba napraviti neki mehanizam da izbaci neku povratnu informaciju o tome
                return;
            }

//            $auction->starts_at = str_replace(' ', 'T', substr($auction->starts_at, 0, 16));
            $auction->expires_at = str_replace(' ', 'T', substr($auction->expires_at, 0, 16));

            $this->set('auction', $auction);

            $categoryModel = new CategoryModel($this->getDatabaseConnection());
            $categories = $categoryModel->getAll();
            $this->set('categories', $categories);
        }

        public function postEdit($auctionId) {
            $this->getEdit($auctionId);

            $editData = [
                'title'          => filter_input(INPUT_POST, 'title', FILTER_UNSAFE_RAW), // TODO: A i ovde je gomila unsafe raw filtera
                'description'    => filter_input(INPUT_POST, 'description', FILTER_UNSAFE_RAW),
                'starting_price' => sprintf("%.2f", filter_input(INPUT_POST, 'starting_price', FILTER_UNSAFE_RAW)),
//                'starts_at'      => filter_input(INPUT_POST, 'starts_at', FILTER_UNSAFE_RAW),
                'expires_at'        => filter_input(INPUT_POST, 'expires_at', FILTER_UNSAFE_RAW),
                'category_id'    => filter_input(INPUT_POST, 'category_id', FILTER_SANITIZE_NUMBER_INT)
            ];

            $auctionModel = new AuctionModel($this->getDatabaseConnection());

            $res = $auctionModel->editById($auctionId, $editData);

            if (!$res) {
                $this->set('message', 'Nije bilo moguce izmeniti aukciju.');
                return;
            }

            $uploadStatus = $this->uploadImage('image', $auctionId . '');
            if (!$uploadStatus) {
                return;
            }

            $this->redirect( \Configuration::BASE . 'user/auctions' );
        }

        private function uploadImage(string $fieldName, string $fileName): bool {
            unlink(\Configuration::UPLOAD_DIR . $fileName . '.jpg');

            $uploadPath = new \Upload\Storage\FileSystem(\Configuration::UPLOAD_DIR);
            $file = new \Upload\File($fieldName, $uploadPath);
            $file->setName($fileName);
            $file->addValidations([
                new \Upload\Validation\Mimetype("image/jpeg"),
                new \Upload\Validation\Size("3M"),
                new \Upload\Validation\Dimensions(320, 240)
            ]);

            try {
                $file->upload();
                return true;
            } catch (\Exception $e) {
                $this->set('message', 'Greska: ' . implode(', ', $file->getErrors()));
                return false;
            }
        }
    }