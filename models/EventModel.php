<?php
    namespace App\Models;

    use App\Core\Field;
    use App\Core\Model;
    use App\Validators\DateTimeValidator;
    use App\Validators\NumberValidator;
    use App\Validators\StringValidator;

    class EventModel extends Model {
        protected function getFields(): array
        {
            return [
                'event_id'        => new Field((new NumberValidator())->setIntegerLength(20), false),
                'created_at'      => new Field((new DateTimeValidator())->allowDate()->allowTime(), false),

                'type'            => new Field((new StringValidator())->setMaxLength(255)),
                'data'            => new Field((new StringValidator())->setMaxLength(64*1024)),
                'status'          => new Field((new StringValidator())->setMaxLength(255))
            ];
        }

        public function getAllByTypeAndStatus(string $type, string $status) {
            $sql = 'SELECT * FROM `event` WHERE `type` = ? AND `status` = ?;';
            $prep = $this->getConnection()->prepare($sql);
            $res = $prep->execute([$type, $status]);

            if (!$res) {
                return [];
            }

            return $prep->fetchAll(\PDO::FETCH_OBJ);
        }
    }