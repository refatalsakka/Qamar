<?php

namespace App\Controllers\Admin;

use System\Controller as Controller;
use RandomLib\Factory as Factory;

class UsersController extends Controller
{
  public function index()
  {
    $users = $this->load->model('User')->users();
    $usersformatted = $this->formatUsers($users);
    $countries = $this->countries('all', 'name');

    $context = [
      'users' => $usersformatted,
      'countries' => $countries,
    ];
    return $this->view->render('admin/pages/users/users', $context);
  }

  public function row()
  {
    $id = userId();
    $model = $this->load->model('User');
    $user = $model->user($id);

    $user->new = $this->isUserNew($user->registration);
    $user->registration = $this->changeFormatDate($user->registration);
    $user->last_login = $this->changeFormatDate($user->last_login);
    $user->last_logout = $this->changeFormatDate($user->last_logout);
    $user->birthday = $this->changeFormatDate($user->birthday, ['Y-m-d', 'd M Y']);
    $user->country_icon = $this->countries($user->country);

    $countries = $this->countries('all', 'name');
    $countries_options = implode(',', $countries);

    $context = [
      'user' => $user,
      'countries_options' => $countries_options,
    ];
    return $this->view->render('admin/pages/users/user', $context);
  }

  public function formatUsers($users)
  {
    $users_for_list = [];

    foreach ($users as $user) {
      $user->new = $this->isUserNew($user->registration);
      $user->country_icon = $this->countries($user->country);
      $user->registration = $this->changeFormatDate($user->registration);
      $user->last_login = $this->changeFormatDate($user->last_login);
      $users_for_list[] = $user;
    }
    return $users_for_list;
  }

  public function filter()
  {
    $msg = null;
    $gets = $this->request->gets();

    if (empty($gets)) {
      $users = $this->load->model('User')->users();
      $usersformatted = $this->formatUsers($users);
      return json_encode($usersformatted);
    }

    $gender = $gets['gender'] ?? null;
    $zip = $gets['zip'] ?? null;
    $country = $gets['country'] ?? null;
    $registration_from = $gets['registration_from'] ?? null;
    $registration_to = $gets['registration_to'] ?? null;
    $active = $gets['active'] ?? null;
    $pending = $gets['pending'] ?? null;
    $inactive = $gets['inactive'] ?? null;
    $online = $gets['online'] ?? null;
    $offline = $gets['offline'] ?? null;

    $sql = '';
    $wheres = [];

    if ($active && $active == '1') {
      $sql .= 'status = ? AND ';
      array_push($wheres, '2');
    }
    if ($pending && $pending == '1') {
      $sql .= 'status = ? AND ';
      array_push($wheres, '1');
    }
    if ($inactive && $inactive == '1') {
      $sql .= 'status = ? AND ';
      array_push($wheres, '0');
    }

    $count_status = substr_count($sql, 'status = ?');

    if ($count_status > 1) {
      $sql = str_replace('status = ? AND', 'status = ? OR', $sql);
      $sql = rtrim($sql, 'OR ');
      $sql = "( $sql )";
      $sql .= ' AND ';
    }

    if ($online && $online == '1') {
      $sql .= 'is_login = ? AND ';
      array_push($wheres, '1');
    }
    if ($offline && $offline == '1') {
      $sql .= 'is_login = ? AND ';
      array_push($wheres, '0');
    }

    $count_is_login = substr_count($sql, 'is_login = ?');

    if ($count_is_login > 1) {
      $sql = str_replace('is_login = ? AND', 'is_login = ? OR', $sql);
      $sql = rtrim($sql, 'OR ');
      $sql = "( $sql )";
      $sql .= ' AND ';
    }

    if ($gender) {
      $sql .= 'gender = ? AND ';
      array_push($wheres, $gender);
    }
    if ($zip) {
      $sql .= 'zip = ? AND ';
      array_push($wheres, $zip);
    }
    if ($country) {
      $sql .= 'country = ? AND ';
      array_push($wheres, $country);
    }

    if ($registration_from) {
      $registration_from = date("Y-m-d", strtotime($registration_from));

      if (!$registration_to) {
        $sql .= 'registration >= ? AND ';
        array_push($wheres, $registration_from);
      } else {
        $registration_to = date("Y-m-d", strtotime($registration_to));

        $sql .= 'registration BETWEEN ? AND ? AND ';
        array_push($wheres, $registration_from);
        array_push($wheres, $registration_to);
      }
    }

    if ($sql == '') {
      $users = $this->load->model('User')->users();

      $usersformatted = $this->formatUsers($users);

      return json_encode($usersformatted);
    }

    $sql = substr($sql, 0, -4);

    $users = $this->load->model('User')->filter($sql, $wheres);

    if (!$users) {
      $msg = 'no users';
      return json_encode($msg);
    }

    $users_for_list = [];

    foreach ($users as $user) {
      $user->new = $this->isUserNew($user->registration);
      $user->country_icon = $this->countries($user->country);
      $user->registration = $this->changeFormatDate($user->registration);
      $user->last_login = $this->changeFormatDate($user->last_login);
      $users_for_list[] = $user;
    }

    $msg = $users_for_list;
    return json_encode($msg);
  }

