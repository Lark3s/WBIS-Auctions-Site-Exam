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

        public function getCategoryIdFromName($name) {
            $sql = 'SELECT `category_id` FROM `category` WHERE `name` = ?;';
            $prep = $this->getConnection()->prepare($sql);
            $res = $prep->execute([$name]);
            $item = NULL;
            if ($res) {
                $item = $prep->fetch(\PDO::FETCH_OBJ);
            }
            return $item;
        }


    }