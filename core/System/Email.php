<?php

namespace System;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use System\Error;

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
   * @return void
   */
  private function setUp()
  {
    $this->mail->SMTPDebug = Error::allowDisplayingError() ? SMTP::DEBUG_SERVER : 0;
    $this->mail->isSMTP();
    $this->mail->Host = $_ENV['EMAIL_HOST'];
    $this->mail->SMTPAuth = $_ENV['EMAIL_SMTPAUTH'];
    $this->mail->Username = $_ENV['EMAIL_USERNAME'];
    $this->mail->Password = $_ENV['EMAIL_PASSWORD'];
    $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $this->mail->Port = $_ENV['EMAIL_PORT'];
  }

  /**
   * Add recipients to email
   *
   * @return $this
   */
  public function recipients($addresses, array $replayTo = [], $cc = null, $bcc = null)
  {
    $this->mail->setFrom($_ENV['EMAIL_ADMIN'], $_ENV['EMAIL_NAME']);

    $this->addAddresses($addresses);

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
   * @return void
   */
  private function addAddresses($addresses)
  {
    if (!is_array($addresses)) {
      $addresses = [$addresses];
    }

    foreach ($addresses as $key => $value) {
      if (is_numeric($key)) {

        $this->mail->addAddress($value);
      } else {

        $this->mail->addAddress($value, $key);
      }
    }
  }

  /**
   * Add attachments to email
   *
   * @return $this
   */
  public function attachments($attachments)
  {
    if (!is_array($attachments)) {
      $attachments = [$attachments];
    }

    foreach ($attachments as $key => $value) {
      if (is_numeric($key)) {

        $this->mail->addAttachment($value);
      } else {

        $this->mail->addAttachment($value, $key);
      }
    }

    return $this;
  }

  /**
   * Add content to email
   *
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
      // $this->mail->send();
    } catch (Exception $e) {
      echo "Message could not be sent. Mailer Error: {$this->mail->ErrorInfo}";
    }
  }
}