  public function update()
  {
    $id = userId();

    $posts = $this->request->posts();
    $name = array_keys($posts)[0];
    $allows = $this->file->call('config/admin/users/pages/update.php');

    $columns = $this->file->fileContent('config/admin/users/columns.json');
    $columns = json_decode($columns);
    $table = $columns->$name->table;
    $column = $columns->$name;
    $filters = $columns->$name->filters;
    $value = ($posts[$name] == '') ? null : isset($filters->date) ? date('Y-m-d', strtotime($posts[$name])) : $posts[$name];
    $user_id_table_name = $column->user_id_table_name;

    $methods = $this->updateMethods([
      'id' => $id,
      'name' => $name,
      'allows' => $allows,
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

  private function updateMethods($args)
  {
    extract($args);
    return [
      'isUserFound' => [
        [$id],
        ['msg' => 'reload'],
      ],
      'checkPostParameters' => [
        [$name, $allows],
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

  private function checkPostParameters($name, $allows)
  {
    if (!in_array($name, $allows)) {
      return false;
    }
    return true;
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

  private function validatorPasses($filters, $name)
  {
    foreach ($filters as $func => $arg) {
      if (method_exists($this->validator, $func) == 1) {
        if (gettype($arg) === 'boolean') {
          if ($arg) {
            $this->validator->input($name)->$func();
          }
        } else {
          $this->validator->input($name)->$func($arg);
        }
      }
    }
    return $this->validator->passes();
  }

  public function new()
  {
    $countries = $this->countries('all', 'name');
    $context = [
      'countries' => $countries,
    ];
    return $this->view->render('admin/pages/users/new', $context);
  }

  public function add()
  {
    $msg = null;
    $posts = $this->request->posts();
    $names = array_keys($posts);
    $allows = $this->file->call('config/admin/users/pages/add.php');

    if (!array_equal($names, $allows)) {
      $msg = 'reload';
      return json_encode($msg);
    }

    $columns = $this->file->fileContent('config/admin/users/columns.json');
    $columns = json_decode($columns);
    $table = $this->load->model('User')->getTable();

    foreach ($names as $name) {
      $filters = $columns->$name->filters;

      foreach ($filters as $func => $arg) {
        if (method_exists($this->validator, $func) == 1) {
          if (gettype($arg) === 'boolean') {
            if ($arg) {
              $this->validator->input($name)->$func();
            }
          } else {
            $this->validator->input($name)->$func($arg);
          }
        }
      }
    }

    if ($this->validator->fails()) {
      $msg = $this->validator->getErrors();
      return json_encode($msg);
    }

    $factory = new Factory;

    $user_id = $factory->getMediumStrengthGenerator()->generateString(8, '0123456789');
    $code = $factory->getMediumStrengthGenerator()->generateString(20, '0123456789abcdefghijklmnopqrstuvwxyz');
    $username = $posts['username'];
    $fname = $posts['fname'];
    $lname = $posts['lname'];
    $gender = $posts['gender'];
    $birthday = date('Y-m-d', strtotime($posts['birthday']));
    $email = $posts['email'];
    $registration = $this->changeFormatDate(microtime(true), ['U.u', 'Y-m-d H:i:s']);

    $insertInUser = $this->db->data([
      'id' => $user_id,
      'code' => $code,
      'username' => $username,
      'fname' => $fname,
      'lname' => $lname,
      'gender' => $gender,
      'birthday' => $birthday,
      'email' => $email,
      'registration' => $registration,
    ])->insert($table);

    if (!$insertInUser) {
      $msg = 'reload';
      return json_encode($msg);
    }

    $country = $posts['country'] ? $posts['country'] : null;
    $state = $posts['state'] ? $posts['state'] : null;
    $city = $posts['city'] ? $posts['city'] : null;
    $zip = $posts['zip'] ? $posts['zip'] : null;
    $street = $posts['street'] ? $posts['street'] : null;
    $house_number = $posts['house_number'] ? $posts['house_number'] : null;
    $additional = $posts['additional'] ? $posts['additional'] : null;

    $insertInAddress = $this->db->data([
      'user_id' => $user_id,
      'country' => $country,
      'state' => $state,
      'city' => $city,
      'zip' => $zip,
      'street' => $street,
      'house_number' => $house_number,
      'additional' => $additional,
    ])->insert('address');

    $insertInActivity = $this->db->data([
      'user_id' => $user_id,
      'is_login' => 0,
    ])->insert('activity');

    if (!$insertInAddress || !$insertInActivity) {
      $msg = 'reload';
      return json_encode($msg);
    }
    $msg['success'] = $user_id;
    return json_encode($msg);
  }

  private function isUserNew($date)
  {
    if (!$date) {
      return;
    }

    $register_year = $this->changeFormatDate($date, ['Y-m-d H:i:s', 'Y']);
    $register_month = $this->changeFormatDate($date, ['Y-m-d H:i:s', 'm']);
    $register_day = $this->changeFormatDate($date, ['Y-m-d H:i:s', 'd']);

    $year = date('Y');
    $month = date('m');
    $day = date('d');

    $years = $year - $register_year;

    if ($years === 0) {
      $months = $month - $register_month;

      if ($months === 0) {
        $days = $day - $register_day;

        if ($days < 1) {
          return 1;
        }
      }
    }
    return 0;
  }
}
