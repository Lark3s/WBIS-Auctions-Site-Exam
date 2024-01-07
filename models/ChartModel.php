<?php
    namespace App\Models;

    use App\Core\Model;

    class ChartModel extends Model {
        public function countAllByYears(string $tableName) {
            $sql = 'SELECT YEAR(`created_at`) AS `creation_year`, COUNT(*) AS `record_count` FROM `' . $tableName .'` GROUP BY `creation_year` ORDER BY `creation_year`';

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