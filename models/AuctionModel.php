<?php

    namespace App\Models;

    use App\Core\Field;
    use App\Core\Model;
    use App\Validators\DateTimeValidator;
    use App\Validators\NumberValidator;
    use App\Validators\StringValidator;

    class AuctionModel extends Model {
        protected function getFields(): array
        {
            return [
                'auction_id'      => new Field((new NumberValidator())->setIntegerLength(10), false),
                'created_at'      => new Field((new DateTimeValidator())->allowDate()->allowTime(), false),

                'expires_at'      => new Field((new DateTimeValidator())->allowDate()->allowTime()),
                'user_id'         => new Field((new NumberValidator())->setIntegerLength(10)),
                'category_id'     => new Field((new NumberValidator())->setIntegerLength(10)),
                'title'           => new Field((new StringValidator())->setMaxLength(255)),
                'image_path'      => new Field((new StringValidator())->setMaxLength(255)),
                'description'     => new Field((new StringValidator())->setMaxLength(64*1024)),
                'starting_price'  => new Field((new NumberValidator())->setDecimal()->setUnsigned()->setIntegerLength(7)->setMaxDecimalDigits(2))
            ];
        }
        public function getAllByCategoryId(int $categoryId): array {
            return $this->getAllByFieldName('category_id', $categoryId);
        }
    }