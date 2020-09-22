<?php

namespace System\Http;

use System\Application;
use Exception;

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

    /**
     * Constructor
     *
     * @param \System\Application $app
     * @param $file
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Recive the file key
     *
     * @param string $file
     * @return object $this;
     */
    public function file(string $file)
    {
        $this->setFileInfo($file);

        return $this;
    }

    /**
     * Set the variables
     *
     * @property object $request
     * @param string $file
     * @return void
     */
    private function setFileInfo($file)
    {
        $this->file = $this->app->request->file($file);

        $this->error = $this->file['error'];

        if ($this->error != UPLOAD_ERR_OK) {
            throw new Exception('Something went wrong');
        }

        $this->fileName = $this->file['name'];

        $this->minetype = $this->file['type'];

        $this->size = $this->file['size'];

        $this->tempFile = $this->file['tmp_name'];

        $fileInfo = pathinfo($this->fileName);

        $this->nameOnly = $fileInfo['filename'];

        $this->extension = $fileInfo['extension'];
    }

    /**
     * Get file name
     *
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * Get file name only
     *
     * @return string
     */
    public function getNameOnly()
    {
        return $this->nameOnly;
    }

    /**
     * Get extension
     *
     * @return string
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * Get minetype
     *
     * @return string
     */
    public function getMinetype()
    {
        return $this->minetype;
    }

    /**
     * Get size
     *
     * @return string
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Get temp file
     *
     * @return string
     */
    public function getTempFile()
    {
        return $this->tempFile;
    }

    /**
     * Move file to the geven target
     *
     * @param string $target
     * @param string $newName
     * @return bool
     */
    public function moveTo(string $target, string $newName = null)
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
