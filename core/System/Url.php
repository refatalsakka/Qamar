<?php

namespace System;

class Url
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

        $this->url = cleanUrl($script, $requestUri);

        $this->host = $this->app->http->requestProtocol() . '://' . $this->app->request->server('HTTP_HOST');

        $this->baseUrl = $this->host . $requestUri;
    }

    /**
     * Get only relative url (clean url)
     *
     * @return string
     */
    public function get()
    {
        return $this->url;
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
     * Get only host
     *
     * @return string
     */
    public function host()
    {
        return $this->host;
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
