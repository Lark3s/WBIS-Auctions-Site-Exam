<?php
    namespace App\Models;

    use App\Core\Model;

    class ChartModel extends Model {
        public function countAllByYears(string $tableName) {
            $sql = 'SELECT YEAR(`created_at`) AS `creation_year`, COUNT(*) AS `record_count` FROM `' . $tableName .'` GROUP BY `creation_year` ORDER BY `creation_year`;';

            $prep = $this->getConnection()->prepare($sql);
            if (!$prep) {
                return [];
            }

            $res = $prep->execute();
            if (!$res) {
                return [];
            }

            return $prep->fetchAll(\PDO::FETCH_OBJ);
        }

        public function countAllByQuarter(string $tableName) {
            $sql = 'SELECT YEAR(`created_at`) AS `creation_year`, QUARTER(`created_at`) AS `creation_quarter`, COUNT(*) AS `record_count` FROM `' . $tableName .'` GROUP BY `creation_year`, `creation_quarter` ORDER BY `creation_year`, `creation_quarter`;';

            $prep = $this->getConnection()->prepare($sql);
            if (!$prep) {
                return [];
            }

            $res = $prep->execute();
            if (!$res) {
                return [];
            }

            return $prep->fetchAll(\PDO::FETCH_OBJ);
        }

        public function countAllByMonth(string $tableName) {
            $sql = 'SELECT YEAR(`created_at`) AS `creation_year`,MONTH(`created_at`) AS `creation_month`, COUNT(*) AS `record_count` FROM `' . $tableName .'` GROUP BY `creation_year`, `creation_month` ORDER BY `creation_year`, `creation_month`;';

            $prep = $this->getConnection()->prepare($sql);
            if (!$prep) {
                return [];
            }

            $res = $prep->execute();
            if (!$res) {
                return [];
            }

            return $prep->fetchAll(\PDO::FETCH_OBJ);
        }

        public function countAllByWeek(string $tableName) {
            $sql = 'SELECT YEARWEEK(`created_at`) AS `creation_yearweek`, COUNT(*) AS `record_count` FROM `' . $tableName .'` GROUP BY `creation_yearweek` ORDER BY `creation_yearweek`;';

            $prep = $this->getConnection()->prepare($sql);
            if (!$prep) {
                return [];
            }

            $res = $prep->execute();
            if (!$res) {
                return [];
            }

            return $prep->fetchAll(\PDO::FETCH_OBJ);
        }
    }