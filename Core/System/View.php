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

    //Css path
    $css = $this->stylesPath($dir, $host);

    //Js path
    $js = $this->scriptsPath($dir, $host);

    //Imgs path
    $img = $this->imagesPath($dir, $host);

    //Website ages
    $pages = $this->websitePages();

    $context += $this->app->config['website'];

    $context += [
      'host'  => $host,
      'parameters' => $parameters,
      'css'   => $css,
      'js'    => $js,
      'img'   => $img,
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

  /**
   * Get the path of js file
   * The path will be replaced with the absolute host
   * @param string $dir
   * @param array $host
   * @return string
   */
  private function scriptsPath($dir, $host)
  {
    $js = $this->app->file->js();
    $js = str_replace($dir, $host, $js);
    $js = str_replace(['\\', '/'], DS, $js);

    return $js;
  }

  /**
   * Get the path of css file
   * The path will be replaced with the absolute host
   * @param string $dir
   * @param array $host
   * @return string
   */
  private function stylesPath($dir, $host)
  {
    $css = $this->app->file->css();
    $css = str_replace($dir, $host, $css);
    $css = str_replace(['\\', '/'], DS, $css);

    return $css;
  }

  /**
   * Get the path of img file
   * The path will be replaced with the absolute host
   * @param string $dir
   * @param array $host
   * @return string
   */
  private function imagesPath($dir, $host)
  {
    $img = $this->app->file->img();
    $img = str_replace($dir, $host, $img);
    $img = str_replace(['\\', '/'], DS, $img);

    return $img;
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
