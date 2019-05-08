<?php

namespace System;

abstract class Model
{
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function __get($key)
    {
        return $this->app->get($key);    
    }

    public function __call($method, $args)
    {
        return call_user_func_array([$this->app->db, $method], $args);
    }

    private function cleanName($model)
    {
        $model =  getLastIndex($model);

        $model = rtrim($model, 'Model');

        $model = strtolower($model);

        $model = (substr($model, -1) == 'y') ? $model . 'ies' : $model . 's';

        return $model;
    }

    public function hasOne($join)
    {
        $join = rtrim($join, 'Model');

        $join .= 'Model';

        $file = $this->app->file->to($join, '.php');
       
        $exists = $this->app->file->exists($file);
     
        if (! $exists) return $join . ' Not Found';
        
        $trace = debug_backtrace();

        $table = $trace[1]['class'];

        $table = $this->cleanName($table);
        
        $join = $this->cleanName($join);
    
        return $this->db->select()->from($table)->join($join)->fetch();
    }

    public function hasMany($join)
    {
        $join = rtrim($join, 'Model');

        $join .= 'Model';

        $file = $this->app->file->to($join, '.php');
       
        $exists = $this->app->file->exists($file);
     
        if (! $exists) return $join . ' Not Found';
        
        $trace = debug_backtrace();

        $table = $trace[1]['class'];

        $table = $this->cleanName($table);
        
        $join = $this->cleanName($join);
    
        return $this->db->select()->from($table)->join($join);
    }
}