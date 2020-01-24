<?php

namespace System;
use DateTime;
use System\Date;

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
   * @return function
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

    $date = new Date($options, $value);

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
      $msg = 'this field must be between ' . $options->start  . ' and ' . $options->end;
    } elseif ($options->start) {
      $method = 'minimum';
      $msg = 'the date can\'t be under ' . $options->start;
    } elseif ($options->end) {
      $method = 'maximum';
      $msg = 'the date can\'t be above ' . $options->end;
    }
    return array ('method' => $method,'msg'=> $msg);
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

  private function languages($language)
  {
    $languages = [
      'all' => '\\p{L}',
      'arabic' =>  '\\x{0621}-\\x{064A}\\x{0660}-\\x{0669} ُ ْ َ ِ ّ~ ً ٍ ٌ',
      'english' => 'a-z',
      'spanish' => 'a-zñ',
      'french' => 'a-zàâçéèêëîïôûùüÿñæœ',
      'german' => 'a-zäüöß',
    ];
    return $languages[$language];
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

    $chars = '';
    $times = null;
    $atFirst = null;
    $atEnd = null;
    $between = null;
    $langsRegex = '';
    $languages = '';

    if (is_object($excepts) && count((array) $excepts)) {
      if (is_string($excepts->chars)) {
        if (preg_match('/,/', $excepts->chars) && preg_match_all('/,/', $excepts->chars) > 1) {
          $chars = explode(',', $excepts->chars);
          $chars = "\\" . implode('\\', $chars);
        } else {
          $chars = str_split($excepts->chars);
          $chars = "\\" . implode('\\', $chars);
        }
      } else if (is_object($excepts->chars) && count((array) $excepts->chars)) {
        $chars = $excepts->chars->value;
        if (is_array($chars)) {
          $chars = implode('', $chars);
        } else if (is_string($chars)) {
          if (preg_match('/,/', $chars) && preg_match_all('/,/', $chars) > 1) {
            $chars = explode(',', $chars);
            $chars = "\\" . implode('\\', $chars);
          } else {
            $chars = str_split($chars);
            $chars = "\\" . implode('\\', $chars);
          }
        }
        $times = $excepts->chars->times ?? null;
        $atFirst = $excepts->chars->atFirst;
        $atEnd = $excepts->chars->atEnd;
        $between = $excepts->chars->between;
      }

      if (is_array($excepts->languages)) {
        foreach($excepts->languages as $language) {
          $langsRegex .= $this->languages(trim($language));
          $languages .= "$language, ";
        }
        $languages = rtrim($languages, ", ");
      } else if (is_string($excepts->languages)) {
        if (preg_match('/,/', $excepts->languages) && preg_match_all('/,/', $excepts->languages)) {
          foreach(explode(',', $excepts->languages) as $language) {
            $langsRegex .= $this->languages(trim($language));
            $languages .= "$language, ";
          }
          $languages = rtrim($languages, ", ");
        } else {
          $langsRegex = $this->languages(trim($excepts->languages));
          $languages = $excepts->languages;
        }
      }
    }

    if ($times > 0) {
      $splitChars = $chars;
      if (strlen($chars) > 1) {
        $splitChars = str_split($splitChars);
        $splitChars = "\\" . implode('|\\', $splitChars);
      }
      $re1 = "/($splitChars)/";
      if (preg_match($re1, $value) && preg_match_all($re1, $value) > $times) {
        $msg = $msg ?: 'charachters are too many';

        $this->addError($this->input, $msg);
        return $this;
      }
    }

    if ($atFirst === false) {
      $splitChars = $chars;
      if (strlen($chars) > 1) {
        $splitChars = str_split($splitChars);
        $splitChars = "\\" . implode('|\\', $splitChars);
      }
      $re2 = "/^($splitChars"."|\\s+\\$splitChars)/";
      if (preg_match_all($re2, $value)) {
        $msg = $msg ?: 'charachters cant be in the first';

        $this->addError($this->input, $msg);
        return $this;
      }
    }

    if ($atEnd === false) {
      $splitChars = $chars;
      if (strlen($chars) > 1) {
        $splitChars = str_split($splitChars);
        $splitChars = "\\" . implode('|\\', $splitChars);
      }
      $re3 = "/($splitChars"."|\\$splitChars\\s+)$/";
      if (preg_match_all($re3, $value)) {
        $msg = $msg ?: 'charachters cant be in the end';

        $this->addError($this->input, $msg);
        return $this;
      }
    }

    if ($between === false) {
      $splitChars = $chars;
      if (strlen($chars) > 1) {
        $splitChars = str_split($splitChars);
        $splitChars = "\\" . implode('|\\', $splitChars);
      }
      $re4 = "/.+(${splitChars})(.+|\\s)/";
      if (preg_match_all($re4, $value)) {
        $msg = $msg ?: 'charachters cant be between';

        $this->addError($this->input, $msg);
        return $this;
      }
    }

    if ($langsRegex) {
      if ($languages !== 'all' && preg_match_all('/a-z/i', $langsRegex) > 1) {
        $langsRegex = preg_replace('/a-z/i', '', $langsRegex);
        $langsRegex .= 'a-z';
      }
    } else {
      $languages = 'english';
      $langsRegex = $this->languages('english');
    }

    $re5 = "/^[0-9\\s$chars$langsRegex]*$/u";
    if (!preg_match($re5, $value)) {
      $chars =  explode('\\', $chars);
      $chars =  implode('', $chars);
      $chars = $chars ? "[ $chars ] and" : '';
      $languages = $languages ? "[ $languages ]" : '';
      $msg = $msg ?: "just $chars $languages letters can be used";
      $this->addError($this->input, $msg);
    }
    return $this;
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
