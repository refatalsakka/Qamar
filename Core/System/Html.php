<?php

namespace System;

class Html
{
    protected $app;

    private $title;

    private $description;

    private $keywords;

    private $css = [];

    private $js = [];

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setKeywords($keywords)
    {
        $this->keywords = $keywords;
    }

    public function getKeywords()
    {
        return $this->keywords;
    }

    public function setCss($css)
    {
        $this->css = $css;
    }

    public function getCss()
    {
        return $this->css;
    }

    public function setJs($js)
    {
        $this->js = $js;
    }

    public function getJs()
    {
        return $this->js;
    }
}