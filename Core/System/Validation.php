<?php

namespace System;
use DateTime;

class Validation
{
  /**
   * Application Object
   *
   * @var \System\Application
   */
  private $app;

  /**
   * Input name
   *
   * @var string
   */
  private $input;

  /**
   * Errors container
   *
   * @var array
   */
  private $errors = [];

  /**
   * Constructor
   *
   * @param \System\Application $app
   */
  public function __construct(Application $app)
  {
    $this->app = $app;
  }

  public function input($input)
  {
    $this->input = $input;

    return $this;
  }

  /**
   * Determine if the given input is not empty
   *
   * @param string $input
   * @param string $msg
   * @return $this
   */
  public function require($msg = null)
  {
    $value = $this->value($this->input);

    if ($value === '' || $value === null) {

      $msg = $msg ?: 'This input is required';

        $this->addError($this->input, $msg);
    }
    return $this;
  }

  /**
   * Determine if the given input is valid email
   *
   * @param string $input
   * @param string $msg
   * @return $this
   */
  public function email($msg = null)
  {
    $value = $this->value($this->input);

    if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {

      $msg = $msg ?: sprintf('%s is not valid Email', ucfirst($this->input));

      $this->addError($this->input, $msg);
    }

    return $this;
  }

  /**
   * Determine if the given input is an image
   *
   * @param string $input
   * @param string $customErrorMessage
   * @return $this
   */
  public function image($msg = null)
  {
    $file = $this->app->request->file($this->input);

    if (!$file->exists()) {

      return $this;
    }

    if (!$file->isImage()) {

      $msg = $msg ?: sprintf('%s Is not valid Image', ucfirst($this->input));

      $this->addError($this->input, $msg);
    }

    return $this;
  }

  /**
   * Determine if the given input has number
   *
   * @param string $input
   * @param string $msg
   * @return $this
   */
  public function number($msg = null)
  {
    $value = $this->value($this->input);

    if ($value) {

      if (!is_numeric($value)) {

        $msg = $msg ?: 'the Input must be number';

        $this->addError($this->input, $msg);
      }
    }

    return $this;
  }

  /**
   * Determine if the given input is date
   *
   * @param string $format
   * @param string $msg
   * @return $this
   */
  public function date($format, $msg = null)
  {
    $value = $this->value($this->input);

    if ($value) {

      $checkFormat = DateTime::createFromFormat($format, $value);

      if (!$checkFormat) {

        $msg = $msg ?: 'the Input must be Date';

        $this->addError($this->input, $msg);
      }
    }

    return $this;
  }

  /**
   * Determine if the given date in the given range
   *
   * @param string $format
   * @param array $range
   * @param string $msg
   * @return $this
   */
  public function dateRange($format, array $range, $msg = null)
  {
    $value = $this->value($this->input);

    if ($value) {

      $checkFormat = DateTime::createFromFormat($format, $value);

      if ($checkFormat) {

        $year = DateTime::createFromFormat($format, $value)->format('Y');

        if ($year < $range['start'] || $year > $range['end']) {

          $msg = $msg ?: 'The date must be between ' . $range['start'] . ' and ' . $range['end'];

          $this->addError($this->input, $msg);
        }
      }
    }

    return $this;
  }

  /**
   * Determine if the given input has float value
   *
   * @param string $input
   * @param string $msg
   * @return $this
   */
  public function float($msg = null)
  {
    $value = $this->value($this->input);

    if (!is_float($value)) {

      $msg = $msg ?: sprintf('%s Accepts only floats', ucfirst($this->input));

      $this->addError($this->input, $msg);
    }

    return $this;
  }

  /**
   * Determine if the given input has string
   *
   * @param string $input
   * @param string $msg
   * @return $this
   */
  public function text($msg = null)
  {
    $value = $this->value($this->input);

    if ($value) {

      if (is_numeric($value) || preg_match('~[0-9]~', $value) === 1) {

        $msg = $msg ?: 'the Input must be Text';

        $this->addError($this->input, $msg);
      }
    }

    return $this;
  }

  /**
   * Determine if the given input has pure string
   *
   * @param string $input
   * @param string $msg
   * @return $this
   */
  public function textWithAllowNumber($msg = null)
  {
    $value = $this->value($this->input);

    if ($value) {

      if (is_numeric($value)) {

        $msg = $msg ?: 'the Input can\'t be jsut Number ';

        $this->addError($this->input, $msg);
      }
    }

    return $this;
  }

  /**
   * Determine if the given input has no umlaut charachter
   *
   * @param string $input
   * @param string $msg
   * @return $this
   */
  public function noUmlaut($msg = null)
  {
    $value = $this->value($this->input);

    if ($value) {

      $umlauts = 'Ŕ,Á,Â,Ă,Ä,Ĺ,Ç,Č,É,Ę,Ë,Ě,Í,Î,Ď,Ň,Ó,Ô,Ő,Ö,Ř,Ů,Ú,Ű,Ü,Ý,ŕ,á,â,ă,ä,ĺ,ç,č,é,ę,ë,ě,í,î,ď,đ,ň,ó,ô,ő,ö,ř,ů,ú,ű,ü,ý,˙,Ń,ń';

      $umlauts = explode(',', $umlauts);

      foreach($umlauts as $umlaut) {

        if ((strpos($value, $umlaut) !== false)) {

        $msg = $msg ?: 'the Input can\'t contain umlaut';

        $this->addError($this->input, $msg);
        }
      }

    }
    return $this;
  }

