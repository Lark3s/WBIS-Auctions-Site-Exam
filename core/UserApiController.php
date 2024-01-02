<?php
    namespace App\Core;

    class UserApiController extends ApiController {
        public function __pre() {
            if ($this->getSession()->get('user_id') === null) {
                ob_clean();
                header('Content-type: application/json; charset=utf-8');

                echo json_encode([
                    'error'   => -10001,
                    'message' => 'This API call is available only to logged in users.'
                ]);

                exit;
            }
        }
    }