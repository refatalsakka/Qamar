<?php

namespace App\Controllers\Website;

use System\Controller as Controller;

class NotfoundController extends Controller
{
    /**
     * Notfound
     *
     * @property object $view
     */
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

    /**
     * Pick a random Quote
     *
     * @property object $msg
     */
    private function pickQuote()
    {
        return $this->msg->notfound('quotes')[rand(0, 26)];
    }
}
