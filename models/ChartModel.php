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

        public function revenueByYearAndCategory($categoryId) {
            $sql = 'SELECT
                        YEAR(o.created_at) AS offer_year,
                        SUM(o.price)*0.15 AS sum_of_highest_offers
                        FROM
                            aukcije.auction a
                        JOIN (
                            SELECT
                                auction_id,
                                MAX(price) AS max_offer_amount
                            FROM
                                aukcije.offer
                            GROUP BY
                                auction_id
                        ) o_max ON a.auction_id = o_max.auction_id
                        JOIN
                            aukcije.offer o ON o_max.auction_id = o.auction_id AND o_max.max_offer_amount = o.price
                        WHERE
                            a.category_id = '.$categoryId.'
                        GROUP BY
                        a.category_id, YEAR(o.created_at) ORDER BY offer_year;';

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

        public function revenueByQuarterAndCategory($categoryId) {
            $sql = 'SELECT
                        YEAR(o.created_at) AS offer_year,
                        QUARTER(o.created_at) AS offer_quarter,
                        SUM(o.price)*0.15 AS sum_of_highest_offers
                        FROM
                            aukcije.auction a
                        JOIN (
                            SELECT
                                auction_id,
                                MAX(price) AS max_offer_amount
                            FROM
                                aukcije.offer
                            GROUP BY
                                auction_id
                        ) o_max ON a.auction_id = o_max.auction_id
                        JOIN
                            aukcije.offer o ON o_max.auction_id = o.auction_id AND o_max.max_offer_amount = o.price
                        WHERE
                            a.category_id = '.$categoryId.'
                        GROUP BY
                        a.category_id, YEAR(o.created_at), QUARTER(o.created_at) ORDER BY offer_year, offer_quarter;';

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

        public function revenueByMonthAndCategory($categoryId) {
            $sql = 'SELECT
                        YEAR(o.created_at) AS offer_year,
                        MONTH(o.created_at) AS offer_month,
                        SUM(o.price)*0.15 AS sum_of_highest_offers
                        FROM
                            aukcije.auction a
                        JOIN (
                            SELECT
                                auction_id,
                                MAX(price) AS max_offer_amount
                            FROM
                                aukcije.offer
                            GROUP BY
                                auction_id
                        ) o_max ON a.auction_id = o_max.auction_id
                        JOIN
                            aukcije.offer o ON o_max.auction_id = o.auction_id AND o_max.max_offer_amount = o.price
                        WHERE
                            a.category_id = '.$categoryId.'
                        GROUP BY
                        a.category_id, YEAR(o.created_at), MONTH(o.created_at) ORDER BY offer_year, offer_month;';

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

        public function revenueByWeekAndCategory($categoryId) {
            $sql = 'SELECT
                        YEAR(o.created_at) AS offer_year,
                        WEEK(o.created_at) AS offer_week,
                        SUM(o.price)*0.15 AS sum_of_highest_offers
                        FROM
                            aukcije.auction a
                        JOIN (
                            SELECT
                                auction_id,
                                MAX(price) AS max_offer_amount
                            FROM
                                aukcije.offer
                            GROUP BY
                                auction_id
                        ) o_max ON a.auction_id = o_max.auction_id
                        JOIN
                            aukcije.offer o ON o_max.auction_id = o.auction_id AND o_max.max_offer_amount = o.price
                        WHERE
                            a.category_id = '.$categoryId.'
                        GROUP BY
                        a.category_id, YEAR(o.created_at), WEEK(o.created_at)  ORDER BY offer_year, offer_week;';

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


        public function revenueByYear() {
            $sql = 'SELECT
                        YEAR(o.created_at) AS offer_year,
                        SUM(o.price)*0.15 AS sum_of_highest_offers
                        FROM
                            aukcije.auction a
                        JOIN (
                            SELECT
                                auction_id,
                                MAX(price) AS max_offer_amount
                            FROM
                                aukcije.offer
                            GROUP BY
                                auction_id
                        ) o_max ON a.auction_id = o_max.auction_id
                        JOIN
                            aukcije.offer o ON o_max.auction_id = o.auction_id AND o_max.max_offer_amount = o.price
                        
                        GROUP BY
                            
                        YEAR(o.created_at) ORDER BY offer_year;';

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

        public function revenueByQuarter() {
            $sql = 'SELECT
                        YEAR(o.created_at) AS offer_year,
                        QUARTER(o.created_at) AS offer_quarter,
                        SUM(o.price)*0.15 AS sum_of_highest_offers
                        FROM
                            aukcije.auction a
                        JOIN (
                            SELECT
                                auction_id,
                                MAX(price) AS max_offer_amount
                            FROM
                                aukcije.offer
                            GROUP BY
                                auction_id
                        ) o_max ON a.auction_id = o_max.auction_id
                        JOIN
                            aukcije.offer o ON o_max.auction_id = o.auction_id AND o_max.max_offer_amount = o.price
                        
                        GROUP BY
                        YEAR(o.created_at), QUARTER(o.created_at) ORDER BY offer_year, offer_quarter;';

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

        public function revenueByMonth() {
            $sql = 'SELECT
                        YEAR(o.created_at) AS offer_year,
                        MONTH(o.created_at) AS offer_month,
                        SUM(o.price)*0.15 AS sum_of_highest_offers
                        FROM
                            aukcije.auction a
                        JOIN (
                            SELECT
                                auction_id,
                                MAX(price) AS max_offer_amount
                            FROM
                                aukcije.offer
                            GROUP BY
                                auction_id
                        ) o_max ON a.auction_id = o_max.auction_id
                        JOIN
                            aukcije.offer o ON o_max.auction_id = o.auction_id AND o_max.max_offer_amount = o.price
                        GROUP BY
                         YEAR(o.created_at), MONTH(o.created_at) ORDER BY offer_year, offer_month;';

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

        public function revenueByWeek() {
            $sql = 'SELECT
                        YEAR(o.created_at) AS offer_year,
                        WEEK(o.created_at) AS offer_week,
                        SUM(o.price)*0.15 AS sum_of_highest_offers
                        FROM
                            aukcije.auction a
                        JOIN (
                            SELECT
                                auction_id,
                                MAX(price) AS max_offer_amount
                            FROM
                                aukcije.offer
                            GROUP BY
                                auction_id
                        ) o_max ON a.auction_id = o_max.auction_id
                        JOIN
                            aukcije.offer o ON o_max.auction_id = o.auction_id AND o_max.max_offer_amount = o.price
                        
                        GROUP BY
                        YEAR(o.created_at), WEEK(o.created_at)  ORDER BY offer_year, offer_week;';

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