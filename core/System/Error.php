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
     * Check if the errors should be displayed
     *
     * @return bool
     */
    public function allowDisplayingError()
    {
        return (bool) ($_ENV['APP_ENV'] == 'local' && $_ENV['APP_DEBUG'] == 'true');
    }

    /**
     * Show error
     *
     * @return void
     */
    private function showError()
    {
        error_reporting(E_ALL);

        ini_set("display_errors", 1);
    }

    /**
     * Hide error
     *
     * @return void
     */
    private function hideError()
    {
        error_reporting(0);

        ini_set('display_errors', 0);
    }

    /**
     * Show or hide errors depend on the condition
     */
    public function toggleError()
    {
        if ($this->allowDisplayingError()) {
            $this->showError();

            $this->whoops();

            return;
        }
        return $this->hideError();
    }

    /**
     * Send Email to the admin contain the Error
     * and the date
     *
     * @property object $email
     * @param string $error
     * @return void
     */
    private function sendErrorToAdmin($error)
    {
        $date = new DateTime('now', new DateTimeZone('Europe/Berlin'));
        $date = 'Error: ' . $date->format('d.m.Y H:i:s');

        $this->app->email->recipients(['admin' => $_ENV['EMAIL_ADMIN']])->content(true, $date, $error, $error)->send();
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

    /**
     * Display friendly error to the users
     *
     * @property object $view
     * @return void
     */
    private function displayFriendlyMessage()
    {
        echo $this->app->view->render('website/pages/error', []);
    }

    /**
     * Check for last errors
     * if there are errors than send continue to report the admin
     * and display a friendly error to the users
     *
     * @return void
     */
    public function handleErrors()
    {
        $error = error_get_last() || null;

        if (!$error) {
            return;
        }

        $type = $error['type'];
        $message = $error['message'];
        $file = $error['file'];
        $line = $error['line'];

        $error = "There is an Error type: {$type}. says: $message. in file: $file. on line: $line.";

        $this->sendErrorToAdmin($error);

        $this->displayFriendlyMessage();
    }
}
