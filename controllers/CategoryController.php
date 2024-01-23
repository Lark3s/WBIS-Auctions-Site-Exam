<?php
    namespace App\Controllers;

    use App\Core\Controller;
    use App\Models\AuctionModel;
    use App\Models\CategoryModel;
    use App\Models\OfferModel;

    class CategoryController extends Controller {
        public function show($id) {
            $categoryModel = new CategoryModel($this->getDatabaseConnection());
            $category = $categoryModel->getById($id);

            preg_match_all('|\d+|', $_GET["URL"], $digitsFromURL);
            $digitsFromURL = $digitsFromURL[0];

            $pageNumber = $digitsFromURL[1];

            $itemsPerPage = 10;

            if (!$category) {
                header('Location: /');
                exit;
            }
//            $categoryName = $category->name;
            $this->set('category', $category);

            $auctionModel = new AuctionModel($this->getDatabaseConnection());
//            $auctionsInCategory = $auctionModel->getAllByCategoryId($id); //ovo je staro, gde samo uzme po id

            $auctionsInCategory = $auctionModel->getByPageAndTableAndId('category', $id, $pageNumber, $itemsPerPage);
            $totalPages = $auctionModel->getTotalPagesByTableAndId('category', $id, $itemsPerPage);

            if ($pageNumber > $totalPages) {
                header('Location: /notFound');
                exit;
            }

            $offerModel = new OfferModel($this->getDatabaseConnection());

            array_map(function($auction) use ($offerModel){
                $auction->last_offer_price = $offerModel->getLastOfferPrice($auction);
                return $auction;
            }, $auctionsInCategory);

            $this->set('auctionsInCategory', $auctionsInCategory);
            $this->set('totalPages', $totalPages);
            $this->set('currentPage', $pageNumber);

//            var_dump($totalPages);
//            exit;
//            var_dump($categoryName);
//            var_dump($auctionModel->getTotalPagesByTableAndId('category', $id, $itemsPerPage));
        }
    }