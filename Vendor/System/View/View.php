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

        $this->viewPath = $this->app->file->to('App\\View\\' . $viewPath, '.php');
        
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