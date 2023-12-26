<?php
    namespace App\Core\Fingerprint;

    class BasicFingerprintProvider implements FingerprintProvider {
        private $data;

        public function __construct(array $data) {
            $this->data = $data;
        }

        //digitalni otisak je heš od heša user agenta + ip adrese
        public function provideFingerprint(): string
        {
            $userAgent = filter_var($this->data['HTTP_USER_AGENT'] ?? '', FILTER_UNSAFE_RAW); // TODO: This one is also filter_unsafe_raw, like Session.php line 96
            $ipAddress = filter_var($this->data['REMOTE_ADDR'] ?? '', FILTER_UNSAFE_RAW); // TODO: This one is also filter_unsafe_raw, like Session.php line 96, also I should think about not using IP address for fingerprints because how common it is to switch addresses nowadays
            $string = $userAgent . '|' . $ipAddress;
            $hash1 = hash('sha512', $string);
            return hash('sha512', $hash1);
        }
    }