<?php

namespace App\Controllers\Admin\User\Traits;

trait Helpers
{
  private function formatUsers($users)
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
}
