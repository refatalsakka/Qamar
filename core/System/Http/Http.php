<?php

namespace System\Http;

use System\Application;

class Http
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
     * Check if the website is secure
     *
     * @return bool
     */
    public function isSecure()
    {
        if ($this->checkHttp() || $this->checkHttpXforwardedProto() || $this->checkHttpXforwardedSsl()) {
            return true;
        }
        return false;
    }

    /**
     * Check if HTTPS is 'on'
     *
     * @return bool
     */
    private function checkHttp()
    {
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
            return true;
        }
        return false;
    }

    /**
     * Check if HTTP_X_FORWARDED_PROTO is not empty or 'https'
     *
     * @return bool
     */
    private function checkHttpXforwardedProto()
    {
        if (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
            return true;
        }
        return false;
    }

    /**
     * Check if HTTP_X_FORWARDED_SSL is 'on'
     *
     * @return bool
     */
    private function checkHttpXforwardedSsl()
    {
        if (!empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on') {
            return true;
        }
        return false;
    }
}
