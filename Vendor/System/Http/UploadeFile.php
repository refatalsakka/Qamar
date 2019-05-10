<?php

namespace System\Http;

use System\Application;

class UploadeFile
{
    private $app;

    private $file = [];

    private $fileName;

    private $nameOnly;

    private $extension;

    private $minetype;

    private $tempFile;

    private $size;

    private $error;

    private const AllOW_EXTENSION = ['png', 'jpg', 'jpeg', 'gif', 'webp'];
   
    public function __construct(Application $app, $input)
    {
        $this->app = $app;

        $this->getFileInfo($input);
    }

    private function getFileInfo($input)
    {
        if (empty($_FILES[$input])) return;

        $file = $_FILES[$input];

        $this->error = $file['error'];
        
        if ($this->error != UPLOAD_ERR_OK) return;

        $this->file = $file;

        $this->name = $this->file['name'];

        $this->minetype = $this->file['type'];

        $this->size = $this->file['size'];

        $this->tempFile = $this->file['tmp_name'];

        $fileInfo = pathinfo($this->name);

        $this->nameOnly = $fileInfo['filename']; 

        $this->extension = $fileInfo['extension'];
    }

    public function exists()
    {
        return ! empty($this->file);
    }

    public function getFileName()
    {
        return $this->fileName;
    }

    public function getNameOnly()
    {
        return $this->nameOnly;
    }

    public function getExtension()
    {
        return $this->extension;
    }

    public function getMinetype()
    {
        return $this->minetype;
    }

    public function getSize()
    {
        return $this->size;
    }

    public function getTempFile()
    {
        return $this->tempFile;
    }

    public function isImage()
    {
        return strpos($this->minetype, 'image/') === 0 && in_array($this->extension, self::AllOW_EXTENSION);
    }
}