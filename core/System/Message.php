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

    public function __call($name, $arguments)
    {
        $lang = $this->app->lang->get();

        $text = $this->app->file->call('resources/lang/' . $lang . '/' . $name . '.php')[$arguments[0]];
        $edit = $arguments[1];

        return $this->editMsg($text, $edit);
    }

    private function editMsg($text, array $edit)
    {
        foreach ($edit as $key => $value) {
            $text = str_replace($key, $value, $text);
        }

        return $text;
    }
}
