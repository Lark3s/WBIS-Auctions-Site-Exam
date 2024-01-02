<?php

    namespace App\Models;

    use App\Core\Field;
    use App\Core\Model;
    use App\Validators\DateTimeValidator;
    use App\Validators\NumberValidator;
    use App\Validators\StringValidator;

    class AuctionViewModel extends Model {
        protected function getFields(): array
        {
            return [
                'auction_view_id' => new Field((new NumberValidator())->setIntegerLength(20), false),
                'created_at'      => new Field((new DateTimeValidator())->allowDate()->allowTime(), false),
                'auction_id'      => new Field((new NumberValidator())->setIntegerLength(11), true),
                'ip_address'      => new Field((new StringValidator())->setMaxLength(24)), // TODO: Napraviti IP address validator!!!!
                'user_agent'      => new Field((new StringValidator())->setMaxLength(255))
            ];
        }

        public function getAllByAuctionId(int $auctionId): array {
            return $this->getAllByFieldName('auction_id', $auctionId);
        }

        public function getAllByIpAddress(string $ipAddress): array {
            return $this->getAllByFieldName('ip_address', $ipAddress);
        }
    }