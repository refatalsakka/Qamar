<?php

namespace System;

use Closure;
use Exception;
use Whoops\Run as Whoops;
use Whoops\Util\Misc as WhoopsMisc;
use Whoops\Handler\JsonResponseHandler;
use Whoops\Handler\PrettyPageHandler;

class Application
{
  /**
   * Container
   *
   * @var array
   */
  private $container = [];

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

    $this->handleErrors();

    $this->share('config', $this->file->call('config.php'));

    $this->share('alias', $this->file->call('config/alias.php'));

    $this->loadHelpers();
  }

  /**
   * Load helpers.php file
   *
   * @return void
   */
  private function loadHelpers()
  {
    $this->file->call('Core/helpers.php');
  }

  /**
   * Run error handling of Whoops
   *
   * @return void
   */
  private function handleErrors()
  {
    $run = new Whoops();

    $run->prependHandler(new PrettyPageHandler());

    if (WhoopsMisc::isAjaxRequest()) {
      $jsonHandler = new JsonResponseHandler();

      $jsonHandler->setJsonApi(true);

      $run->prependHandler($jsonHandler);
    }

    $run->register();
  }

  /**
   * Get Application instance
   *
   * @param \System\File $file
   * @return \System\Application
   */
  public static function getInstance($file = null)
  {
    return self::$instance = is_null(self::$instance) ? new static($file) : self::$instance;
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
   * Get all core classes
   *
   * @return array
   */
  public function coreClasses()
  {
    return $this->alias['classes'];
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
        throw new Exception("$key is not found");
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
    return isset($this->coreClasses()[$key]);
  }

  /**
   * Create new object for the core class based on the given key
   *
   * @param string $key
   * @return object
   */
  public function createObject($key)
  {
    $object = $this->coreClasses()[$key];
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
