<?php

namespace App\Controllers\Website;

use System\Controller as Controller;

/**
 * Notfound Controller
 *
 * @property object $view
 */
class NotfoundController extends Controller
{
    public function index()
    {
        $this->response->setHeader("HTTP/1.0 404 Not Found");

        $ops = $this->msg->notfound('ops');
        $goHome = $this->msg->notfound('goHome');
        $quote = $this->pickQuote();

        $context = [
            'ops' => $ops,
            'goHome' => $goHome,
            'quote' => $quote,
        ];
        return $this->view->render('website/pages/notfound', $context);
    }

    private function pickQuote()
    {
        return $this->msg->notfound('quotes')[rand(0, 26)];
    }
}
