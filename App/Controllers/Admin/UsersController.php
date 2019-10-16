<?php

namespace App\Controllers\Admin;

use System\Controller as Controller;

class UsersController extends Controller
{
  public function index()
  {
    $users = $this->load->model('User')->users();

    foreach ($users as $user) {

      $user->new = $this->isUserNew($user->registration);
      $user->coutrny_Icon = $this->setCoutrnIcon($user->country);
      $user->registration = $this->changeFormatDate($user->registration);

      $user->last_login = $this->changeFormatDate($user->last_login);

      $users_modify[] = $user;
    }

    $users = $users_modify;

    $context = [
      'users' => $users,
    ];
    return $this->view->render('admin/pages/users/users', $context);
  }

  public function user($id)
  {
    $id = getLastParameter($this->request->baseUrl());

    $user = $this->load->model('User')->user($id);

    $user->new = $this->isUserNew($user->registration);
    $user->registration = $this->changeFormatDate($user->registration);
    $user->last_login = $this->changeFormatDate($user->last_login);
    $user->last_logout = $this->changeFormatDate($user->last_logout);
    $user->birthday = $this->changeFormatDate($user->birthday, ['Y-m-d', 'd M Y']);

    $context = [
      'user' => $user,
    ];
    return $this->view->render('admin/pages/users/user', $context);
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

    if (isset($columns[$name]['type'])) {
      $type = $columns[$name]['type'];
      $this->validator->input($name)->$type();
    }
    if (isset($columns[$name]['require'])) {
      $this->validator->input($name)->require();
    }
    if (isset($columns[$name]['unique'])) {
      $this->validator->input($name)->unique([$table, $name]);
    }
    if (isset($columns[$name]['noUmlaut'])) {
      $this->validator->input($name)->noUmlaut();
    }
    if (isset($columns[$name]['email'])) {
      $this->validator->input($name)->email();
    }
    if (isset($columns[$name]['date'])) {
      $dateFormat = $columns[$name]['date'];
      $this->validator->input($name)->date($dateFormat['show']);

      if (isset($columns[$name]['dateRange'])) {
        $this->validator->input($name)->dateRange($dateFormat['show'], $columns[$name]['dateRange']);
      }
    }
    if (isset($columns[$name]['minLen'])) {
      $this->validator->input($name)->minLen($columns[$name]['minLen']);
    }
    if (isset($columns[$name]['maxLen'])) {
      $this->validator->input($name)->maxLen($columns[$name]['maxLen']);
    }
    if (isset($columns[$name]['containJust'])) {
      $this->validator->input($name)->containJust($columns[$name]['containJust']);
    }

    if ($this->validator->fails()) {

      $msg['error'] = $this->validator->getMsgs();
      return json_encode($msg);
    }

    $id = getLastParameter($this->request->baseUrl());

    $user = $this->load->model('User')->get($id);

    if (!$user) {

      $msg = 'reload';
      return json_encode($msg);
    }

    $value = $posts[$name];
    $user_id_table_name = $columns[$name]['user_id_table_name'];

    $value = strtolower($value);

    if (isset($columns[$name]['date'])) {

      $value = date($dateFormat['insert'], strtotime($value));
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

      $msg['success'] = $value;

      if (isset($columns[$name]['date'])) {

        $msg['success'] = $this->changeFormatDate($value, [$dateFormat['insert'], $dateFormat['show']]);
      }
    }

    return json_encode($msg);
  }

  public function new()
  {
    return $this->view->render('admin/pages/users/new', []);
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

      if (isset($columns[$name]['require'])) {
        $this->validator->input($name)->require();
      }
      if (isset($columns[$name]['type'])) {
        $type = $columns[$name]['type'];
        $this->validator->input($name)->$type();
      }
      if (isset($columns[$name]['date'])) {
        $dateFormat = $columns[$name]['date'];
        $this->validator->input($name)->date($dateFormat['show']);

        if (isset($columns[$name]['dateRange'])) {
          $this->validator->input($name)->dateRange($dateFormat['show'], $columns[$name]['dateRange']);
        }
      }
      if (isset($columns[$name]['unique'])) {
        $this->validator->input($name)->unique([$table, $name]);
      }
      if (isset($columns[$name]['noUmlaut'])) {
        $this->validator->input($name)->noUmlaut();
      }
      if (isset($columns[$name]['minLen'])) {
        $this->validator->input($name)->minLen($columns[$name]['minLen']);
      }
      if (isset($columns[$name]['maxLen'])) {
        $this->validator->input($name)->maxLen($columns[$name]['maxLen']);
      }
      if (isset($columns[$name]['containJust'])) {
        $this->validator->input($name)->containJust($columns[$name]['containJust']);
      }
    }

    if ($this->validator->fails()) {

      $msg = $this->validator->getMsgs();
      return json_encode($msg);
    }

    $user_id =  $this->changeFormatDate(microtime(true), ['U.u', 'us']);
    $code = $this->changeFormatDate(microtime(true), ['U.u', 'uiYdsmH']);
    $username = $posts['username'];
    $fname = $posts['fname'];
    $lname = $posts['lname'];
    $sex = $posts['sex'];
    $birthday = date($dateFormat['insert'], strtotime($posts['birthday']));
    $email = $posts['email'];
    $registration =  $this->changeFormatDate(microtime(true), ['U.u', 'Y-m-d H:i:s']);

    $insertInUser = $this->db->data([
      'id' => $user_id,
      'code' => $code,
      'username' => $username,
      'fname' => $fname,
      'lname' => $lname,
      'sex' => $sex,
      'birthday' => $birthday,
      'email' => $email,
      'registration' => $registration,
    ])->insert($table);

    if (!$insertInUser) {

      $msg = $this->validator->getMsgs();
      return json_encode($msg);
    }

    $country = $posts['country'] ? $posts['country'] : null;
    $state = $posts['state'] ? $posts['state'] : null;
    $city = $posts['city'] ? $posts['city'] : null;
    $street = $posts['street'] ? $posts['street'] : null;
    $zip = $posts['zip'] ? $posts['zip'] : null;
    $additional = $posts['additional'] ? $posts['additional'] : null;

    $insertInAddress = $this->db->data([
      'user_id' => $user_id,
      'country' => $country,
      'state' => $state,
      'city' => $city,
      'street' => $street,
      'zip' => $zip,
      'additional' => $additional,
    ])->insert('address');

    $insertInActivity = $this->db->data([
      'user_id' => $user_id,
      'is_login' => 0,
    ])->insert('activity');

    if (!$insertInAddress || !$insertInActivity) {

      $msg = $this->validator->getMsgs();
      return json_encode($msg);
    }
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

  private function setCoutrnIcon($country)
  {
    $countries_icons = $this->file->call('config/icons.php')['flags'];

    return($country && isset($countries_icons[$country])) ? $countries_icons[$country] : null;
  }
}
