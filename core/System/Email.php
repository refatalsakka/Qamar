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

  private function setUp()
  {
    // $this->mail->SMTPDebug = Error::allowDisplayingError() ? SMTP::DEBUG_SERVER : 0;
    // $this->mail->isSMTP();
    // $this->mail->Host = $_ENV['EMAIL_HOST'];
    // $this->mail->SMTPAuth = $_ENV['EMAIL_SMTPAUTH'];
    // $this->mail->Username = $_ENV['EMAIL_USERNAME'];
    // $this->mail->Password = $_ENV['EMAIL_PASSWORD'];
    // $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    // $this->mail->Port = $_ENV['EMAIL_PORT'];

    $mail = new PHPMailer(true);

    try {
      //Server settings
      $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
      $mail->isSMTP();                                            // Send using SMTP
      $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
      $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
      $mail->Username   = 'refat838@gmail.com';                     // SMTP username
      $mail->Password   = '8?W5?ENzd@qA9M$x8?W5?ENzd@qA9M$x';                               // SMTP password
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
      $mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

      //Recipients
      $mail->setFrom('refat838@gmail.com', 'Mailer');
      $mail->addAddress('refatalsakka@gmail.com', 'Joe User');     // Add a recipient

      // Content
      $mail->isHTML(true);                                  // Set email format to HTML
      $mail->Subject = 'Here is the subject';
      $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
      $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

      $mail->send();
      echo 'Message has been sent';
  } catch (Exception $e) {
      echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
  }
  }

  public function recipients($addresses, array $replayTo = [], $cc = null, $bcc = null)
  {
    $this->mail->setFrom($_ENV['EMAIL_ADMIN'], $_ENV['EMAIL_NAME']);

    if (!is_array($addresses)) $addresses = [$addresses];

    foreach ($addresses as $key => $value) {
      if (is_numeric($key)) {

        $this->mail->addAddress($value);
      } else {

        $this->mail->addAddress($value, $key);
      }
    }

    if (!empty($replayTo)) $this->mail->addReplyTo(array_values($replayTo)[0], array_keys($replayTo)[0]);

    if ($cc) $this->mail->addCC($cc);

    if ($bcc) $this->mail->addBCC($bcc);

    return $this;
  }

  public function attachments($attachments)
  {
    if (!is_array($attachments)) $attachments = [$attachments];

    foreach ($attachments as $key => $value) {
      if (is_numeric($key)) {

        $this->mail->addAttachment($value);
      } else {

        $this->mail->addAttachment($value, $key);
      }
    }

    return $this;
  }

  public function content($html, $subject, $body, $altBody)
  {
    $this->mail->isHTML($html);
    $this->mail->Subject = $subject;
    $this->mail->Body = $body;
    $this->mail->AltBody = $altBody;

    return $this;
  }

  public function send()
  {
    try {
      $this->mail->send();
    } catch (Exception $e) {
      echo "Message could not be sent. Mailer Error: {$this->mail->ErrorInfo}";
    }
  }
}
