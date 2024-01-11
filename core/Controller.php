<?php
    namespace App\Core;

    use App\Core\Session\Session;

    class Controller {
        private DatabaseConnection $dbc;
        private $session;
        private $data = [];

        public function __pre() {
            $this->checkLogin();
        }

        final public function __construct(DatabaseConnection &$dbc){
            $this->dbc = $dbc;
        }

        final public function &getSession(): Session {
            return $this->session;
        }

        final public function setSession(Session &$session) {
            $this->session = $session;
        }

        final public function &getDatabaseConnection():DatabaseConnection {
            return $this->dbc;
        }

        final protected function set(string $name, $value): bool {
            $result = false;

            if (preg_match('/^[a-z][a-z0-9]+(?:[A-Z][a-z0-9]+)*$/', $name)){
                $this->data[$name] = $value;
                $result = true;
            }

            return $result;
        }

        final public function getData(): array {
            return $this->data;
        }

        protected function redirect(string $path, int $code = 303) { //307 zadrzava originalni metod kojim je poslato, dok 303 koristi get, zato je bolji 303
            ob_clean();
            header('Location: ' . $path, true, $code);
            exit;
        }

        protected function checkLogin() {
            if ($this->getSession()->get('user_id') === null) {
                $this->set('isLoggedIn', false);
            } else {
                $this->set('isLoggedIn', true);
            }
        }
    }