<?php

namespace System\Http;

use System\Application;

class Request
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
     * Get value from spcefic request type
     *
     * @param array $requestType
     * @param string $key
     * @return mixed
     */
    private function getValueOfRequest($requestType, $key)
    {
        $value = array_get($requestType, $key);

        if (is_array($value)) {
            $value = array_filter($value);
        } else {
            $value = trim($value);
        }
        return $value;
    }

    /**
     * Get value from $_GET by the given key
     *
     * @param string $key
     * @return mixed
     */
    public function get(string $key = null)
    {
        if ($key !== null) {
            return $this->getValueOfRequest($_GET, $key);
        }
        return $_GET;
    }

    /**
     * Get value from $_POST by the given key
     *
     * @param string $key
     * @return mixed
     */
    public function post(string $key = null)
    {
        if ($key !== null) {
            return $this->getValueOfRequest($_POST, $key);
        }
        return $_POST;
    }

    /**
     * Get value from $_FILES by the given key
     *
     * @param string $key
     * @return mixed
     */
    public function file(string $key = null)
    {
        if ($key !== null) {
            return $this->getValueOfRequest($_FILES, $key);
        }
        return $_FILES;
    }

    /**
     * Get value from $_SERVER by the given key
     *
     * @param string $key
     * @return string
     */
    public function server(string $key)
    {
        if ($key !== null) {
            return $this->getValueOfRequest($_SERVER, $key);
        }
        return $_SERVER;
    }

    /**
     * Set value To $_POST For the given key
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function setPost(string $key, $value)
    {
        $_POST[$key] = $value;
    }

    /**
     * Set value To $_GET For the given key
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function setGet(string $key, $value)
    {
        $_GET[$key] = $value;
    }

    /**
     * Set value To $_FILES For the given key
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function setFile(string $key, $value)
    {
        $_FILES[$key] = $value;
    }

    /**
     * Set value To $_SERVER For the given key
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function setServer(string $key, $value)
    {
        $_SERVER[$key] = $value;
    }

    /**
     * Get current request method
     *
     * @return string
     */
    public function method()
    {
        return $this->server('REQUEST_METHOD');
    }

    /**
     * Check if the request to the admin panel
     *
     * @return bool
     */
    public function isRequestToAdminManagement()
    {
        $url = $this->app->url->parameters();
        ;
        $url = ltrim($url, '/');
        $url = explode('/', $url)[0];

        return $url == 'admin';
    }

    /**
     * Check the request method
     *
     * @param string|array $methods
     * @return bool
     */
    public function isMatchingRequestMethod($method)
    {
        if ($this->method() == strtoupper($method)) {
            return true;
        }
        return false;
    }

    /**
     * Check if the request can be Continued
     *
     * @property object $load
     * @param array $middlewares
     * @return bool
     */
    public function canRequestContinue(array $middlewares)
    {
        if (!empty($middlewares)) {
            foreach ($middlewares as $middleware) {
                $output = $this->app->load->middleware($middleware)->handle();

                if (!$output) {
                    return false;
                }
            }
        }
        return true;
    }
}
