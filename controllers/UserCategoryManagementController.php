<?php
    namespace App\Controllers;

    use App\Core\Role\UserRoleController;
    use App\Models\CategoryModel;

    class UserCategoryManagementController extends UserRoleController {
        public function categories() {
            $categoryModel = new CategoryModel($this->getDatabaseConnection());
            $categories = $categoryModel->getAll();
            $this->set('categories', $categories);
        }

        public function getEdit($categoryId) {
            $categoryModel = new CategoryModel($this->getDatabaseConnection());
            $category = $categoryModel->getById($categoryId);

            if (!$category) {
                $this->redirect(\Configuration::BASE . 'user/categories');
            }

            $this->set('category', $category);

            return $categoryModel;
        }

        public function postEdit($categoryId) {
            $categoryModel = $this->getEdit($categoryId);

            $name = filter_input(INPUT_POST, 'name', FILTER_UNSAFE_RAW); //TODO: unsafe raw again.......

            $categoryModel->editById($categoryId, [
                'name' => $name
            ]);

            $this->redirect(\Configuration::BASE . 'user/categories');
        }

        public function getAdd() {

        }

        public function postAdd() {
            $name = filter_input(INPUT_POST, 'name', FILTER_UNSAFE_RAW); //TODO: unsafe raw here too.......
            $categoryModel = new CategoryModel($this->getDatabaseConnection());

            $categoryId = $categoryModel->add([
                'name' => $name
            ]);

            if ($categoryId) {
                $this->redirect(\Configuration::BASE . 'user/categories');
            }

            $this->set('message', 'Doslo je do gerske: Nije moguce dodati novu kategoriju.');
        }
    }