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
   * Uploaded Files Container
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
   * Check if the website on secure host
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
   * Get Value from _GET by the given key
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
   * Get Value from _POST by the given key
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
   * Set Value To _POST For the given key
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
   * Get _POST
   *
   * @return array
   */
  public function posts()
  {
    return $_POST;
  }

  /**
   * Get _GET
   *
   * @return array
   */
  public function gets()
  {
    return $_GET;
  }

  /**
   * Get Files
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
   * @return \System\Http\UploadedFile
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
   * Get Value from _SERVER by the given key
   *
   * @param string $key
   * @return mixed
   */
  public function server($key)
  {
    return array_get($_SERVER, $key);
  }

  /**
   * Get Current Request Method
   *
   * @return string
   */
  public function method()
  {
    return $this->server('REQUEST_METHOD');
  }

  /**
   * Get The referer link
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
   * Get Only relative url (clean url)
   *
   * @return string
   */
  public function url()
  {
    return $this->url;
  }

  /**
   * Get Only host
   *
   * @return string
   */
  public function host()
  {
    return $this->host;
  }

  public function isRequestToAdminManagement()
  {
    $url = $this->url;

    $url = ltrim($url, '/');

    $url = explode('/', $url)[0];

    return $url == 'admin';
  }
}
