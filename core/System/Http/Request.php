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
   * Uploaded files container
   *
   * @var array
   */
  private $files = [];

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
   * @return void
   */
  public function prepareUrl()
  {
    $script = dirname($this->server('SCRIPT_NAME'));

    $requestUri = $this->server('REQUEST_URI');

    if (strpos($requestUri, '?')) {

      list($requestUri, $queryString) = explode('?', $requestUri);
    }

    $this->url = $this->cleanUrl($script, $requestUri);

    $REQUEST_PROTOCOL = $this->isSecure() ? 'https' : 'http';

    $this->host = $REQUEST_PROTOCOL . '://' . $this->server('HTTP_HOST');

    $this->baseUrl = $this->host . $requestUri;
  }

  /**
   * Clean url
   *
   * @param string $script
   * @param string $default
   * @return string
   */
  private function cleanUrl($script, $requestUri)
  {
    if (!in_array($script, ['/', '\\'])) {

      $url = preg_replace('#^' . $script . '#', '', $requestUri);

    } else {

      $url = $requestUri;
    }

    if ($url !== '/') {

      $url = rtrim($url, '/');
    }

    return $url;
  }

  /**
   * Check if the website is secure
   *
   * @return bool
   */
  private function isSecure()
  {
    if ($this->checkHttp() || $this->checkHttpXforwardedProto() || $this->checkHttpXforwardedSsl()) {

      return true;
    }

    return false;
  }

  /**
   * Check if HTTPS is 'on'
   *
   * @return bool
   */
  private function checkHttp()
  {
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {

      return true;
    }

    return false;
  }

  /**
   * Check if HTTP_X_FORWARDED_PROTO is not empty or 'https'
   *
   * @return bool
   */
  private function checkHttpXforwardedProto()
  {
    if (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {

      return true;
    }

    return false;
  }

  /**
   * Check if HTTP_X_FORWARDED_SSL is 'on'
   *
   * @return bool
   */
  private function checkHttpXforwardedSsl()
  {
    if (!empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on') {

      return true;
    }

    return false;
  }

  /**
   * Get value from $_GET by the given key
   *
   * @param string $key
   * @return mixed
   */
  public function get($key)
  {
    $value = array_get($_GET, $key);

    if (is_array($value)) {

      $value = array_filter($value);

    } else {

      $value = trim($value);
    }

    return $value;
  }

  /**
   * Get value from $_POST by the given key
   *
   * @param string $key
   * @return mixed
   */
  public function post($key)
  {
    $value = array_get($_POST, $key);

    if (is_array($value)) {

      $value = array_filter($value);

    } else {

      $value = trim($value);
    }

    return $value;
  }

  /**
   * Set value To $_POST For the given key
   *
   * @param string $key
   * @param mixed $valuet
   * @return mixed
   */
  public function setPost($key, $value)
  {
    $_POST[$key] = $value;
  }

  /**
   * Get $_POST
   *
   * @return array
   */
  public function posts()
  {
    return $_POST;
  }

  /**
   * Get $_GET
   *
   * @return array
   */
  public function gets()
  {
    return $_GET;
  }

  /**
   * Get $_FILES
   *
   * @return array
   */
  public function files()
  {
    return $_FILES;
  }

  /**
   * Get the uploaded file object for the given input
   *
   * @param string $input
   * @return System\Http\UploadeFile\
   */
  public function file($input)
  {
    if (isset($this->files[$input])) {

      return $this->files[$input];
    }

    $upoadedFile = new UploadeFile($this->app, $input);

    $this->files[$input] = $upoadedFile;

    return $this->files[$input];
  }

  /**
   * Get value from $_SERVER by the given key
   *
   * @param string $key
   * @return mixed
   */
  public function server($key)
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


  public function canRequestContinue($middlewares)
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
