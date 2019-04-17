<?php

namespace System;

use PDO;
use PDOException;

class Database
{
    private $app;

    private static $connection;

    public function __construct(Application $app)
    {
        $this->app = $app;

        if (! $this->isConnected()) {
            $this->connect();
        }
    }

    private function  isConnected()
    {
        return static::$connection instanceof PDO;
    }

    private function connect()
    {
        $data = $this->app->file->call($this->app->file->to('config', '.php'));

        extract($data);
        
        try {
            static::$connection  = new PDO('mysql:host=' . $server . ';dbname=' . $dbname, $dbuser, $dbpass);

        } catch (PDOException $e) {

            die($e->getMessage());
        }
    }

    public function connection()
    {
        return static::$connection;
    }
}