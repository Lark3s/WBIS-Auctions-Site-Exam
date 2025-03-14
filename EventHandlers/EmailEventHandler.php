<?php
    namespace App\EventHandlers;

    use App\Core\EventHandler;
    use PHPMailer\PHPMailer\PHPMailer;

    class EmailEventHandler implements EventHandler {
        private $toAddresses;
        private $ccAddresses;
        private $bccAddresses;
        private $attachments;
        private $subject;
        private $body;
        private $attemptCount;

        public function addAddress(string $address) {
            $this->toAddresses[] = $address;
        }
        public function addCC(string $address) {
                    $this->ccAddresses[] = $address;
        }
        public function addBCC(string $address) {
                    $this->bccAddresses[] = $address;
        }
        public function addAttachment(string $path) {
                    $this->attachments[] = $path;
        }

        public function setSubject(string $subject) {
            $this->subject = $subject;
        }

        public function setBody(string $body) {
            $this->body = $body;
        }

        public function __construct() {
            $this->toAddresses  = [];
            $this->ccAddresses  = [];
            $this->bccAddresses = [];
            $this->attachments  = [];
            $this->subject      = '';
            $this->body         = '';
            $this->attemptCount = 0;
        }

        public function getData(): string {
            return json_encode( (object) [
                'to'    => $this->toAddresses,
                'cc'    => $this->ccAddresses,
                'bcc'   => $this->bccAddresses,
                'att'   => $this->attachments,
                'sub'   => $this->subject,
                'txt'   => $this->body,
                'count' => 0
            ]);
        }
        public function setData(string $serialisedData) {
            $data = json_decode($serialisedData);

            if (!$data) {
                return;
            }

            $this->toAddresses  = $data->to;
            $this->ccAddresses  = $data->cc;
            $this->bccAddresses = $data->bcc;
            $this->attachments  = $data->att;
            $this->subject      = $data->sub;
            $this->body         = $data->txt;
            $this->attemptCount = $data->count;

        }
        public function handle(): string {
            $mailer = new PHPMailer();
            $mailer->isSMTP();
            $mailer->Host = \Configuration::MAIL_HOST;
            $mailer->Port = \Configuration::MAIL_PORT;
            $mailer->SMTPSecure = \Configuration::MAIL_PROTOCOL;
            $mailer->SMTPAuth = true;
            $mailer->Username = \Configuration::MAIL_USERNAME;
            $mailer->Password = \Configuration::MAIL_PASSWORD;
            $mailer->setFrom(\Configuration::MAIL_USERNAME);
            $mailer->isHTML(true);

            $mailer->Body = $this->body;
            $mailer->Subject = $this->subject;

            foreach ($this->toAddresses as $address) {
                $mailer->addAddress($address);
            }

            foreach ($this->ccAddresses as $address) {
                $mailer->addCC($address);
            }

            foreach ($this->bccAddresses as $address) {
                $mailer->addBCC($address);
            }

            foreach ($this->attachments as $attachment) {
                $mailer->addAttachment($attachment);
            }

            $this->attemptCount++;
            $res = $mailer->send();

            if (!$res) {
                if ($this->attemptCount > 5) {
                    return 'failed';
                }
                return 'pending';
            }

            return 'done';
        }
    }