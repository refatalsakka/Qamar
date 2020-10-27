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
     * Render the given path with the passed
     * variables and generate new view object for it
     *
     * @property object $url
     * @property object $file
     * @param string $path
     * @param array $context
     * @return string
     */
    public function render(string $path, array $context)
    {
        $pug = new Pug(array(
            'pretty' => true,
            'cache' => ($_ENV['APP_DEBUG'] == 'true') ? false : 'template' . DIRECTORY_SEPARATOR . 'cache',
            'basedir' => 'template',
            'upToDateCheck' => false,
        ));

        $host = $this->app->url->host();
        $dir = $this->app->file->root();
        $parameters = $this->parameters();

        $file = $this->filePath($path, $dir);

        $public = $this->_public($dir, $host);
        $page = strtolower($this->app->route->getPage());
        $title = $this->app->msg->$page('title');

        $context += [
            'lang' => $_ENV['APP_LANG'],
            'charset' => $_ENV['APP_CHARSET'],
            'decsription' => $_ENV['APP_DECSRIPTION'],
            'keywords' => $_ENV['APP_KEYWORDS'],
            'auth' => $_ENV['APP_AUTH'],
            'host' => $host,
            'parameters' => $parameters,
            '_public' => $public,
            'title' => $title,
            'page' => $page,
        ];
        return $pug->render($file, $context);
    }

    /**
     * Generate the path
     *
     * @param string $path
     * @param string $dir
     * @return string
     */
    private function filePath($path, $dir)
    {
        $file = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path);

        $file = $dir . DIRECTORY_SEPARATOR . 'template' . DIRECTORY_SEPARATOR . $file . '.pug';

        return $file;
    }

    /**
     * Generate link to public
     *
     * @param string $dir
     * @param array $host
     * @return string
     */
    private function _public($dir, $host)
    {
        $public = assets();

        $public = str_replace($dir, $host, $public);

        $public = str_replace('\\', '/', $public);

        if (substr($public, -1) !== '/') {
            $public = $public . '/';
        }
        return $public;
    }

    /**
     * Get the parameters from the url
     * loop over all and give it the right link for each one
     * e.g. if the current page is localhost/admin/users
     * the right link for localhost is localhost or just /
     * the right link for admin is localhost/admin
     * the right link for users is localhost/admin/users
     *
     * @property object $url
     * @return array
     */
    private function parameters()
    {
        $url = $this->app->url->parameters();

        if ($url === '/') {
            return;
        }
        $parameters = explode('/', $url);

        array_shift($parameters);

        $results = [];

        foreach ($parameters as $parameter) {
            $name = str_replace('-', ' ', $parameter);

            $length = strpos($url, $parameter) + strlen($parameter);

            $link = substr($url, 0, $length);

            $results[] = [
                'name' => $name,
                'link' => $link,
            ];
        }
        return $results;
    }
}
