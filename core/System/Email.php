<?php

namespace System;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Email
{
    /**
     * Application Object
     *
     * @var \System\Application
     */
    private $app;

    /**
     * PHPMailer Object
     *
     * @var PHPMailer
     */
    private $mail;

    /**
     * Constructor
     *
     * @param \System\Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;

        $this->mail = new PHPMailer(true);

        $this->setUp();
    }

    /**
     * Set up the configrations
     *
     * @property object $error
     * @return void
     */
    private function setUp()
    {
        $this->mail->SMTPDebug = $this->error->allowDisplayingError() ? SMTP::DEBUG_SERVER : 0;
        $this->mail->isSMTP();
        $this->mail->Host = $_ENV['EMAIL_HOST'];
        $this->mail->SMTPAuth = $_ENV['EMAIL_SMTPAUTH'];
        $this->mail->Username = $_ENV['EMAIL_USERNAME'];
        $this->mail->Password = $_ENV['EMAIL_PASSWORD'];
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mail->Port = $_ENV['EMAIL_PORT'];
    }

    /**
     * To add addresses or attachments easily to the object
     *
     * @return void
     */
    private function add($input, $add)
    {
        if (!is_array($input)) {
            $input = [$input];
        }

        foreach ($input as $key => $value) {
            if (is_numeric($key)) {
                $this->mail->$add($value);
            } else {
                $this->mail->$add($value, $key);
            }
        }
    }

    /**
     * Add recipients to email
     *
     * @param string|array $addresses
     * @param array $replayTo
     * @param string $cc
     * @param string $bcc
     * @return $this
     */
    public function recipients($addresses, array $replayTo = [], $cc = null, $bcc = null)
    {
        $this->mail->setFrom($_ENV['EMAIL_ADMIN'], $_ENV['EMAIL_NAME']);

        $this->addresses($addresses);

        if (!empty($replayTo)) {
            $this->mail->addReplyTo(array_values($replayTo)[0], array_keys($replayTo)[0]);
        }

        if ($cc) {
            $this->mail->addCC($cc);
        }

        if ($bcc) {
            $this->mail->addBCC($bcc);
        }

        return $this;
    }

    /**
     * Add addresses
     *
     * @param string|array $addresses
     * @return void
     */
    private function addresses($addresses)
    {
        $this->add($addresses, 'addAddress');
    }

    /**
     * Add attachments to email
     *
     * @param string|array $attachments
     * @return $this
     */
    public function attachments($attachments)
    {
        $this->add($attachments, 'addAttachment');

        return $this;
    }

    /**
     * Add content to email
     *
     * @param string $html
     * @param string $subject
     * @param string $body
     * @param string $altBody
     * @return $this
     */
    public function content($html, $subject, $body, $altBody)
    {
        $this->mail->isHTML($html);
        $this->mail->Subject = $subject;
        $this->mail->Body = $body;
        $this->mail->AltBody = $altBody;

        return $this;
    }

    /**
     * Send email
     *
     * @return void
     */
    public function send()
    {
        try {
            $this->mail->send();
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error';
        }
    }
}
