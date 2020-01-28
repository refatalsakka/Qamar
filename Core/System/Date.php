<?php

namespace System;
use DateTime;

class Date
{
  private $start;
  private $end;
  private $format;
  private $value;
  private $year;

  /**
   * Constructor
   *
   */
  public function __construct($value, $options = [])
  {
    $this->start = $options->start ?? null;
    $this->end = $options->end ?? null;
    $this->format = $options->format ?? 'd M Y';
    $this->value = $value;
    $this->year = DateTime::createFromFormat($this->format, $this->value)->format('Y');
  }

  public function isAdate()
  {
    return DateTime::createFromFormat($this->format, $this->value);
  }

  public function isDateBetween($start = null, $end = null)
  {
    if (!$this->start || !$this->end) {
      $this->start = $start;
      $this->end = $end;
    }

    if ($this->year < $this->start || $this->year > $this->end) {
      return false;
    }
    return true;
  }

  public function minimum($start = null)
  {
    $this->start = $this->start ?? $start;

    if ($this->year < $this->start) {
      return false;
    }
    return true;
  }

  public function maximum($end = null)
  {
    $this->end = $this->end ?? $end;

    if ($this->year > $this->end) {
      return false;
    }
    return true;
  }
}
