<?php
    namespace App\Core;

    class DatabaseConnection {
        private $connection;
        private $configuration;
        public function __construct(DatabaseConfig $databaseConfig) {
            $this->configuration = $databaseConfig;
        }

        public function getConnection():\PDO{
            if ($this->connection === NULL) {
                $this->connection = new \PDO($this->configuration->getSourceString(), $this->configuration->getUser(), $this->configuration->getPassword());
            }

            return $this->connection;
        }
    }
