<?php

namespace System;

class Pages
{
  private $app;

  private $pages;

  private $title;

  private $icon;

  public function __construct(Application $app)
  {
    $this->app = $app;
  }

  public function add($name, $link, $icon = null)
  {

    if (!$this->title) {

      if (!$icon) {

        $icon = 'icon-star';
      }

      $this->pages[] = [
        'name' => $name,
        'link' => $link,
        'icon' => $icon
      ];

    } else {

      if (!$icon) {

        $icon = $this->icon;
      }

      $page = [
        'name' => $name,
        'link' => $link,
        'icon' => $icon
      ];

      array_push($this->pages[$this->title]['linkedPages'], $page);
    }
  }

  public function group($options, $callback)
  {
    $this->title = $options['title'];

    if (!$options['icon']) {

      $options['icon'] = 'icon-star';
    }

    $this->icon = $options['icon'];

    $this->pages[$this->title] = [
      'title'  => $options['title'],
      'icon'  => $options['icon'],
      'linkedPages' => []
    ];

    $callback($this);

    $this->cleanTitle();
  }

  private function cleanTitle()
  {
    $this->title = null;
  }

  public function getPages()
  {
    return $this->pages;
  }
}
