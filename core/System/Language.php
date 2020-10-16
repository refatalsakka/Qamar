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
     * @return string $language
     */
    public function get()
    {
        $language = $this->app->cookie->get('language');

        if (!$language || !is_allow('languages', $language)) {
            $language = $_ENV['APP_LANG'];
        }

        return $language;
    }

    /**
     * Set language in cookies
     *
     * @return string $lang
     */
    public function set($value)
    {
        $this->app->cookie->set('language', $value);
    }
}