  /**
   * Determine if the given input has the value that are passed
   *
   * @param $allow
   * @param string $msg
   * @return $this
   */
  public function containJust($allowes, $msg = null)
  {
    $value = $this->value($this->input);

    if ($value) {

      if (!is_array($allowes) && $allowes !== '') {

        $allowes = [$allowes];
      }

      $path = null;
      $indexes = null;

      $files = [];
      $final = [];

      foreach($allowes as $key => $allow) {

        if (strpos($allow, 'path:') === 0) {

          unset($allowes[$key]);

          $path = substr($allow, 5);

          $getFrom = 'value';

          if (strpos($path, '::')) {

            list($path, $getFrom) = explode('::', $path);
          }

          if (strpos($path, ':[')) {

            list($path, $indexes) = explode(':[', $path);

            $indexes = rtrim($indexes, ']');

            if (strpos($indexes, '][')) {

              $indexesInFiles = [];

              $indexes = explode('][', $indexes);

              foreach ($indexes as $index) {

                if (!empty($indexesInFiles)) {

                  $indexesInFiles = $indexesInFiles[$index];

                } else {

                  $indexesInFiles = $this->app->file->call($path . '.php')[$index];
                }
              }

              $files += $indexesInFiles;

            } else {

              $files += $this->app->file->call($path . '.php')[$indexes];
            }

          } else {

            $files += $this->app->file->call($path . '.php');
          }

          if ($getFrom === 'keys') {

            $final += array_keys($files);
          } else {

            $final += array_values($files);
          }

        } else {

          array_push($final, $allow);
        }
      }

      if (!in_array($value, $final)) {

        $msg = $msg ?: 'Wrong value';

        $this->addError($this->input, $msg);
      }

    }
    return $this;
  }

  /**
   * Determine if the given input has spaces between the letters or the words
   *
   * @param string $msg
   * @return $this
   */
  public function noSpaceBetween($msg = null)
  {
    $value = $this->value($this->input);


    if ($value) {

      if (preg_match('/\s/', $value)) {

        $msg = $msg ?: 'Spaces are not allow';

        $this->addError($this->input, $msg);
      }
    }

    return $this;
  }

  /**
   * Determine if the given input value should be at least the given length
   *
   * @param string $input
   * @param int $length
   * @param string $msg
   * @return $this
   */
  public function minLen($length, $msg = null)
  {
    $value = $this->value($this->input);

    if ($value) {

      if (strlen($value) < $length) {

        $msg = $msg ?: 'This input must be at least ' . $length;

        $this->addError($this->input, $msg);
      }
    }

    return $this;
  }

  /**
   * Determine if the given input value should be at most the given length
   *
   * @param string $input
   * @param int $length
   * @param string $msg
   * @return $this
   */
  public function maxLen($length, $msg = null)
  {
    $value = $this->value($this->input);

    if ($value) {

      if (strlen($value) > $length) {

        $msg = $msg ?: 'This must be ' . $length . ' or fewer';

        $this->addError($this->input, $msg);
      }
    }

    return $this;
  }

  /**
   * Determine if the $input matches the given input
   *
   * @param string $input
   * @param string $msg
   * @return $this
   */
  public function match($input, $msg = null)
  {
    $valuePassword = $this->value($this->input);

    $valueConfirm = $this->value($input);

    if ($valuePassword && $valueConfirm) {

      if ($valuePassword !== $valueConfirm) {

        $msg = $msg ?: 'Passwords does not match';

        $this->addError('match', $msg);
      }
    }

    return $this;
  }

  /**
   * Determine if the given input is unique in database
   *
   * @param string $input
   * @param array $data
   * @param string $msg
   * @return $this
   */
  public function unique(array $data, $msg = null)
  {
    $value = $this->value($this->input);

    list($table, $column) = $data;

    $result = $this->app->db->select($column)->from($table)->where($column . ' = ? ', $value)->fetch();

    if ($result) {

      $msg = $msg ?: sprintf('%s is already exist', ucfirst($this->input));

      $this->addError($this->input, $msg);
    }

    return $this;
  }

  /**
   * Add custom message
   *
   * @param string $msg
   * @return $this
   */
  public function message($msg = null)
  {
    $this->errors[] = $msg;

    return $this;
  }

  /**
   * Determine if all inputs are valid
   *
   * @return bool
   */
  public function passes()
  {
    return empty($this->errors);
  }

  /**
   * Determine if there are any invalid inputs
   *
   * @return bool
   */
  public function fails()
  {
    return !empty($this->errors);
  }

  /**
   * Get all errors
   *
   * @return array
   */
  public function getMsgs()
  {
    return $this->errors;
  }

  /**
   * Get the value for the given input name
   *
   * @param string $input
   * @return mixed
   */
  private function value($input)
  {
    return $this->app->request->post($input);
  }


  /**
   * Add input error
   *
   * @param string $inputName
   * @param string $errorMessage
   * @return void
   */
  public function addError($input, $msg)
  {
    if (!$this->hasError($input)) {

      $this->errors[$input] = $msg;
    }
  }

  /**
   * Determine if the given input has previous errors
   *
   * @param string $inputName
   */
  private function hasError($input)
  {
    return array_key_exists($input, $this->errors);
  }
}
