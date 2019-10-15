<?php

namespace System;

use Pug\Pug as Pug;

class View
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
   * Render the given view path with the passed variables and generate new View Object for it
   *
   * @param string $viewPath
   * @param array $context
   * @return mixed
   */
  public function render($path, array $context)
  {
    $pug = new Pug(array(
      'pretty' => true,
      'cache' => 'template' . DS . 'cache',
      'basedir' => 'template',
      'upToDateCheck' => false,
    ));

    $host = $this->app->request->host();
    $dir = $this->app->file->root();

    $parameters = $this->parameters();

    //File path
    $file = $this->filePath($path, $dir);

    //Public
    $_public = $this->_public($dir, $host);

    //Website ages
    $pages = $this->websitePages();

    $context += $this->app->config['website'];

    $context += [
      'host'  => $host,
      'parameters' => $parameters,
      '_public' => $_public,
      'pages' => $pages,
      'ds'    => DS,
    ];

    return $pug->render($file, $context);
  }

  /**
   * Get the website pages, that should be
   * showen in the asaide of the admin panel
   *
   * @return array
   */
  private function websitePages()
  {
    return $this->app->pages->getPages();
  }

  /**
   * Set the right DIRECTORY_SEPARATOR
   * Generate the path
   *
   * @param string $path
   * @param array $dir
   * @return string
   */
  private function filePath($path, $dir)
  {
    $file = str_replace(['/', '\\'], DS, $path);
    $file = $dir . DS . 'template' . DS . $file . '.pug';

    return $file;
  }

  private function _public($dir, $host)
  {
    $public = $this->app->file->toPublic();
    $public = str_replace($dir, $host, $public);
    $public = str_replace(['\\', '/'], DS, $public);

    return $public;
  }

  private function parameters()
  {
    $url =  $this->app->request->url();

    if ($url === '/') {return;}

    $parameters =  explode('/', $url);
    array_shift($parameters);
    $return = [];

    foreach ($parameters as $parameter) {
      $name = $parameter;
      $length =  strpos($url, $parameter) + strlen($parameter);
      $link =  substr($url, 0, $length);

      $return[] = [
        'name' => $name,
        'link' => $link,
      ];
    }

    return $return;
  }
}
