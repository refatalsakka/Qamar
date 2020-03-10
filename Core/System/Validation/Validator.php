<?php

namespace System\Validation;

use System\Application;
use System\Validation\DateV;
use System\Validation\Characters;
use System\Validation\AlloweJust;

class Validator
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
   * @return mix
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

    $date = new DateV($value, $options);

    if (!$date->isAdate()) {
      $msg = $msg ?: "this field must be a Date";
      $this->addError($this->input, $msg);
      return $this;
    }
    extract($date->dateMethods($options));

    if (!$date->$method()) {
      $this->addError($this->input, $msg);
    }
    return $this;
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

    $characters = new Characters($excepts, $value);

    extract($characters->variables());

    foreach ($methods as $method => $options) {
      if (call_user_func_array(array($characters, $method), $options[0])) {
        $msg = $msg ?: $options[1];
        $this->addError($this->input, $msg);
        return $this;
      }
    }
    $re = "/^[0-9\\s$chars$langsRegex]*$/u";

    if (!preg_match($re, $value)) {
      $msg = $characters->charactersMsg($chars, $languages, $msg);
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
  public function alloweJust($characters = [], $msg = null)
  {
    if ($characters === false) return $this;

    $value = $this->value();

    if (!$value && $value != '0') return $this;

    $allowedCharacters = new AlloweJust($this->app, $characters);
    $characters = $allowedCharacters->getCharacters();

    if (!in_array($value, $characters)) {
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
