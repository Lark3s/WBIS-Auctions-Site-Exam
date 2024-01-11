<?php
    namespace App\Core\Role;

    use App\Core\Controller;

    class UserRoleController extends Controller {
        public function __pre() {
            $this->checkLogin();
            if ($this->getSession()->get('user_id') === null) {
                $this->redirect('/user/login');
            }
        }

        public function checkIfAdmin() {
            if ($this->getSession()->get('role') != 'admin') {
                return false;
            }
            return true;
        }
    }