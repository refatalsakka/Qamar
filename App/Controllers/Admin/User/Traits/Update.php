<?php

namespace App\Controllers\Admin\User\Traits;

trait Update
{
  public function update()
  {
    $id = userId();

    $posts = $this->request->posts();
    $name = array_keys($posts)[0];
    $columns = $this->getUserConfigColumns();
    $table = $columns->$name->table;
    $column = $columns->$name;
    $filters = $columns->$name->filters;
    $value = ($posts[$name] == '') ? null : isset($filters->date) ? date('Y-m-d', strtotime($posts[$name])) : $posts[$name];
    $user_id_table_name = $column->user_id_table_name;

    $methods = $this->updateMethodsToCheckBeforeContinue([
      'id' => $id,
      'name' => $name,
      'table' => $table,
      'user_id_table_name' => $user_id_table_name,
      'value' => $value,
      'filters' => $filters,
    ]);

    $error = $this->checkForErrorsInUpdateMethods($methods);

    if ($error) {
      return json_encode($error);
    }
    $msg = $this->userUpdateMsg($name, $value, $filters);
    return json_encode($msg);
  }

  private function checkForErrorsInUpdateMethods($methods)
  {
    foreach ($methods as $method => $options) {
      if (call_user_func_array(array($this, $method), $options[0]) == false) {
        return $this->updateErrorMsg($options);
      }
    }
    return false;
  }

  private function updateErrorMsg($options)
  {
    $msg = null;

    if (array_keys($options[1])[0] === 'msg') {
      $msg = array_values($options[1]);
    } else {
      if (array_keys($options[1])[0] === 'error') {
        $msg['error'] = $this->validator->getErrors();
      } else {
        $msg[array_keys($options[1])[0]] = array_values($options[1]);
      }
    }
    return $msg;
  }

  private function updateMethodsToCheckBeforeContinue($args)
  {
    extract($args);
    return [
      'isUserFound' => [
        [$id],
        ['msg' => 'reload'],
      ],
      'isValueChanged' => [
        [$name, $table, $user_id_table_name, $id, $value],
        ['same' => $value ? strtolower($value) : ''],
      ],
      'validatorPasses' => [
        [$filters, $name],
        ['error' => ''],
      ],
      'updateUser' => [
        [$name, $value, $user_id_table_name, $id, $table],
        ['msg' => 'reload'],
      ],
    ];
  }

  private function userUpdateMsg($name, $value, $filters)
  {
    $msg = null;

    if ($name === 'country') {
      $msg['country'] = [
        $value => $this->countries($value),
      ];
    } else {
      $msg['text'] = isset($filters->date) ? $this->changeFormatDate($value, ['Y-m-d', 'd M Y']) : _e($value);
    }
    return $msg;
  }

  private function updateUser($name, $value, $user_id_table_name, $id, $table)
  {
    return $this->db->data($name, $value)->where($user_id_table_name . ' = ?', $id)->update($table);
  }

  private function isUserFound($id)
  {
    return $this->load->model('User')->get($id);
  }

  private function isValueChanged($name, $table, $user_id_table_name, $id, $value)
  {
    $current_value = $this->db->select($name)->from($table)->where($user_id_table_name . ' = ?', [$id])->fetch()->$name;
    if (($current_value === strtolower($value)) || ($value == null && $current_value == null)) {
      return false;
    }
    return true;
  }
}
