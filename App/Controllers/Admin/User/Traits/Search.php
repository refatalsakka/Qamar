<?php

namespace App\Controllers\Admin\User\Traits;

trait Search
{
  public function search()
  {
    $gets = $this->request->gets();

    if (empty($gets)) {
      $users = $this->load->model('User')->users();
      $usersformatted = $this->formatUsers($users);
      return json_encode($usersformatted);
    }
    list($sql, $wheres) = $this->generateSql($gets);

    if ($sql == '') {
      $users = $this->load->model('User')->users();
      $usersformatted = $this->formatUsers($users);
      return json_encode($usersformatted);
    }
    $users = $this->load->model('User')->filter($sql, $wheres);
    $msg = null;

    if (!$users) {
      $msg = 'no users';
      return json_encode($msg);
    }
    $msg = $this->formatUsers($users);
    return json_encode($msg);
  }

  private function active($active, $sql, $wheres)
  {
    if ($active && $active == '1') {
      $sql .= 'status = ? AND ';
      array_push($wheres, '2');
    }
    return [$sql, $wheres];
  }

  private function pending($pending, $sql, $wheres)
  {
    if ($pending && $pending == '1') {
      $sql .= 'status = ? AND ';
      array_push($wheres, '1');
    }
    return [$sql, $wheres];
  }

  private function inactive($inactive, $sql, $wheres)
  {
    if ($inactive && $inactive == '1') {
      $sql .= 'status = ? AND ';
      array_push($wheres, '0');
    }
    return [$sql, $wheres];
  }

  private function formatStatus($sql)
  {
    $count_status = substr_count($sql, 'status = ?');

    if ($count_status > 1) {
      $sql = str_replace('status = ? AND', 'status = ? OR', $sql);
      $sql = rtrim($sql, 'OR ');
      $sql = "( $sql )";
      $sql .= ' AND ';
    }
    return $sql;
  }

  private function online($online, $sql, $wheres)
  {
    if ($online && $online == '1') {
      $sql .= 'is_login = ? AND ';
      array_push($wheres, '1');
    }
    return [$sql, $wheres];
  }

  private function offline($offline, $sql, $wheres)
  {
    if ($offline && $offline == '1') {
      $sql .= 'is_login = ? AND ';
      array_push($wheres, '0');
    }
    return [$sql, $wheres];
  }

  private function formatLogin($sql)
  {
    $count_is_login = substr_count($sql, 'is_login = ?');

    if ($count_is_login > 1) {
      $sql = str_replace('is_login = ? AND', 'is_login = ? OR', $sql);
      $sql = rtrim($sql, 'OR ');
      $sql = "( $sql )";
      $sql .= ' AND ';
    }
    return $sql;
  }

  private function gender($gender, $sql, $wheres) {
    if ($gender) {
      $sql .= 'gender = ? AND ';
      array_push($wheres, $gender);
    }
    return [$sql, $wheres];
  }

  private function zip($zip, $sql, $wheres) {
    if ($zip) {
      $sql .= 'zip = ? AND ';
      array_push($wheres, $zip);
    }
    return [$sql, $wheres];
  }

  private function country($country, $sql, $wheres) {
    if ($country) {
      $sql .= 'country = ? AND ';
      array_push($wheres, $country);
    }
    return [$sql, $wheres];
  }

  private function registration($registration_from, $registration_to, $sql, $wheres) {
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
    return [$sql, $wheres];
  }

  private function generateSql($gets)
  {
    $sql = '';
    $wheres = [];

    list($sql, $wheres) = $this->active($gets['active'] ?? null, $sql, $wheres);
    list($sql, $wheres) = $this->pending($gets['pending'] ?? null, $sql, $wheres);
    list($sql, $wheres) = $this->inactive($gets['inactive'] ?? null, $sql, $wheres);
    $sql = $this->formatStatus($sql);
    list($sql, $wheres) = $this->online($gets['online'] ?? null, $sql, $wheres);
    list($sql, $wheres) = $this->offline($gets['offline'] ?? null, $sql, $wheres);
    $sql = $this->formatLogin($sql);
    list($sql, $wheres) = $this->gender($gets['gender'] ?? null, $sql, $wheres);
    list($sql, $wheres) = $this->zip($gets['zip'] ?? null, $sql, $wheres);
    list($sql, $wheres) = $this->country($gets['country'] ?? null, $sql, $wheres);
    list($sql, $wheres) = $this->registration($gets['registration_from'] ?? null, $gets['registration_to'] ?? null, $sql, $wheres);

    return [$sql ? substr($sql, 0, -4) : $sql, $wheres];
  }
}
