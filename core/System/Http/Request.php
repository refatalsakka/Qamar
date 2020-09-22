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
     * Url
     *
     * @var string
     */
    private $url;

    /**
     * Base Url
     *
     * @var string
     */
    private $baseUrl;

    /**
     * Host
     *
     * @var string
     */
    private $host;

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
     * Prepare url
     *
     * @property object $http
     * @return void
     */
    public function prepareUrl()
    {
        $script = dirname($this->server('SCRIPT_NAME'));

        $requestUri = $this->server('REQUEST_URI');

        if (strpos($requestUri, '?')) {
            list($requestUri) = explode('?', $requestUri);
        }

        $this->url = cleanUrl($script, $requestUri);

        $this->host = $this->app->http->requestProtocol() . '://' . $this->server('HTTP_HOST');

        $this->baseUrl = $this->host . $requestUri;
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
     * Get value from $_SERVER by the given key
     *
     * @param string $key
     * @return string
     */
    public function server(string $key)
    {
        return array_get($_SERVER, $key);
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
     * Get the referer link
     *
     * @return string
     */
    public function referer()
    {
        return $this->server('HTTP_REFERER');
    }

    /**
     * Get full url of the script
     *
     * @return string
     */
    public function baseUrl()
    {
        return $this->baseUrl;
    }

    /**
     * Get only relative url (clean url)
     *
     * @return string
     */
    public function url()
    {
        return $this->url;
    }

    /**
     * Get only host
     *
     * @return string
     */
    public function host()
    {
        return $this->host;
    }

    /**
     * Check if the request to the admin panel
     *
     * @return bool
     */
    public function isRequestToAdminManagement()
    {
        $url = $this->url;
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
    public function isMatchingRequestMethod($methods = ['GET'])
    {
        if (!is_array($methods)) {
            $methods = [$methods];
        }

        if (empty($methods)) {
            $methods = ['GET'];
        }

        foreach ($methods as $method) {
            if ($this->method() == strtoupper($method)) {
                return true;
            }
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
        if (empty($middlewares)) {
            return true;
        }

        foreach ($middlewares as $middleware) {
            $output = $this->app->load->middleware($middleware)->handle();

            if (!$output) {
                return false;
            }
        }
        return true;
    }
}
