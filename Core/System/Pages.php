<?php

namespace System;

class Pages
{
  /**
   * Pages Container
   *
   * @var array
   */
  private $pages = [];

  /**
   * Title
   *
   * @var string
   */
  private $title;

  /**
   * Icon
   *
   * @var string
   */
  private $icon;

  /**
   * Add $name, $link and $icon to $pages
   * when the @method add beeing called in @method group then the @var $ttitle must be defined
   * so the pages will be put it in the linkedPages of @var $pages
   *
   * @param string $name
   * @param string $link
   * @param string $icon
   * @return void
   */
  public function add($name, $link, $icon = null)
  {
    if (!$this->title) {
      if (!$icon) $icon = 'icon-star';

      $this->pages[] = [
        'name' => $name,
        'link' => $link,
        'icon' => $icon
      ];
    } else {
      if (!$icon) $icon = $this->icon;

      $page = [
        'name' => $name,
        'link' => $link,
        'icon' => $icon
      ];
      array_push($this->pages[$this->title]['linkedPages'], $page);
    }
  }

  /**
   * Set the $title and $icon from the given $options
   * $callback will add the pages in the linkedPages
   * Call the @method cleanTitle to void overriding
   *
   * @param array $options
   * @param callback $callback
   * @return void
   */
  public function group($options, $callback)
  {
    $this->title = $options['title'];

    if (!$options['icon']) $options['icon'] = 'icon-star';

    $this->icon = $options['icon'];

    $this->pages[$this->title] = [
      'title'  => $options['title'],
      'icon'  => $options['icon'],
      'linkedPages' => []
    ];

    $callback($this);

    $this->cleanTitle();
  }


  /**
   * Set null to $title
   *
   * @return void
   */
  private function cleanTitle()
  {
    $this->title = null;
  }

  /**
   * Return the $pages
   *
   * @return array
   */
  public function getPages()
  {
    return $this->pages;
  }
}
