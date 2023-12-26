<?php
final class Config {
    //TODO: to run the project you should change the file name to Configuration
    // also change the class name to Configuration
    // then put your DB user and pass in constants called DATABASE_USER and DATABASE_PASSWORD

    const BASE = 'http://localhost/';
    const DATABASE_HOST = 'localhost';
    const DATABASE_USER = '<insert your db password>';
    const DATABASE_PASSWORD = '<insert your db password>';
    const DATABASE_NAME = 'aukcije';

    const SESSION_STORAGE = '\\App\\Core\\Session\\FileSessionStorage';
    const SESSION_STORAGE_DATA = [ './sessions/' ];
    const SESSION_LIFETIME = 3600;

    const FINGERPRINT_PROVIDER_FACTORY = '\\App\\Core\\Fingerprint\\BasicFingerprintProviderFactory';
    const FINGERPRINT_PROVIDER_METHOD = 'getInstance';
    const FINGERPRINT_PROVIDER_ARGS = [ 'SERVER' ];
}