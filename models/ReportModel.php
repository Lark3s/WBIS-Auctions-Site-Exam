<?php
    namespace App\Models;

    use App\Core\Field;
    use App\Core\Model;
    use App\Validators\NumberValidator;
    use App\Validators\StringValidator;

    class ReportModel extends Model {
//        protected function getFields(): array
//        {
//            return [
//                'year'   => new Field((new NumberValidator())->setIntegerLength(4), false),
//                'name'   => new Field((new StringValidator())->setMaxLength(255))
//            ];
//        }

        public function newUsers($year, $time, $type){
            $sql = '';
            switch ($type) {
                case "year":
                    $sql = 'SELECT COUNT(*) AS entry_count FROM user WHERE YEAR(created_at) = '.$year.';';
                    break;
                case "quarter":
                    $sql = 'SELECT COUNT(*) AS entry_count FROM user WHERE YEAR(created_at) = '.$year.' AND QUARTER(created_at) = '.$time.';';
                    break;
                case "month":
                    $sql = 'SELECT COUNT(*) AS entry_count FROM user WHERE YEAR(created_at) = '.$year.' AND MONTH(created_at) = '.$time.';';
                    break;
                case "week":
                    $sql = 'SELECT COUNT(*) AS entry_count FROM user WHERE YEAR(created_at) = '.$year.' AND WEEK(created_at) = '.$time.';';
                    break;
                default:
                    return null;
            }

            $prep = $this->getConnection()->prepare($sql);
            if (!$prep) {
                return [];
            }

            $res = $prep->execute();
            if (!$res) {
                return [];
            }

            $noOfUsers = $prep->fetch(\PDO::FETCH_OBJ);
            if (!$noOfUsers){
                return '';
            }
            return $noOfUsers->entry_count;
        }

        public function newAuctions($year, $time, $type){
            $sql = '';
            switch ($type) {
                case "year":
                    $sql = 'SELECT COUNT(*) AS entry_count FROM auction WHERE YEAR(created_at) = '.$year.';';
                    break;
                case "quarter":
                    $sql = 'SELECT COUNT(*) AS entry_count FROM auction WHERE YEAR(created_at) = '.$year.' AND QUARTER(created_at) = '.$time.';';
                    break;
                case "month":
                    $sql = 'SELECT COUNT(*) AS entry_count FROM auction WHERE YEAR(created_at) = '.$year.' AND MONTH(created_at) = '.$time.';';
                    break;
                case "week":
                    $sql = 'SELECT COUNT(*) AS entry_count FROM auction WHERE YEAR(created_at) = '.$year.' AND WEEK(created_at) = '.$time.';';
                    break;
                default:
                    return null;
            }

            $prep = $this->getConnection()->prepare($sql);
            if (!$prep) {
                return [];
            }

            $res = $prep->execute();
            if (!$res) {
                return [];
            }

            $noOfAuctions = $prep->fetch(\PDO::FETCH_OBJ);
            if (!$noOfAuctions){
                return '';
            }
            return $noOfAuctions->entry_count;
        }

        public function revenue($year, $time, $type){
            $sql = '';
            switch ($type) {
                case "year":
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
                        WHERE year(o.created_at) = '.$year.'
                        GROUP BY
                        YEAR(o.created_at) ORDER BY offer_year;';
                    break;
                case "quarter":
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
                        WHERE YEAR(o.created_at) = '.$year.' AND QUARTER(o.created_at) = '.$time.'
                        GROUP BY
                        YEAR(o.created_at), QUARTER(o.created_at) ORDER BY offer_year, offer_quarter;';
                    break;
                case "month":
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
                        WHERE YEAR(o.created_at) = '.$year.' AND MONTH(o.created_at) = '.$time.'
                        GROUP BY
                         YEAR(o.created_at), MONTH(o.created_at) ORDER BY offer_year, offer_month;';
                    break;
                case "week":
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
                        WHERE YEAR(o.created_at) = '.$year.' AND WEEK(o.created_at) = '.$time.'
                        GROUP BY
                        YEAR(o.created_at), WEEK(o.created_at)  ORDER BY offer_year, offer_week;';
                    break;
                default:
                    return null;
            }
            $prep = $this->getConnection()->prepare($sql);
            if (!$prep) {
                return [];
            }

            $res = $prep->execute();
            if (!$res) {
                return [];
            }

            $revenue = $prep->fetch(\PDO::FETCH_OBJ);
            if (!$revenue){
                return '';
            }
            return $revenue->sum_of_highest_offers;
        }

        public function mostPopularCategory($year, $time, $type){
            $sql = '';
            switch ($type) {
                case "year":
                    $sql = 'SELECT c.name AS category_name, COUNT(a.auction_id) AS num_auctions
                            FROM aukcije.category c
                            JOIN aukcije.auction a ON c.category_id = a.category_id
                            WHERE YEAR(a.created_at) = '.$year.'
                            GROUP BY c.category_id
                            ORDER BY num_auctions DESC
                            LIMIT 1;';
                    break;
                case "quarter":
                    $sql = 'SELECT c.name AS category_name, COUNT(a.auction_id) AS num_auctions
                            FROM aukcije.category c
                            JOIN aukcije.auction a ON c.category_id = a.category_id
                            WHERE YEAR(a.created_at) = '.$year.' AND QUARTER(a.created_at) = '.$time.'
                            GROUP BY c.category_id
                            ORDER BY num_auctions DESC
                            LIMIT 1;';
                    break;
                case "month":
                    $sql = 'SELECT c.name AS category_name, COUNT(a.auction_id) AS num_auctions
                            FROM aukcije.category c
                            JOIN aukcije.auction a ON c.category_id = a.category_id
                            WHERE YEAR(a.created_at) = '.$year.' AND MONTH(a.created_at) = '.$time.'
                            GROUP BY c.category_id
                            ORDER BY num_auctions DESC
                            LIMIT 1;';
                    break;
                case "week":
                    $sql = 'SELECT c.name AS category_name, COUNT(a.auction_id) AS num_auctions
                            FROM aukcije.category c
                            JOIN aukcije.auction a ON c.category_id = a.category_id
                            WHERE YEAR(a.created_at) = '.$year.' AND WEEK(a.created_at) = '.$time.'
                            GROUP BY c.category_id
                            ORDER BY num_auctions DESC
                            LIMIT 1;';
                    break;
                default:
                    return null;
            }

            $prep = $this->getConnection()->prepare($sql);
            if (!$prep) {
                return [];
            }

            $res = $prep->execute();
            if (!$res) {
                return [];
            }

            $category = $prep->fetch(\PDO::FETCH_OBJ);
            if (!$category){
                return '';
            }
            return $category->category_name;
        }

        public function mostPopularAuction($year, $time, $type){
            $sql = '';
            switch ($type) {
                case "year":
                    $sql = 'SELECT a.title AS auction_name, COUNT(v.auction_id) AS view_count
                            FROM aukcije.auction a
                            JOIN aukcije.auction_view v ON a.auction_id = v.auction_id
                            WHERE YEAR(v.created_at) = '.$year.'
                            GROUP BY a.title
                            ORDER BY view_count DESC
                            LIMIT 1;';
                    break;
                case "quarter":
                    $sql = 'SELECT a.title AS auction_name, COUNT(v.auction_id) AS view_count
                            FROM aukcije.auction a
                            JOIN aukcije.auction_view v ON a.auction_id = v.auction_id
                            WHERE YEAR(v.created_at) = '.$year.' AND QUARTER(v.created_at) = '.$time.'
                            GROUP BY a.title
                            ORDER BY view_count DESC
                            LIMIT 1;';
                    break;
                case "month":
                    $sql = 'SELECT a.title AS auction_name, COUNT(v.auction_id) AS view_count
                            FROM aukcije.auction a
                            JOIN aukcije.auction_view v ON a.auction_id = v.auction_id
                            WHERE YEAR(v.created_at) = '.$year.' AND MONTH(v.created_at) = '.$time.'
                            GROUP BY a.title
                            ORDER BY view_count DESC
                            LIMIT 1;';
                    break;
                case "week":
                    $sql = 'SELECT a.title AS auction_name, COUNT(v.auction_id) AS view_count
                            FROM aukcije.auction a
                            JOIN aukcije.auction_view v ON a.auction_id = v.auction_id
                            WHERE YEAR(v.created_at) = '.$year.' AND WEEK(v.created_at) = '.$time.'
                            GROUP BY a.title
                            ORDER BY view_count DESC
                            LIMIT 1;';
                    break;
                default:
                    return null;
            }

            $prep = $this->getConnection()->prepare($sql);
            if (!$prep) {
                return '';
            }

            $res = $prep->execute();
            if (!$res) {
                return '';
            }

            $auction = $prep->fetch(\PDO::FETCH_OBJ);
            if (!$auction){
                return '';
            }
            return $auction->auction_name;
        }
    }