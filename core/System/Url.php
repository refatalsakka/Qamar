<?php

namespace System;

class Url
{
    /**
     * Protocol
     *
     * @var string
     */
    private $protocol;

    /**
     * Host
     *
     * @var string
     */
    private $host;

    /**
     * Parameters
     *
     * @var string
     */
    private $parameters;

    /**
     * Base Url
     *
     * @var string
     */
    private $baseUrl;

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

        $this->prepare();
    }

    /**
     * Prepare url
     *
     * @property object $request
     * @property object $http
     * @return void
     */
    private function prepare()
    {
        $script = dirname($this->app->request->server('SCRIPT_NAME'));

        $requestUri = $this->app->request->server('REQUEST_URI');

        if (strpos($requestUri, '?')) {
            list($requestUri) = explode('?', $requestUri);
        }

        $this->protocol = $this->app->http->requestProtocol();

        $this->rootDomain = $this->app->request->server('HTTP_HOST');

        $this->host = $this->protocol . '://' . $this->rootDomain;

        $this->parameters = cleanUrl($script, $requestUri);

        $this->baseUrl = $this->host . $this->parameters;
    }

    /**
     * Get Protocol
     *
     * @return string
     */
    public function protocol()
    {
        return $this->protocol;
    }

    /**
     * Get Root domain
     *
     * @return string
     */
    public function rootDomain()
    {
        return $this->rootDomain;
    }

    /**
     * Get parameters
     *
     * @return string
     */
    public function parameters()
    {
        return $this->parameters;
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
     * Get full url of the script
     *
     * @return string
     */
    public function baseUrl()
    {
        return $this->baseUrl;
    }

    /**
     * Generate full link for the given path
     *
     * @param string $path
     * @return string
     */
    public function link(string $path)
    {
        $link = $this->host();

        $path = rtrim($path, '/');
        $path = ltrim($path, '/');

        return $link . '/' . $path;
    }

    /**
     * Redirect to the given path
     *
     * @param string $path
     * @param int $num
     * @return void
     */
    public function redirectTo(string $path, int $num = 0)
    {
        header('Refresh: ' . $num . '; URL=' . $this->link($path));
        exit;
    }

    /**
     * Redirect to the previous page
     *
     * @return void
     */
    public function redirectToPreviousPage()
    {
        header('Refresh: ' . $this->request->referer());
        exit;
    }

    /**
     * Redirect to the 404 page
     *
     * @property object $request
     * @property object $load
     * @param string $path
     * @return void
     */
    public function notfound(string $path = '')
    {
        if (!$path) {
            $path = '/404';

            if ($this->app->request->isRequestToAdminManagement() && $this->app->load->model('User')->isAdmin()) {
                $path = 'admin/404';
            }
        }
        header('Refresh: 0; URL=' . $this->link($path));
        exit;
    }
}
