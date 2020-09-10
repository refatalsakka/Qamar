<?php

namespace System;

use Whoops\Run as Whoops;
use Whoops\Util\Misc as WhoopsMisc;
use Whoops\Handler\JsonResponseHandler;
use Whoops\Handler\PrettyPageHandler;
use DateTime;
use DateTimeZone;

class Error
{
  /**
   * Application Object
   *
   * @var \System\Application
   */
  private $app;

  /**
   * Constructor
   *
   * @param \System\Application $app
   */
  public function __construct(Application $app)
  {
    $this->app = $app;
  }

  /**
   * Check if the Errors should be displayed or not
   *
   * @return void
   */
  public static function allowDisplayingError()
  {
    return (bool) ($_ENV['APP_ENV'] == 'local' && $_ENV['APP_DEBUG'] == 'true');
  }

  /**
   * Show Error
   *
   * @return void
   */
  private function showError()
  {
    error_reporting(E_ALL);

    ini_set("display_errors", 1);
  }

  /**
   * Hide Error
   *
   * @return void
   */
  private function hideError()
  {
    error_reporting(0);

    ini_set('display_errors', 0);
  }

  /**
   *
   */
  public function toggleError()
  {
    if (Error::allowDisplayingError()) {

      $this->showError();

      $this->whoops();

      return;
    }

    return $this->hideError();
  }

  /**
   * Send Email to the admin contain the Error
   *
   * @return void
   */
  private function sendErrorToAdmin($error)
  {
    $date = new DateTime('now', new DateTimeZone('Europe/Berlin'));
    $date = $date->format('d.m.Y H:i:s');

    $this->app->email->recipients(['amin' => 'refat838@gmail.com'], ['refat' => 'refatalsakka@gmail.com'])->content(true, 'Error', 'test','test')->send();
  }

  /**
   * Run error handling of Whoops
   *
   * @return void
   */
  private function whoops()
  {
    $run = new Whoops();

    $run->prependHandler(new PrettyPageHandler());

    if (WhoopsMisc::isAjaxRequest()) {

      $jsonHandler = new JsonResponseHandler();

      $jsonHandler->setJsonApi(true);

      $run->prependHandler($jsonHandler);
    }

    $run->register();
  }

  private function displayFriendlyMessage()
  {
    echo $this->app->view->render('website/pages/error', []);
  }

  /**
   * Check the environment
   * if lcaol run the Whoops
   * else send email to the admin contain the error
   *
   * @return void
   */
  public function handleErrors()
  {
    $error = error_get_last();

    if (!$error) return;

    $type = $error['type'];
    $message = $error['message'];
    $file = $error['file'];
    $line = $error['line'];

    $error = "There is an Error type: {$type}. says: $message. in file: $file. on line: $line.";

    $this->sendErrorToAdmin($error);

    $this->displayFriendlyMessage();
  }
}
