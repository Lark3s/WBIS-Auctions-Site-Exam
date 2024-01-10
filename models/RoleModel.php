<?php

namespace App\models;

use App\Core\Field;
use App\Core\Model;
use App\Validators\DateTimeValidator;
use App\Validators\NumberValidator;
use App\Validators\StringValidator;

class RoleModel extends Model {
    protected function getFields(): array
    {
        return [
            'user_id'    => new Field((new NumberValidator())->setIntegerLength(10), false),
            'created_at' => new Field((new DateTimeValidator())->allowDate()->allowTime(), false),

            'name'       => new Field((new StringValidator())->setMaxLength(45))
        ];
    }
}