<?php

namespace App\Controllers\Admin;

use System\Controller as Controller;
use DateTime;

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
    $user->birthday = $this->changeFormatDate($user->birthday, ['Y-m-d', 'd M Y'], false);

    $context = [
      'user' => $user,
    ];
    return $this->view->render('admin/pages/users/user', $context);
  }

  private function isUserNew($date)
  {
    if (!$date) {return;}

    $register = DateTime::createFromFormat('Y-m-d H:i:s', $date);
    $register_year = $register->format('Y');
    $register_month = $register->format('m');
    $register_day = $register->format('d');

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

  private function changeFormatDate($date, array $format = ['Y-m-d', 'd M Y'], $time = [' H:i:s', ' | H:i'])
  {
    $time = $time ? $time : ['', ''];

    return $date ? DateTime::createFromFormat("$format[0]$time[0]", $date)->format("$format[1]$time[1]") : null;
  }

  private function setCoutrnIcon($country)
  {
    $countries_icons = $this->file->call('config/icons.php')['flags'];

    return($country && isset($countries_icons[$country])) ? $countries_icons[$country] : null;
  }

  public function update()
  {
    $posts = $this->request->posts();

    $inputs = array_keys($posts);

    $allows = $this->file->call('config/users/update.php');

    $id = getLastParameter($this->request->baseUrl());

    if (!isset($allows[$inputs[0]])) {

      $msg = 'reload';
      return json_encode($msg);
    }

    $column = $inputs[0];
    $table = $allows[$column]['table'];

    if (isset($allows[$column]['type'])) {
      $type = $allows[$column]['type'];
      $this->validator->input($column)->$type();
    }
    if (isset($allows[$column]['require'])) {
      $this->validator->input($column)->require();
    }
    if (isset($allows[$column]['unique'])) {
      $this->validator->input($column)->unique([$table, $column]);
    }
    if (isset($allows[$column]['noUmlaut'])) {
      $this->validator->input($column)->noUmlaut();
    }
    if (isset($allows[$column]['email'])) {
      $this->validator->input($column)->email();
    }
    if (isset($allows[$column]['date'])) {
      $dateFormat = $allows[$column]['date'];
      $this->validator->input($column)->date($dateFormat['show']);

      if (isset($allows[$column]['dateRange'])) {
        $this->validator->input($column)->dateRange($dateFormat['show'], $allows[$column]['dateRange']);
      }
    }
    if (isset($allows[$column]['minLen'])) {
      $this->validator->input($column)->minLen($allows[$column]['minLen']);
    }
    if (isset($allows[$column]['maxLen'])) {
      $this->validator->input($column)->maxLen($allows[$column]['maxLen']);
    }
    if (isset($allows[$column]['containJust'])) {
      $this->validator->input($column)->containJust($allows[$column]['containJust']);
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

    $value = $posts[$column];
    $user_id_table_name = $allows[$column]['user_id_table_name'];

    $value = strtolower($value);

    if (isset($allows[$column]['date'])) {

      $value = date($dateFormat['insert'], strtotime($value));
    }

    if ($value == '') {

      $value = null;
    }

    $update = $this->db->data($column, $value)->where($user_id_table_name . ' = ?', $id)->update($table);

    if (!$update) {

      $msg = 'reload';
      return json_encode($msg);
    }

    $msg = null;

    $msg['success'] = 'no text';

    if ($value) {

      $msg['success'] = $value;

      if (isset($allows[$column]['date'])) {

        $msg['success'] = $this->changeFormatDate($value, [$dateFormat['insert'], $dateFormat['show']], false);
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
    return json_encode('hi');
  }
}
