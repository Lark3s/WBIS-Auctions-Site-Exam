<?php
    namespace App\Controllers;

    use App\Core\Controller;
    use App\Core\DatabaseConnection;
    use App\Models\AuctionModel;
    use App\Models\AuctionViewModel;
    use App\Models\CategoryModel;
    use App\models\RoleModel;
    use App\Models\UserModel;
    use App\Validators\StringValidator;

    class MainController extends Controller {

        public function home() {
            $categoryModel = new CategoryModel($this->getDatabaseConnection());
            $categories = $categoryModel->getAll();
            $this->set('categories', $categories);
        }

        public function getRegister() {
            # ...
        }

        public function postRegister() {
            $email      = filter_input(INPUT_POST, 'reg_email', FILTER_SANITIZE_EMAIL);
            $forename   = filter_input(INPUT_POST, 'reg_forename', FILTER_UNSAFE_RAW);    //TODO: Ovde isto ima par primeraka sa filter unsafe raw koje treba kasnije resiti nekako
            $surname    = filter_input(INPUT_POST, 'reg_surname', FILTER_UNSAFE_RAW);     // unsafe raw
            $username   = filter_input(INPUT_POST, 'reg_username', FILTER_UNSAFE_RAW);    // unsafe raw
            $password1  = filter_input(INPUT_POST, 'reg_password_1', FILTER_UNSAFE_RAW);  // unsafe raw
            $password2  = filter_input(INPUT_POST, 'reg_password_2', FILTER_UNSAFE_RAW);  // unsafe raw

            if ($password1 !== $password2) {
                $this->set('message', 'Doslo je do greske: Niste uneli istu lozinku u oba polja!');
                return;
            }

            if (!(new StringValidator())->setMinLength(7)->setMaxLength(120)->isValid($password1)) {
                $this->set('message', 'Doslo je do greske: Lozinka nije ispravnog formata!');
                return;
            }

            $userModel = new UserModel($this->getDatabaseConnection());
            $user = $userModel->getByFieldName('email', $email);
            if ($user) {
                $this->set('message', 'Doslo je do greske: Vec postoji korisnik sa tom adresom e-poste!');
                return;
            }

            $user = $userModel->getByFieldName('username', $username);
            if ($user) {
                $this->set('message', 'Doslo je do greske: Vec postoji korisnik sa tim korisnickim imenom!');
                return;
            }

            $passwordHash = password_hash($password1, PASSWORD_DEFAULT);

            $userId = $userModel->add([
                'email'    => $email,
                'forename' => $forename,
                'surname'  => $surname,
                'username' => $username,
                'password' => $passwordHash,
                'address'  => 'B.B. N.G.',         //TODO: ovo mora da se resi kada budem menjao bazu
                'phone'    => '0690000000',        // i ovo
                'salt'     => 'idk'                // a i ovo
            ]);

            if (!$userId) {
                $this->set('message', 'Doslo je do greske: Neuspesno registrovanje naloga!');
                return;
            }

            $this->set('message', 'Nalog je uspesno napravljen. Mozete da se prijavite!');
        }

        public function getLogin() {

        }

        public function postLogin() {
            $username = filter_input(INPUT_POST, 'login_username', FILTER_UNSAFE_RAW); //TODO: I OVDE JE UNSAFE RAW
            $password = filter_input(INPUT_POST, 'login_password', FILTER_UNSAFE_RAW); // takodje

            if (!(new StringValidator())->setMinLength(7)->setMaxLength(120)->isValid($password)) {
                $this->set('message', 'Doslo je do greske: Lozinka nije ispravnog formata!');
                return;
            }

            $userModel = new UserModel($this->getDatabaseConnection());
            $user = $userModel->getByFieldName('username', $username);
            if (!$user) {
                $this->set('message', 'Doslo je do greske: Ne postoji korisnik sa tim korisnickim imenom!');
                return;
            }

            if (!password_verify($password, $user->password)) {
                sleep(1); // TODO: Ovo je najprostija brute force zastita, treba to kasnije resiti
                $this->set('message', 'Doslo je do greske: Neispravna lozinka!');
                return;
            }

            $roleModel = new RoleModel($this->getDatabaseConnection());
            $role = $roleModel->getById($user->role_id);
            if (!$role) {
                $this->set('message', 'Doslo je do greske: Nepostjeca uloga!');
                return;
            }

            $this->getSession()->put('user_id', $user->user_id);
            $this->getSession()->put('role', $role->name);
            $this->getSession()->save();

            $this->redirect(\Configuration::BASE . 'user/profile');
        }

        public function getLogout() {
            $this->getSession()->remove('user_id');
            $this->getSession()->remove('role');
            $this->getSession()->save();

            $this->redirect(\Configuration::BASE);
        }

        public function notFound() {
            # ...
        }
    }