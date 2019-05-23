<?php

namespace System;

class Validation
{
    private $app;

    private $input;

    private $errors = [];

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function input($input)
    {
        $this->input = $input;

        return $this;
    }

    public function require($input, $msg = null)
    {
        if (!$this->input) $this->input = $input;

        $value = $this->value($this->input);
      
        if (! $value) {

            $msg = $msg ?: sprintf('%s is Required', ucfirst($this->input));

            $this->addError($this->input, $msg);;
        }

        return $this;
    }

    public function email($msg = null)
    {
        $value = $this->value($this->input);

        if (! filter_var($value, FILTER_VALIDATE_EMAIL)) {

            $msg = $msg ?: sprintf('%s is not valid Email', ucfirst($this->input));

            $this->addError($this->input, $msg);;
        }

        return $this;
    }

    public function float($input, $msg = null)
    {

    }

    public function minLen($length, $msg = null)
    {
        $value = $this->value($this->input);

        if (strlen($value) < $length) {

            $msg = $msg ?: sprintf('%s must be more than ' . $length, ucfirst($this->input));

            $this->addError($this->input, $msg);
        }

        return $this;
    }

    public function maxLen($length, $msg = null)
    {
        $value = $this->value($this->input);

        if (strlen($value) > $length) {

            $msg = $msg ?: sprintf('%s must be fewer than ' . $length, ucfirst($this->input));

            $this->addError($this->input, $msg);
        }

        return $this;
    }

    public function match($input, $msg = null)
    {
        $valuePassword = $this->value($this->input);

        $valueConfirm = $this->value($input);

        if ($valuePassword !== $valueConfirm && ! empty($valueConfirm)) {

            $msg = $msg ?: sprintf('%s dosnt match %s', ucfirst($input), ucfirst($this->input));
            
            $this->addError($this->input, $msg);
        }

        return $this;
    }

    public function unique($data, $msg = null)
    {
        $value = $this->value($this->input);

        $table = null;
        $column = null;

        $id = null;
        $userId = null;

        if (count($data) == 2) list($table, $column) = $data;
        if (count($data) == 4) list($table, $column, $id, $userId) = $data;

        $sql = $userId ? $column . ' = ? AND ' . $id . ' != ? ' : $column . ' = ?';

        $valueSql = $userId ? [$value, $userId] : $value;
        
        $result = $this->app->db->select($column)
                                ->from($table)
                                ->where($sql, $valueSql)
                                ->fetch();

        if ($result) {

            $msg = $msg ?: sprintf('%s is already exist', ucfirst($this->input));
            
            $this->addError($this->input, $msg);
        }

        return $this;

    }

    public function requireFile($input, $msg = null)
    {

    }

    public function image($input, $msg = null)
    {

    }

    public function message($msg = null)
    {
        $this->errors[] = $msg;

        return $this;
    }

    public function validate()
    {

    }

    public function passes()
    {
        return empty($this->errors);
    }

    public function fails()
    {
        return ! empty($this->errors);
    }

    public function getMsgs()
    {
        return $this->errors;
    }

    private function value($input)
    {
        return $this->app->request->post($input);
    }

    private function addError($input, $msg)
    {
        if (! $this->checkError($input)) {

            $this->errors[$input] = $msg;
        }
    }

    private function checkError($input)
    {
        return array_key_exists($input, $this->errors);
    }
}