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
   * Input value
   *
   * @var string
   */
  private $value;

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

  public function input($input, $request = 'post')
  {
    $this->input = $input;

    $this->value = $this->app->request->$request($this->input);

    return $this;
  }

  /**
   * Get the value for the input name
   *
   * @return mixed
   */
  private function value()
  {
    return mb_strtolower($this->value);
  }

  /**
   * Determine if the input is not empty
   *
   * @param string $msg
   * @return $this
   */
  public function require($call = true, $msg = null)
  {
    if ($call === false) return $this;

    $value = $this->value();

    if ($value === '' || $value === null) {
      $msg = $msg ?: 'this field is required';

      $this->addError($this->input, $msg);
    }
    return $this;
  }

  /**
   * Call the function by given $type
   *
   * @param string $type
   * @return function
   */
  public function type($type)
  {
    return $this->$type();
  }

  /**
   * Determine if the input is valid email
   *
   * @param string $msg
   * @return $this
   */
  public function email($call = true, $msg = null)
  {
    if ($call === false) return $this;

    $value = $this->value();

    if (!$value && $value != '0') return $this;

    if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
      $msg = $msg ?: 'e-mail is not valid';

      $this->addError($this->input, $msg);
    }
    return $this;
  }

  /**
   * Determine if the input is an image
   *
   * @param string $customErrorMessage
   * @return $this
   */
  public function image($call = true, $msg = null)
  {
    if ($call === false) return $this;

    $file = $this->app->request->file($this->input);

    if (!$file->exists()) {
      return $this;
    }

    if (!$file->isImage()) {
      $msg = $msg ?: 'image is not valid';

      $this->addError($this->input, $msg);
    }
    return $this;
  }

  /**
   * Determine if the input has number
   *
   * @param string $msg
   * @return $this
   */
  public function number($call = true, $msg = null)
  {
    if ($call === false) return $this;

    $value = $this->value();

    if (!$value && $value != '0') return $this;

    if (!is_numeric($value)) {
      $msg = $msg ?: 'this field must be a number';

      $this->addError($this->input, $msg);
    }
    return $this;
  }

  /**
   * Determine if the input has float value
   *
   * @param string $msg
   * @return $this
   */
  public function float($call = true, $msg = null)
  {
    if ($call === false) return $this;

    $value = $this->value();

    if (!$value && $value != '0') return $this;

    if (!is_float($value)) {
      $msg = $msg ?: "this field must be a float number";

      $this->addError($this->input, $msg);
    }
    return $this;
  }

  /**
   * Determine if the input is a date
   * Determine if the input between the range if the $options['start']
   * or the $options ['end'] is exists
   *
   * @param string $options
   * @param string $msg
   * @return $this
   */
  public function date($options = [], $msg = null)
  {
    if ($options === false) return $this;

    $value = $this->value();

    if (!$value && $value != '0') return $this;

    $options = json_encode($options);
    $options = json_decode($options);

    $format = $options->format ?? 'd M Y';
    $start = $options->start ?? null;
    $end = $options->end ?? null;

    $checkFormat = DateTime::createFromFormat($format, $value);

    if (!$checkFormat) {
      $msg = $msg ?: 'this field must be a date';

      $this->addError($this->input, $msg);

      return $this;
    }

    if ($start && $end) {
      $year = DateTime::createFromFormat($format, $value)->format('Y');

      if ($year < $start  || $year > $end) {
        $msg = $msg ?: 'this field must be between ' . $start  . ' and ' . $end;

        $this->addError($this->input, $msg);

        return $this;
      }
    }

    if ($start) {
      $year = DateTime::createFromFormat($format, $value)->format('Y');

      if ($year < $start) {
        $msg = $msg ?: 'the date can\'t be under ' . $start;

        $this->addError($this->input, $msg);

        return $this;
      }
    }

    if ($end) {
      $year = DateTime::createFromFormat($format, $value)->format('Y');

      if ($year > $end) {
        $msg = $msg ?: 'the date can\'t be above ' . $end;

        $this->addError($this->input, $msg);

        return $this;
      }
    }
    return $this;
  }

  /**
   * Determine if the input has simple text
   *
   * @param string $msg
   * @return $this
   */
  public function pureText($call = true, $msg = null)
  {
    if ($call === false) return $this;

    $value = $this->value();

    if (!$value && $value != '0') return $this;

    if (!preg_match('/^[a-zA-Z]+$/', $value)) {
      $msg = $msg ?: 'this field must be just a text';

      $this->addError($this->input, $msg);
    }
    return $this;
  }

  /**
   * Determine if the input has simple text
   *
   * @param string $msg
   * @return $this
   */
  public function text($call = true, $msg = null)
  {
    if ($call === false) return $this;

    $value = $this->value();

    if (!$value && $value != '0') return $this;

    if (!is_string($value)) {
      $msg = $msg ?: 'the field must be a text';

      $this->addError($this->input, $msg);
    }
    return $this;
  }

  /**
   * Determine if the input has pure string
   *
   * @param string $msg
   * @return $this
   */
  public function noNumbers($call = true, $msg = null)
  {
    if ($call === false) return $this;

    $value = $this->value();

    if (!$value && $value != '0') return $this;

    if (preg_match('~[0-9]~', $value)) {
      $msg = $msg ?: 'numbers are not allow';

      $this->addError($this->input, $msg);
    }

    return $this;
  }

  /**
   * Determine if the input has no umlaut charachter
   *
   * @param string $msg
   * @return $this
   */
  public function noUmlautsExcept($excepts = [], $msg = null)
  {
    if ($excepts === false) return $this;

    $value = $this->value();

    if (!$value && $value != '0') return $this;

    $umlauts = 'á,â,ă,ä,ĺ,ç,č,é,ę,ë,ě,í,î,ď,đ,ň,ó,ô,ő,ö,ř,ů,ú,ű,ü,ý,ń,˙';

    $umlauts = explode(',', $umlauts);

    if ((is_string($excepts) && $excepts !== '')) {
      $excepts = explode(',', $excepts);
    } else if (!is_array($excepts)) {
      $excepts = [];
    }

    if ($excepts) {
      $characters = [];

      foreach($excepts as $character) {
        $characters[] = mb_strtolower($character);
      }
      $excepts = $characters;
    }

    foreach($umlauts as $umlaut) {
      if ((strpos($value, $umlaut) !== false && !in_array($umlaut, $excepts))) {
        $excepts = implode('', $excepts);
        if ($excepts) {
          $msg = $msg ?: "just [ $excepts ] can be used";
        } else {
          $msg = $msg ?: 'umlauts are not allow';
        }
        $this->addError($this->input, $msg);
      }
    }
    return $this;
  }

  /**
   * Determine if the input has pure string
   *
   * @param string $msg
   * @return $this
   */
  public function noCharachtersExcept($options = [], $msg = null)
  {
    if ($options === false) return $this;

    $value = $this->value();

    if (!$value && $value != '0') return $this;

    $umlauts = 'á,â,ă,ä,ĺ,ç,č,é,ę,ë,ě,í,î,ď,đ,ň,ó,ô,ő,ö,ř,ů,ú,ű,ü,ý,ń,˙';
    $umlauts = implode('', explode(',', $umlauts));

     $excepts = $options->excepts ?? [];
     $times = $options->times ?? 1;

     if ($excepts) {
      if (!is_array($excepts)) {
        $count_comma = substr_count($excepts, ',');

        if ($count_comma && $count_comma > 1) {
          $excepts = explode(',', $excepts);
        } else {
          $excepts = str_split($excepts);
        }
      }
      foreach($excepts as $except) {
        $count_charachter = substr_count($value, $except);
        if ($count_charachter && $count_charachter > $times) {
          $msg = $msg ?: "[ " .  implode(', ', $excepts) . " ] can be used just $times times";
          $this->addError($this->input, $msg);
          return $this;
        }
      }
      $excepts = implode('', $excepts);
    } else {
      $excepts = '';
    }

    if (!preg_match("/^[a-zA-Z0-9$umlauts$excepts]+$/", $value)) {
      $msg = $msg ?: 'just [ ' . $excepts . ' ] can be used';

      if ($excepts) {
        $msg = $msg ?: 'charachters are not allow';
      }
      $this->addError($this->input, $msg);
    }
    return $this;
  }

  /**
   * Determine if the input has spaces between the letters or the words
   *
   * @param string $msg
   * @return $this
   */
  public function noSpaces($call = true, $msg = null)
  {
    if ($call === false) return $this;

    $value = $this->value();

    if (!$value && $value != '0') return $this;

    if (preg_match('/\s/', $value)) {
      $msg = $msg ?: 'spaces are not allow';

      $this->addError($this->input, $msg);
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
  public function containJust($characters = [], $msg = null)
  {
    if ($characters === false) return $this;

    $value = $this->value();

    if (!$value && $value != '0') return $this;

    if (!is_array($characters) && $characters !== '') {
      $characters = [$characters];
    }

    $path = null;
    $indexes = null;

    $files = [];
    $final = [];

    foreach($characters as $key => $character) {
      if (strpos($character, 'path:') === 0) {
        unset($characters[$key]);

        $path = substr($character, 5);

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
        array_push($final, $character);
      }
    }

    if (!in_array($value, $final)) {
      $msg = $msg ?: 'wrong value';

      $this->addError($this->input, $msg);
    }
    return $this;
  }

  /**
   * Determine if the input value should equal length
   *
   * @param int $length
   * @param string $msg
   * @return $this
   */
  public function length($length = null, $msg = null)
  {
    if ($length === false) return $this;

    $value = $this->value();

    if (!$value && $value != '0') return $this;

    if (strlen($value) !== $length) {
      $msg = $msg ?: `this field can be just ${length} charachter`;

      $this->addError($this->input, $msg);
    }
    return $this;
  }

  /**
   * Determine if the input value should be at most the given length
   *
   * @param int $length
   * @param string $msg
   * @return $this
   */
  public function maxLen($length = null, $msg = null)
  {
    if ($length === false) return $this;

    $value = $this->value();

    if (!$value && $value != '0') return $this;

    if (strlen($value) > $length) {
      $msg = $msg ?: "this field can be maximum $length charachter";

      $this->addError($this->input, $msg);
    }
    return $this;
  }

  /**
   * Determine if the input value should be at least the given length
   *
   * @param int $length
   * @param string $msg
   * @return $this
   */
  public function minLen($length = null, $msg = null)
  {
    if ($length === false) return $this;

    $value = $this->value();

    if (!$value && $value != '0') return $this;

    if (strlen($value) < $length) {
      $msg = $msg ?: "this field can be minimum $length charachter";

      $this->addError($this->input, $msg);
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
    if ($input === false) return $this;

    $value = $this->value();

    $valueConfirm = $this->app->request->post($input);

    if ($value && $valueConfirm) {
      if ($value !== $valueConfirm) {
        $msg = $msg ?: 'passwords doesn\'t match';

        $this->addError('match', $msg);
      }
    }
    return $this;
  }

  /**
   * Determine if the input is unique in database
   *
   * @param array $data
   * @param string $msg
   * @return $this
   */
  public function unique($data = [], $msg = null)
  {
    if ($data === false) return $this;

    $value = $this->value();

    if (!$data) return $this;

    if (is_array($data)) {
      list($table, $column) = $data;
    } else {
      $table = $data;
      $column = $this->input;
    }

    $result = $this->app->db->select($column)->from($table)->where($column . ' = ? ', $value)->fetch();

    if ($result) {
      $msg = $msg ?: sprintf('%s is already exist', ucfirst($this->input));

      $this->addError($this->input, $msg);
    }
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
   * Determine if the given input has previous errors
   *
   * @param string $inputName
   */
  private function hasError($input)
  {
    return array_key_exists($input, $this->errors);
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
   * Get all errors
   *
   * @return array
   */
  public function getErrors()
  {
    return $this->errors;
  }
}
