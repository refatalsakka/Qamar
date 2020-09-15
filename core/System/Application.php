<?php

namespace System;

use Closure;
use Exception;
use Dotenv\Dotenv;

class Application
{
  /**
   * Container
   *
   * @var array
   */
  private $container = [];

  /**
   * Set and rename core classes
   *
   * @var array
   */
  private $coreClasses = [
    'request'   =>  'System\\Http\\Request',
    'response'  =>  'System\\Http\\Response',
    'route'     =>  'System\\Route',
    'session'   =>  'System\\Session',
    'cookie'    =>  'System\\Cookie',
    'load'      =>  'System\\Loader',
    'html'      =>  'System\\Html',
    'db'        =>  'System\\Database',
    'url'       =>  'System\\Url',
    'view'      =>  'System\\View',
    // 'hash'      =>  'System\\Hash',
    'error'     =>  'System\\Error',
    'email'     =>  'System\\Email'
  ];

  /**
   * Application Object
   *
   * @var \System\Application
   */
  private static $instance;

  /**
   * Constructor
   *
   * @param \System\File $file
   */
  private function __construct(File $file)
  {
    $this->share('file', $file);

    Dotenv::createImmutable($this->file->root())->load();

    $this->file->call('Core/helpers.php');

    $this->error->toggleError();

    register_shutdown_function([$this->error, 'handleErrors']);
  }

  /**
   * Get Application instance
   *
   * @param \System\File $file
   * @return \System\Application
   */
  public static function getInstance($file)
  {
    self::$instance = is_null(self::$instance) ? new static($file) : self::$instance;

    return self::$instance;
  }

  /**
   * Run the Application
   *
   * @return void
   */
  public function run()
  {
    $this->session->start();

    $this->request->prepareUrl();

    foreach (glob("routes/**/*.php") as $route) {

      $this->file->call($route);
    }

    $output = $this->route->getProperRoute();

    $this->response->setOutput($output);

    $this->response->send();
  }

  /**
   * Share the given key|value through Application
   *
   * @param string $key
   * @param mixed $value
   * @return void
   */
  public function share($key, $value)
  {
    if ($value instanceof Closure) {

      $value = call_user_func($value, $this);
    }

    $this->container[$key] = $value;
  }

  /**
   * Get shared value
   *
   * @param string $key
   * @return mixed
   */
  public function get($key)
  {
    if (!$this->isSharing($key)) {

      if ($this->isCoreAlias($key)) {

        $this->share($key, $this->createObject($key));

      } else {
        $found = false;
        $dirs = [];

        $direPath = 'core/System/';

        $directions = glob($direPath, GLOB_ONLYDIR);
        $dirs = array_merge($directions, $dirs);

        do {
          $direPath .= '**/';
          $directions = glob($direPath, GLOB_ONLYDIR);
          $dirs = array_merge($directions, $dirs);
        } while (!empty($directions));

        foreach ($dirs as $dir) {
          $path = $this->file->fullPath($dir . ucwords($key)) . '.php';

          if (file_exists($path)) {
            $found = true;

            $dir = $this->file->fullPath($dir . ucwords($key));
            $dir = ltrim($dir, $this->file->root() . 'core');

            $this->coreClasses[$key] = $dir;

            $this->share($key, $this->createObject($key));
          }
        }

        if (!$found) {
          throw new Exception("$key is not found");
        }
      }
    }

    return $this->container[$key];
  }

  /**
   * Determine if the given key is shared through Application
   *
   * @param string $key
   * @return bool
   */
  public function isSharing($key)
  {
    return isset($this->container[$key]);
  }

  /**
   * Determine if the given key is an alias to core class
   *
   * @param string $key
   * @return bool
   */
  public function isCoreAlias($key)
  {
    return isset($this->coreClasses[$key]);
  }

  /**
   * Create new object for the core class based on the given key
   *
   * @param string $key
   * @return object
   */
  public function createObject($key)
  {
    $object = $this->coreClasses[$key];

    return new $object($this);
  }

  /**
   * Get shared value dynamically
   *
   * @param string $key
   * @return mixed
   */
  public function __get($key)
  {
    return $this->get($key);
  }
}
