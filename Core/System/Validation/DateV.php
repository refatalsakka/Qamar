<?php

namespace System\Validation;

use DateTime;

class DateV
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
    $this->format = $options->format ?? 'd M Y';
    $this->value = $value;
    $this->start = $options->start ?? null;
    $this->end = $options->end ?? null;
    $this->year = ($this->isAdate()) ? DateTime::createFromFormat($this->format, $this->value)->format('Y') : null;
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
    $this->start = $this->start || $start;

    if ($this->year < $this->start) {
      return false;
    }
    return true;
  }

  public function maximum($end = null)
  {
    $this->end = $this->end || $end;

    if ($this->year > $this->end) {
      return false;
    }
    return true;
  }

  public function dateMethods($options)
  {
    $method = null;
    $msg = null;

    if (isset($options->start) && isset($options->end)) {
      $method = 'isDateBetween';
      $msg = 'this field must be between ' . $options->start . ' and ' . $options->end;
    } elseif (isset($options->start)) {
      $method = 'minimum';
      $msg = 'the date can\'t be under ' . $options->start;
    } elseif (isset($options->end)) {
      $method = 'maximum';
      $msg = 'the date can\'t be above ' . $options->end;
    }
    return array('method' => $method, 'msg' => $msg);
  }
}
