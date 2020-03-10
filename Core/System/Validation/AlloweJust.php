<?php

namespace System\Validation;

use System\Application;

class AlloweJust
{
  private $app;
  private $characters;

  public function __construct(Application $app, $characters)
  {
    $this->app = $app;
    $this->characters = (!is_array($characters) && $characters !== '') ? [$characters] : $characters;

    $this->checkCharacters($this->characters);
  }

  private function isLink($character)
  {
    return strpos($character, 'path:') === 0;
  }

  private function hasLinkKeyAndValuse($character)
  {
    return strpos($character, '::');
  }

  private function hasLinkSpecificIndex($character)
  {
    return strpos($character, ':[');
  }

  private function hasLinkMultipleIndexes($indexes)
  {
    return strpos($indexes, '][');
  }

  private function whenLinkIndexes($indexes, $path)
  {
    $files = [];
    if ($this->hasLinkMultipleIndexes($indexes)) {
      $indexes = explode('][', $indexes);
      $files += $this->getIndexesInFiles($indexes, $path);
    } else {
      $files += $this->app->file->call($path . '.php')[$indexes];
    }
    return $files;
  }

  private function getIndexesInFiles($indexes, $path)
  {
    $indexesInFiles = [];

    foreach ($indexes as $index) {
      if (!empty($indexesInFiles)) {
        $indexesInFiles = $indexesInFiles[$index];

      } else {
        $indexesInFiles = $this->app->file->call($path . '.php')[$index];
      }
    }
    return $indexesInFiles;
  }

  private function formatPath($character)
  {
    $path = substr($character, 5);
    $getFrom = 'value';
    if ($this->hasLinkKeyAndValuse($character)) {
      list($path, $getFrom) = explode('::', $path);
    }
    return [$path, $getFrom];
  }

  private function formatCharacters($characters, $key, $character)
  {
    list($path, $getFrom) = $this->formatPath($character);
    $files = [];

    unset($characters[$key]);

    if ($this->hasLinkSpecificIndex($character)) {
      list($path, $indexes) = explode(':[', $path);
      $indexes = rtrim($indexes, ']');
      $files += $this->whenLinkIndexes($indexes, $path);
    } else {
      $files += $this->app->file->call($path . '.php');
    }
    $this->characters += $getFrom === 'keys' ? array_keys($files) : array_values($files);
  }

  private function checkCharacters($characters)
  {
    foreach ($characters as $key => $character) {
      if ($this->isLink($character)) {
        $this->formatCharacters($characters, $key, $character);
      } else {
        array_push($this->characters, $character);
      }
    }
  }

  public function getCharacters()
  {
    return $this->characters;
  }
}
