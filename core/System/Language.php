<?php

namespace System;

class Language
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
     * Get the language from cookies
     * if not exists get it from .ENV
     *
     * @return string $lang
     */
    public function get()
    {
        $lang = $this->app->cookie->get('lang');

        if (!$lang || !is_allow('languages', $lang)) {
            $lang = $_ENV['APP_LANG'];
        }

        return $lang;
    }

    /**
     * Set language in cookies
     *
     * @return string $lang
     */
    public function set($value)
    {
        $this->app->cookie->set('lang', $value, 1000000);
    }
}
