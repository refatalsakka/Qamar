<?php

namespace System\View;

use System\Application;

class View implements ViewInterface
{
    private $app;

    private $viewPath;

    private $data = [];

    private $output;
    
    public function __construct(Application $app, $viewPath, array $data) {
        
        $this->app = $app;

        $this->preparePath($viewPath);

        $this->data = $data;
    }
    
    private function preparePath($viewPath) {

        
        $trace = debug_backtrace()[4]['object'];
    
        $class = get_class(array ($trace)[0]);

        $dir = strpos($class, 'Website') != false ? 'Website\\' : 'Admin\\';

        $sub_dir = '';
        
        $this->viewPath = $this->app->file->to('App\\View\\' . $dir . $sub_dir . $viewPath, '.php');
        
        if (! $this->viewfileExists($this->viewPath)) {
            echo $this->viewPath . ' does not exist';
        }
    }

    private function viewfileExists($viewPath) {

        return $this->app->file->exists($viewPath);
    }
    
    public function getOutput() {

        if (is_null($this->output)) {

            ob_start();
            
            extract($this->data);

            require $this->viewPath;
            
            $this->output = ob_get_clean();
        }
        
        return $this->output;
    }

    public function __toString() {
        return $this->getOutput();
    }
}