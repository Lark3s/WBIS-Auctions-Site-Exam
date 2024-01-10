<?php
    namespace App\Core\Session;

    use App\Core\Fingerprint\FingerprintProvider;

    final class Session {
        private $sessionStorage;
        private $sessionData;
        private $sessionId;
        private $sessionLife;
        private $fingerprintProvider;

        public function __construct(SessionStorage $sessionStorage, int $sessionLife = 1800) {
            $this->sessionStorage = $sessionStorage;
            $this->sessionData = (object) [];
            $this->sessionLife = $sessionLife;
            $this->fingerprintProvider = null;

            $this->sessionId = filter_input(INPUT_COOKIE, 'APPSESSION', FILTER_UNSAFE_RAW); // TODO: Temporary, change input filtering to something else, the planned option was unsafe and is currently deprecated!!!
            $this->sessionId = preg_replace('|[^A-Za-z0-9]|','', $this->sessionId);

            if (strlen($this->sessionId) !== 32) {
                $this->sessionId = $this->generateSessionId();
                setcookie('APPSESSION', $this->sessionId, time() + $this->sessionLife, '/'); // TODO: This can be it's own function
            }
        }

        public function setFingerprintProvider(FingerprintProvider $fp) {
            $this->fingerprintProvider = $fp;
        }

        private function generateSessionId(): string {
            $supportedChars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
            $id = "";
            for ($i=0; $i<32; $i++) {
                $id .= $supportedChars[rand(0, strlen($supportedChars)-1)];
            }
            return $id;
        }

        public function put(string $key, $value) {
            $this->sessionData->$key = $value;
        }

        public function get(string $key, $defaultValue = null) {
            return $this->sessionData->$key ?? $defaultValue;
        }

        public function remove(string $key) {
            if ($this->exists($key)) {
                unset($this->sessionData->$key);
            }
        }

        public function clear() {
            $this->sessionData = (object) [];
        }

        public function exists(string $key): bool {
            return isset($this->sessionData->$key);
        }

        public function has(string $key): bool {
            if (!$this->exists($key)) {
                return false;
            }

            return boolval($this->sessionData->$key);
        }

        public function save() {
            $fingerprint = $this->fingerprintProvider->provideFingerprint();
            $this->sessionData->__fingerprint = $fingerprint;

            $jsonData = json_encode($this->sessionData);
            $this->sessionStorage->save($this->sessionId, $jsonData);
            setcookie('APPSESSION', $this->sessionId, time() + $this->sessionLife, '/');
        }

        public function reload() {
            $jsonData = $this->sessionStorage->load($this->sessionId);
            $restoredData = json_decode($jsonData);

            if (!$restoredData) {
                $this->sessionData = (object) [];
                return;
            }

            $this->sessionData = $restoredData;

            if ($this->fingerprintProvider === null) {
                return;
            }

            $savedFingerprint = $this->sessionData->__fingerprint ?? null;

            if ($savedFingerprint === null) {
                return;
            }

            $currentFingerprint = $this->fingerprintProvider->provideFingerprint();

            //proverava da li se trenutna sesija poklapa sa sacuvanom, u slucaju da ne, cisti sesiju(session hijack zastita)
            if ($currentFingerprint !== $savedFingerprint) {
                $this->clear();
                $this->sessionStorage->delete($this->sessionId);
                $this->sessionId = $this->generateSessionId();
                $this->save();
                setcookie('APPSESSION', $this->sessionId, time() + $this->sessionLife, '/');
            }
        }

        public function regenerate() {
            $this->reload();
            $this->sessionStorage->delete($this->sessionId);
            $this->sessionId = $this->generateSessionId();
            $this->save();
            setcookie('APPSESSION', $this->sessionId, time() + $this->sessionLife, '/');
        }


    }