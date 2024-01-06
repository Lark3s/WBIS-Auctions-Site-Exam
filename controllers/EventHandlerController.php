<?php
    namespace App\Controllers;

    use App\Core\Controller;
    use App\EventHandlers\EmailEventHandler;
    use App\Models\EventModel;

    class EventHandlerController extends Controller {
        public function handle(string $type) {
            $host = filter_input(INPUT_SERVER, 'HTTP_HOST');

            if ($host !== 'localhost') {
                return;
            }

            $eventModel = new EventModel($this->getDatabaseConnection());
            $events = $eventModel->getAllByTypeAndStatus($type, 'pending');

            if (!count($events)) {
                return;
            }

            if ($type === 'email') {
                $this->handleEmails($events);
            }
        }

        private function handleEmails(array $events) {
            foreach ($events as $event) {
                $this->handleEmailEvent($event);
            }
        }

        private function handleEmailEvent($event) {
            $eventModel = new EventModel($this->getDatabaseConnection());

            $emailEventHandler = new EmailEventHandler();
            $emailEventHandler->setData($event->data);

            $eventModel->editById($event->event_id, [
                'status' => 'started'
            ]);

            $newStatus = $emailEventHandler->handle();

            $eventModel->editById($event->event_id, [
                'status' => $newStatus
            ]);
        }
    }