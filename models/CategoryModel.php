<?php
    namespace App\Models;

    use App\Core\Field;
    use App\Core\Model;
    use App\Validators\NumberValidator;
    use App\Validators\StringValidator;

    class CategoryModel extends Model {
        protected function getFields(): array
        {
            return [
                'category_id'   => new Field((new NumberValidator())->setIntegerLength(10), false),

                'name'          => new Field((new StringValidator())->setMaxLength(255))
            ];
        }
    }