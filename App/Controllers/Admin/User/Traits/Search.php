<?php

namespace App\Controllers\Admin\User\Traits;

trait Search
{
  public function search()
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
}
