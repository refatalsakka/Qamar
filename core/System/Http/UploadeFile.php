<?php

namespace System\Http;

use System\Application;

class UploadeFile
{
    /**
     * Application Object
     *
     * @var \System\Application
     */
    private $app;

    /**
     * File
     *
     * @var array
     */
    private $file = [];

    /**
     * File Name
     *
     * @var string
     */
    private $fileName;

    /**
     * File Name
     *
     * @var string
     */
    private $nameOnly;

    /**
     * File Extension
     *
     * @var string
     */
    private $extension;

    /**
     * File Minetype
     *
     * @var string
     */
    private $minetype;

    /**
     * File Temp
     *
     * @var string
     */
    private $tempFile;

    /**
     * File Size
     *
     * @var string
     */
    private $size;

    /**
     * File Error
     *
     * @var string
     */
    private $error;

    public function __construct(Application $app, $input)
    {
        $this->app = $app;

        $this->getFileInfo($input);
    }

    private function getFileInfo($input)
    {
        if (empty($_FILES[$input])) {
            return;
        }

        $file = $_FILES[$input];

        $this->error = $file['error'];

        if ($this->error != UPLOAD_ERR_OK) {
            return;
        }

        $this->file = $file;

        $this->fileName = $this->file['name'];

        $this->minetype = $this->file['type'];

        $this->size = $this->file['size'];

        $this->tempFile = $this->file['tmp_name'];

        $fileInfo = pathinfo($this->fileName);

        $this->nameOnly = $fileInfo['filename'];

        $this->extension = $fileInfo['extension'];
    }

    public function exists()
    {
        return !empty($this->file);
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

    public function moveTo($target, $newName = null)
    {
        $newName = $newName ?: sha1(rand()) . sha1(rand());
        $newName .= '.' . $this->extension;

        if (!is_dir($target)) {
            mkdir($target, 0777, true);
        }

        $filePath = $target . $newName;
        $filePath  = rtrim($filePath, '/');
        $filePath  = ltrim($filePath, '/');

        return move_uploaded_file($this->tempFile, $filePath);
    }
}
