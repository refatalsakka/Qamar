<?php

namespace System;

class Message
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
     * Get the right array from config/allow.php
     *
     * @property string $key
     * @param array $arguments
     *
     * @return method editMsg()
     */
    public function __call($key, $arguments)
    {
        $language = $this->app->lang->get();

        $text = $this->app->file->call('resources/languages/' . $language . '/' . $key . '.php')[$arguments[0]];
        $edit = $arguments[1] ?? [];

        return $this->editMsg($text, $edit);
    }

    /**
     * Replace the key to the value in the given array $edit
     *
     * @property string $text
     * @param array $edit
     *
     * @return string $text
     */
    private function editMsg($text, array $edit)
    {
        if (!empty($edit)) {
            foreach ($edit as $key => $value) {
                $text = str_replace($key, $value, $text);
            }
        }

        return $text;
    }
}
