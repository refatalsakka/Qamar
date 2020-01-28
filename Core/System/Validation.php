<?php

namespace System;

use System\Date;
use System\Characters;

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
   * @param bool $call
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
   * @return @method
   */
  public function type($type)
  {
    return $this->$type();
  }

  /**
   * Determine if the input is valid email
   *
   * @param bool $call
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
   * @param bool $call
   * @param string $customErrorMessage
   * @return $this
   */
  public function image($call = true, $msg = null)
  {
    if ($call === false) return $this;

    $file = $this->app->request->file($this->input);

    if (!$file->exists()) return $this;

    if (!$file->isImage()) {
      $msg = $msg ?: 'image is not valid';

      $this->addError($this->input, $msg);
    }
    return $this;
  }

  /**
   * Determine if the input has number
   *
   * @param bool $call
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
   * @param bool $call
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

    extract($this->dateMethods($options));

    $date = new Date($value, $options);

    if (!$date->$method()) {
      $this->addError($this->input, $msg);
    }
    return $this;
  }

  private function dateMethods($options)
  {
    $method = null;
    $msg = null;
    if ($options->start && $options->end) {
      $method = 'isDateBetween';
      $msg = 'this field must be between ' . $options->start . ' and ' . $options->end;
    } elseif ($options->start) {
      $method = 'minimum';
      $msg = 'the date can\'t be under ' . $options->start;
    } elseif ($options->end) {
      $method = 'maximum';
      $msg = 'the date can\'t be above ' . $options->end;
    }
    return array('method' => $method, 'msg'=> $msg);
  }

  /**
   * Determine if the input has simple text
   *
   * @param bool $call
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
   * @param bool $call
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
   * Determine if the input has pure string
   *
   * @param array $excepts
   * @param string $msg
   * @return $this
   */
  public function characters($excepts, $msg = null)
  {
    if ($excepts === false) return $this;

    $value = $this->value();

    if (!$value && $value != '0') return $this;

    extract($this->charactersvariables($excepts, $value));

    if ($this->checkForErrorsInCharactersMethods($methods, $msg)) {
      return $this;
    }

    $re = "/^[0-9\\s$chars$langsRegex]*$/u";
    if (!preg_match($re, $value)) {
      $chars = $this->charactersFormatCharsMsg($chars);
      $languages = $languages ? "[ $languages ]" : '';
      $msg = $msg ?: "just $chars $languages letters can be used";
      $this->addError($this->input, $msg);
    }
    return $this;
  }

  private function charactersFormatCharsRegex($chars)
  {
    if (strlen($chars) > 1) {
      $chars = str_split($chars);
      $chars = "\\" . implode('|\\', $chars);
    }
    return $chars;
  }

  private function charactersFormatCharsMsg($chars)
  {
    $chars = explode('\\', $chars);
    $chars = implode('', $chars);
    $chars = $chars ? "[ $chars ] and" : '';
    return $chars;
  }

  private function charactersvariables($excepts, $value)
  {
    $characters = new Characters($excepts);
    $chars = $characters->getChars();
    $langsRegex = $characters->getLangsRegex();
    $languages = $characters->getLanguages();
    $times = $characters->getTimes();
    $atFirst = $characters->getAtFirst();
    $atEnd = $characters->getAtEnd();
    $between = $characters->getBetween();
    $methods = $this->charactersMethods([
      "times" => $times,
      "atFirst" => $atFirst,
      "atEnd" => $atEnd,
      "between" => $between,
      "chars" => $chars,
      "value" => $value,
    ]);

    return [
      'characters' => $characters,
      'chars' => $chars,
      'langsRegex' => $langsRegex,
      'languages' => $languages,
      'times' => $times,
      'atFirst' => $atFirst,
      'atEnd' => $atEnd,
      'between' => $between,
      'methods' => $methods,
    ];
  }

  private function charactersMethods($args)
  {
    extract($args);
    return [
      'charactersTimes' => [
        [$times, $chars, $value],
        'charachters are too many',
      ],
      'charactersAtFirst' => [
        [$atFirst, $chars, $value],
        'charachters cant be at the first',
      ],
      'charactersAtEnd' => [
        [$atEnd, $chars, $value],
        'charachters cant be at the end',
      ],
      'charactersBetween' => [
        [$between, $chars, $value],
        'charachters cant be between',
      ],
    ];
  }

  private function charactersTimes($times, $chars, $value)
  {
    if ($times > 0) {
      $chars = $this->charactersFormatCharsRegex($chars);
      $re = "/($chars)/";
      if (preg_match($re, $value) && preg_match_all($re, $value) > $times) {
        return true;
      }
      return false;
    }
  }

  private function charactersAtFirst($atFirst, $chars, $value)
  {
    if ($atFirst === false) {
      $chars = $this->charactersFormatCharsRegex($chars);
      $re = "/^($chars" . "|\\s+\\$chars)/";
      if (preg_match_all($re, $value)) {
        return true;
      }
      return false;
    }
  }

  private function charactersAtEnd($atEnd, $chars, $value)
  {
    if ($atEnd === false) {
      $chars = $this->charactersFormatCharsRegex($chars);
      $re = "/($chars" . "|\\$chars\\s+)$/";
      if (preg_match_all($re, $value)) {
        return true;
      }
      return false;
    }
  }

  private function charactersBetween($between, $chars, $value)
  {
    if ($between === false) {
      $chars = $this->charactersFormatCharsRegex($chars);
      $re = "/.+(${chars})(.+|\\s)/";
      if (preg_match_all($re, $value)) {
        return true;
      }
      return false;
    }
  }

  private function checkForErrorsInCharactersMethods($methods, $msg)
  {
    foreach ($methods as $method => $options) {
      if (call_user_func_array(array($this, $method), $options[0])) {
        $msg = $msg ?: $options[1];
        $this->addError($this->input, $msg);
        return true;
      }
    }
    return false;
  }

  /**
   * Determine if the input has spaces between the letters or the words
   *
   * @param bool $call
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
   * @param array $characters
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

    foreach ($characters as $key => $character) {
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
   * @param string $input
   */
  private function hasError($input)
  {
    return array_key_exists($input, $this->errors);
  }

  /**
   * Add input error
   *
   * @param string $inputName
   * @param string $msg
   * @return void
   */
  public function addError($input, $msg)
  {
    if (!$this->hasError($input)) $this->errors[$input] = $msg;
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
