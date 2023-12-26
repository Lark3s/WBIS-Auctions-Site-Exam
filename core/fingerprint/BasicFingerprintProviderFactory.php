<?php
    namespace App\Core\Fingerprint;

    class BasicFingerprintProviderFactory {
        public function getInstance(string $arraySource): BasicFingerprintProvider {
            switch ($arraySource) {
                case 'SERVER' :
                    return new BasicFingerprintProvider($_SERVER);
            }

            return new BasicFingerprintProvider($_SERVER);
        }
    }