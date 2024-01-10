<?php
    namespace App\Models;

    use App\Core\Field;
    use App\Core\Model;
    use App\Validators\BitValidator;
    use App\Validators\DateTimeValidator;
    use App\Validators\NumberValidator;
    use App\Validators\StringValidator;

    class UserModel extends Model {
        protected function getFields(): array
        {
            return [
                'user_id'    => new Field((new NumberValidator())->setIntegerLength(10), false),
                'created_at' => new Field((new DateTimeValidator())->allowDate()->allowTime(), false),

                'forename'   => new Field((new StringValidator())->setMaxLength(255)),
                'surname'    => new Field((new StringValidator())->setMaxLength(255)),
                'address'    => new Field((new StringValidator())->setMaxLength(64*1024)),
                'phone'      => new Field((new StringValidator())->setMaxLength(64)),
                'email'      => new Field((new StringValidator())->setMaxLength(255)),
                'username'   => new Field((new StringValidator())->setMaxLength(64)),
                'password'   => new Field((new StringValidator())->setMaxLength(128)),
                'salt'       => new Field((new StringValidator())->setMaxLength(64*1024)),
                'is_active'  => new Field(new BitValidator()),
                'role_id'    => new Field((new NumberValidator())->setIntegerLength(10))
            ];
        }
        public function getByUsername(int $username) {
            return $this->getByFieldName('username', $username);
        }
    }