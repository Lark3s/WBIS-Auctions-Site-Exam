<?php
    namespace App\Controllers;

    use App\Core\Controller;
    use App\Models\AuctionModel;
    use App\Models\CategoryModel;

    class CategoryController extends Controller {
        public function show($id) {
            $categoryModel = new CategoryModel($this->getDatabaseConnection());
            $category = $categoryModel->getById($id);

            if (!$category) {
                header('Location: /');
                exit;
            }

            $this->set('category', $category);

            $auctionModel = new AuctionModel($this->getDatabaseConnection());
            $auctionsInCategory = $auctionModel->getAllByCategoryId($id);
            $this->set('auctionsInCategory', $auctionsInCategory);
        }
    }