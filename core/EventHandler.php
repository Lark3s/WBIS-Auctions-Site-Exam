<?php
    namespace App\Core;

    interface EventHandler {
        public function getData(): string;
        public function setData(string $serialisedData);
        public function handle(): string;
    }