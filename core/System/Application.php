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
    private $classes = [];

    /**
     * Application Object
     *
     * @var \System\Application
     */
    private static $instance;

    /**
     * Constructor
     *
     * @property object $file
     * @property object $error
     * @param \System\File $file
     */
    private function __construct(File $file)
    {
        $this->share('file', $file);

        $this->classes = $this->file->call('config/classes.php');

        Dotenv::createImmutable($this->file->root())->load();

        $this->file->call('Core/helpers.php');

        $this->error->start();

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
     * @property object $session
     * @property object $request
     * @property object $file
     * @property object $route
     * @property object $response
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
     * After getting all the folders and sub-folders, it will loop over all of them
     * is the class exists: it will process the name and create an object and add it to the $container
     * is the class not exists: it will throw an Exception
     *
     * @property object $file
     * @param string $key
     * @return void
     */
    private function searchForClass($key)
    {
        $found = false;
        $dirs = getAllSubDires('core/System/');

        foreach ($dirs as $dir) {
            $path = $this->file->fullPath($dir . ucwords($key)) . '.php';

            if ($this->file->exists($path)) {
                $found = true;

                $dir = $this->file->fullPath($dir . ucwords($key));
                $dir = ltrim($dir, $this->file->root() . 'core');

                $this->classes[$key] = $dir;

                $this->share($key, $this->createObject($key));
            }
        }

        if (!$found) {
            throw new Exception("$key is not found");
        }
    }

    /**
     * Get shared value
     * When the key exists in the $classes, it will look if it was sharing before
     * is not sharing: it will create in an object and add it to the $container
     * is sharing:  it will grab it direct from the $container
     *
     * When the key is not exists in the core $classes, the @method searchForClass will be called
     *
     * @param string $key
     * @return mixed
     */
    public function get($key)
    {
        if (!$this->isSharing($key)) {
            if ($this->isClassAliasIsset($key)) {
                $this->share($key, $this->createObject($key));
            } else {
                $this->searchForClass($key);
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
    public function isClassAliasIsset($key)
    {
        return isset($this->classes[$key]);
    }

    /**
     * Create new object for the core class based on the given key
     *
     * @param string $key
     * @return object
     */
    public function createObject($key)
    {
        $object = $this->classes[$key];

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
