<?php

namespace App\Controllers\Admin\User\Traits;


trait ShowTrait
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
}
