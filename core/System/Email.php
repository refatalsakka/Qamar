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
        $this->mail->SMTPDebug = $this->app->error->allowDisplayingError() ? SMTP::DEBUG_SERVER : 0;
        $this->mail->isSMTP();
        $this->mail->Host = $_ENV['EMAIL_HOST'];
        $this->mail->SMTPAuth = $_ENV['EMAIL_SMTPAUTH'];
        $this->mail->Username = $_ENV['EMAIL_USERNAME'];
        $this->mail->Password = $_ENV['EMAIL_PASSWORD'];
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mail->Port = $_ENV['EMAIL_PORT'];
        $this->mail->setFrom($_ENV['EMAIL_ADMIN'], $_ENV['EMAIL_NAME']);
    }

    /**
     * To add addresses or attachments easily to the object
     *
     * @param string|array $input
     * @param string $method
     * @return void
     */
    private function add($input, $method)
    {
        if (!is_array($input)) {
            $input = [$input];
        }

        foreach ($input as $key => $value) {
            if (is_numeric($key)) {
                $this->mail->$method($value);
            } else {
                $this->mail->$method($value, $key);
            }
        }
    }

    /**
     * Add addresses
     *
     * @param string|array $addresses
     * @return object $this
     */
    public function address($addresses)
    {
        $this->add($addresses, 'addAddress');

        return $this;
    }

    /**
     * Add addresses
     *
     * @param array $replayTo
     * @return object $this
     */
    public function replayTo(array $replayTo = [])
    {
        $this->mail->addReplyTo(array_values($replayTo)[0], array_keys($replayTo)[0]);

        return $this;
    }

    /**
     * Add attachments to email
     *
     * @param string|array $attachments
     * @return object $this
     */
    public function attachments($attachments)
    {
        $this->add($attachments, 'addAttachment');

        return $this;
    }

    /**
     * Add bcc to email
     *
     * @param string|array $bcc
     * @return object $this
     */
    public function bcc($bcc)
    {
        $this->add($bcc, 'addBCC');

        return $this;
    }

    /**
     * Add cc to email
     *
     * @param string|array $cc
     * @return object $this
     */
    public function cc($cc)
    {
        $this->add($cc, 'addCC');

        return $this;
    }

    /**
     * Add content to email
     *
     * @param string $html
     * @param string $subject
     * @param string $body
     * @param string $altBody
     * @return object $this
     */
    public function content(string $html, string $subject, string $body, string $altBody)
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
