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
   * Render the given path with the passed
   * variables and generate new view object for it
   *
   * @param string $path
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

    $context += $this->app->file->call('config/about.php')['website'];

    $context += [
      'host'  => $host,
      'parameters' => $parameters,
      '_public' => $_public,
    ];

    return $pug->render($file, $context);
  }

  /**
   * Generate the path
   *
   * @param string $path
   * @param string $dir
   * @return string
   */
  private function filePath($path, $dir)
  {
    $file = str_replace(['/', '\\'], DS, $path);

    $file = $dir . DS . 'template' . DS . $file . '.pug';

    return $file;
  }

  /**
   * Generate the url to public
   *
   * @param string $dir
   * @param array $host
   * @return string
   */
  private function _public($dir, $host)
  {
    $public = assets();

    $public = str_replace($dir, $host, $public);

    $public = str_replace('\\', '/', $public);

    if (substr($public, -1) !== '/') {
      $public = $public . '/';
    }

    return $public;
  }

  /**
   * Get the parameters from the url
   * loop over all and give it the right link for each one
   * e.g. if the current page is localhost/admin/users
   * the right link for localhost is localhost or just /
   * the right link for admin is localhost/admin
   * the right link for users is localhost/admin/users
   *
   * @return array
   */
  private function parameters()
  {
    $url = $this->app->request->url();

    if ($url === '/') {
      return;
    }

    $parameters = explode('/', $url);

    array_shift($parameters);

    $results = [];

    foreach ($parameters as $parameter) {
      $name = str_replace('-', ' ', $parameter);

      $length = strpos($url, $parameter) + strlen($parameter);

      $link = substr($url, 0, $length);

      $results[] = [
        'name' => $name,
        'link' => $link,
      ];
    }

    return $results;
  }
}
