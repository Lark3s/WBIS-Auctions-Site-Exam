<?php

    namespace App\Models;

    use App\Core\Field;
    use App\Core\Model;
    use App\Validators\DateTimeValidator;
    use App\Validators\NumberValidator;

    class OfferModel extends Model {
        protected function getFields(): array
        {
            return [
                'offer_id'   => new Field((new NumberValidator())->setIntegerLength(10), false),
                'created_at' => new Field((new DateTimeValidator())->allowDate()->allowTime(), false),

                'user_id'    => new Field((new NumberValidator())->setIntegerLength(10), false),
                'auction_id' => new Field((new NumberValidator())->setIntegerLength(10), false),
                'price'      => new Field((new NumberValidator())->setDecimal()->setUnsigned()->setIntegerLength(7)->setMaxDecimalDigits(2))
            ];
        }
        public function getAllByAuctionId(int $auctionId): array {
            $items = $this->getAllByFieldName('auction_id', $auctionId);

            usort($items, function ($a, $b){
                return strcmp($a->created_at, $b->created_at);
            });

            return $items;
        }
    }