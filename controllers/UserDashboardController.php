<?php
    namespace App\Controllers;

    use App\Core\Controller;
    use App\Core\Role\UserRoleController;

    class UserDashboardController extends UserRoleController {
        public function index() {
            $this->authorize();
        }

        public function analytics() {
            $this->authorize();
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