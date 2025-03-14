<?php
    namespace App\Core;

    abstract class Model {
        private DatabaseConnection $dbc;

        final function __construct(DatabaseConnection &$dbc) {
            $this->dbc = $dbc;
        }

        protected function getFields():array {
            return [  ];
        }

        final protected function getConnection()  {
            return $this->dbc->getConnection();
        }

        private function getTableName(): string {
            $matches = [];
            preg_match('|^.*\\\((?:[A-Z][a-z]+)+)Model$|', static::class, $matches);
            return substr(strtolower(preg_replace('|[A-Z]|', '_$0', $matches[1] ?? '')), 1);
        }

        public function getById(int $id) {
            $tableName = $this->getTableName();
            $sql = 'SELECT * FROM ' . $tableName . ' WHERE ' . $tableName . '_id = ?;';
            $prep = $this->dbc->getConnection()->prepare($sql);
            $res = $prep->execute([$id]);
            $item = NULL;
            if ($res) {
                $item = $prep->fetch(\PDO::FETCH_OBJ);
            }
            return $item;
        }

        public function getAll(): array {
            $tableName = $this->getTableName();
            $sql = 'SELECT * FROM ' . $tableName . ';';
            $prep = $this->dbc->getConnection()->prepare($sql);
            $res = $prep->execute();
            $items = [];
            if ($res) {
                $items = $prep->fetchAll(\PDO::FETCH_OBJ);
            }
            return $items;
        }

        private function isFieldValueValid(string $fieldName, $fieldValue):bool {
            $fields = $this->getFields();
            $supportedFieldNames = array_keys($fields);
            if (!in_array($fieldName, $supportedFieldNames)) {
                return false;
            }

            return $fields[$fieldName]->isValid($fieldValue);
        }

        public function getByFieldName(string $fieldName, $value) {
            if (!$this->isFieldValueValid($fieldName, $value)) {
                throw new \Exception('Invalid field name or value: ' . $fieldName);
            }
            $tableName = $this->getTableName();
            $sql = 'SELECT * FROM ' . $tableName . ' WHERE ' . $fieldName . ' = ?;';
            $prep = $this->dbc->getConnection()->prepare($sql);
            $res = $prep->execute([$value]);
            $item = NULL;
            if ($res) {
                $item = $prep->fetch(\PDO::FETCH_OBJ);
            }
            return $item;
        }

        public function getAllByFieldName(string $fieldName, $value): array {
            if (!$this->isFieldValueValid($fieldName, $value)) {
                throw new \Exception('Invalid field name or value: ' . $fieldName);
            }
            $tableName = $this->getTableName();
            $sql = 'SELECT * FROM ' . $tableName . ' WHERE ' . $fieldName . ' = ?;';
            $prep = $this->dbc->getConnection()->prepare($sql);
            $res = $prep->execute([$value]);
            $items = [];
            if ($res) {
                $items = $prep->fetchAll(\PDO::FETCH_OBJ);
            }
            return $items;
        }

        private function checkFieldList(array $data): void
        {
            $fields = $this->getFields();

            $supportedFieldNames = array_keys($fields);
            $requestedFieldNames = array_keys($data);

            foreach ($requestedFieldNames as $requestedFieldName) {
                if ( !in_array($requestedFieldName, $supportedFieldNames) ) {
                    throw new \Exception('Field ' . $requestedFieldName . 'is not supported.');
                }
                if ( !$fields[$requestedFieldName]->isEditable() ) {
                    throw new \Exception('Field ' . $requestedFieldName . ' is not editable.');
                }
                if ( !$fields[$requestedFieldName]->isValid($data[$requestedFieldName]) ) {
                    throw new \Exception('The value for the field ' . $requestedFieldName . ' is invalid.');
                }
            }
        }

        final public function add(array $data) {
            $this->checkFieldList($data);

            $tableName = $this->getTableName();

            $sqlFieldNames = implode(', ', array_keys($data));

            $questionMarksPrep = str_repeat('?, ', count($data));
            $questionMarksFinal = substr($questionMarksPrep, 0, -2);

            $sql = "INSERT INTO {$tableName} ({$sqlFieldNames}) VALUES ({$questionMarksFinal});";
            $prep = $this->dbc->getConnection()->prepare($sql);
            $res = $prep->execute(array_values($data));
            if (!$res) {
                return false;
            }

            return $this->dbc->getConnection()->lastInsertId();
        }

        final public function editById(int $id, array $data) {
            $this->checkFieldList($data);

            $tableName = $this->getTableName();

            $editList = [];
            $values = [];
            foreach ( $data as $fieldName => $value) {
                $editList[] = "{$fieldName} = ?";
                $values[] = $value;
            }
            $editString = implode(', ', $editList);

            $values[] = $id;

            $sql = "UPDATE {$tableName} SET {$editString} WHERE {$tableName}_id = ?;";
            $prep = $this->dbc->getConnection()->prepare($sql);
            return $prep->execute($values);
        }

        final public function deleteById(int $id) {
            $tableName = $this->getTableName();
            $sql = 'DELETE FROM' . $tableName . ' WHERE ' . $tableName . '_id = ?;';
            $prep = $this->dbc->getConnection()->prepare($sql);
            return $prep->execute([$id]);
        }
    }