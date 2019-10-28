<?php

namespace App\Controllers\Admin;

use System\Controller as Controller;

class UsersController extends Controller
{
  public function index()
  {
    $users = $this->load->model('User')->users();

    $usersformatted = $this->formatUsers($users);

    $countries = $countries = array_keys($this->countries('all'));;

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

    $countries = array_keys($this->countries('all'));
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
      $user->country_Icon = $this->countries($user->country);
      $user->registration = $this->changeFormatDate($user->registration);
      $user->last_login = $this->changeFormatDate($user->last_login);

      $users_for_list[] = $user;
    }
    return $users_for_list;
  }

  public function filter() {

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
      $user->country_Icon = $this->countries($user->country);
      $user->registration = $this->changeFormatDate($user->registration);
      $user->last_login = $this->changeFormatDate($user->last_login);

      $users_for_list[] = $user;
    }

    $msg = $users_for_list;
    return json_encode($msg);
  }

  public function update()
  {
    $posts = $this->request->posts();

    $name = array_keys($posts)[0];

    $allows = $this->file->call('config/admin/users/update.php');

    if (!in_array($name, $allows)) {
      $msg = 'reload';
      return json_encode($msg);
    }

    $columns = $this->file->call('config/admin/users/columns.php');
    $table = $columns[$name]['table'];
    $column = $columns[$name];
    $filters = $columns[$name]['filters'];
    $value = $posts[$name];
    $id = userId();
    $user_id_table_name = $column['user_id_table_name'];

    $current_value = $this->db->select($name)->from($table)->where($user_id_table_name . ' = ?', [$id])->fetch()->$name;

    if ($current_value === strtolower($value)) {
      $msg = null;
      $msg['success'] = strtolower($value);
      return json_encode($msg);
    }

    foreach (array_keys($filters) as $filter) {
      if ($filters[$filter] === true) {
        $this->validator->input($name)->$filter();
      } else {
        $this->validator->input($name)->$filter($filters[$filter]);
      }
    }

    if ($this->validator->fails()) {
      $msg['error'] = $this->validator->getMsgs();
      return json_encode($msg);
    }

    $user = $this->load->model('User')->get($id);

    if (!$user) {
      $msg = 'reload';
      return json_encode($msg);
    }

    if (isset($filters['date'])) {
      $value = date('Y-m-d', strtotime($value));
    }

    if ($value == '') {
      $value = null;
    }

    $update = $this->db->data($name, $value)->where($user_id_table_name . ' = ?', $id)->update($table);

    if (!$update) {
      $msg = 'reload';
      return json_encode($msg);
    }

    $msg = null;
    $msg['success'] = 'no text';

    if ($value) {
      $msg['success'] = _e($value);

      if (isset($filters['date'])) {
        $msg['success'] = $this->changeFormatDate($value, ['Y-m-d', 'd M Y']);
      }
    }
    return json_encode($msg);
  }

  public function new()
  {
    $countries = $this->countries('all');

    $countries = array_keys($countries);

    $context = [
      'countries' => $countries,
    ];
    return $this->view->render('admin/pages/users/new', $context);
  }

  public function add()
  {
    $posts = $this->request->posts();

    $names = array_keys($posts);

    $allows = $this->file->call('config/admin/users/add.php');

    if (!array_equal($names, $allows)) {
      $msg = 'reload';
      return json_encode($msg);
    }

    $columns = $this->file->call('config/admin/users/columns.php');
    $table = $this->load->model('User')->getTable();

    foreach ($names as $name) {
      $filters = $columns[$name]['filters'];

      foreach (array_keys($filters) as $filter) {
        if ($filters[$filter] === true) {
          $this->validator->input($name)->$filter();
        } else {
          $this->validator->input($name)->$filter($filters[$filter]);
        }
      }
    }

    if ($this->validator->fails()) {
      $msg = $this->validator->getMsgs();
      return json_encode($msg);
    }

    $user_id = substr($this->changeFormatDate(microtime(true), ['U.u', 'us']) * rand(), 0, 6);
    $code = $this->changeFormatDate(microtime(true), ['U.u', 'uiYdsmH']);
    $username = $posts['username'];
    $fname = $posts['fname'];
    $lname = $posts['lname'];
    $gender = $posts['gender'];
    $birthday = date('Y-m-d', strtotime($posts['birthday']));
    $email = $posts['email'];
    $registration =  $this->changeFormatDate(microtime(true), ['U.u', 'Y-m-d H:i:s']);

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
    if (!$date) {return;}

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

        if ($days < 4) {
          return 1;
        }
      }
    }
    return 0;
  }
}
