<?php

namespace App\Controllers\Admin\User\Traits;

use RandomLib\Factory as Factory;

trait Add
{
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

    if (!$this->checkPostParametersAdd($names, $allows)) {
      $msg = 'reload';
      return json_encode($msg);
    }
    $columns = $this->file->fileContent('config/admin/users/columns.json');
    $columns = json_decode($columns);
    $table = $this->load->model('User')->getTable();

    if (!$this->checkAddValidator($names, $columns)) {
      $msg = $this->validator->getErrors();
      return json_encode($msg);
    }
    extract($this->generateUserIdCode());

    $insertPersonalInfo = $this->insertPersonalInfo($posts, $table, $user_id, $code);

    if (!$insertPersonalInfo) {
      $msg = 'reload';
      return json_encode($msg);
    }
    $insertInAddress = $this->insertUserAddress($posts, $user_id);
    $insertUserActivities = $this->insertUserActivities($user_id);

    if (!$insertInAddress || !$insertUserActivities) {
      $msg = 'reload';
      return json_encode($msg);
    }
    $msg['success'] = $user_id;
    return json_encode($msg);
  }

  private function checkAddValidator($names, $columns)
  {
    foreach ($names as $name) {
      $filters = $columns->$name->filters;
      $this->validatorPasses($filters, $name);
    }
    if ($this->validator->fails()) {
      return false;
    }
    return true;
  }

  private function insertPersonalInfo($posts, $table, $user_id, $code)
  {
    $birthday = date('Y-m-d', strtotime($posts['birthday']));
    $registration = $this->changeFormatDate(microtime(true), ['U.u', 'Y-m-d H:i:s']);

    return $this->db->data([
      'id' => $user_id,
      'code' => $code,
      'username' => $posts['username'],
      'fname' => $posts['fname'],
      'lname' => $posts['lname'],
      'gender' => $posts['gender'],
      'birthday' => $birthday,
      'email' => $posts['email'],
      'registration' => $registration,
    ])->insert($table);
  }

  private function insertUserAddress($posts, $user_id)
  {
    return $this->db->data([
      'user_id' => $user_id,
      'country' => $posts['country'] ?: null,
      'state' => $posts['state'] ?: null,
      'city' => $posts['city'] ?: null,
      'zip' => $posts['zip'] ?: null,
      'street' => $posts['street'] ?: null,
      'house_number' => $posts['house_number'] ?: null,
      'additional' => $posts['additional'] ?: null,
    ])->insert('address');
  }

  private function insertUserActivities($user_id)
  {
    return $this->db->data([
      'user_id' => $user_id,
      'is_login' => 0,
    ])->insert('activity');
  }

  private function generateUserIdCode()
  {
    $factory = new Factory;
    $user_id = $factory->getMediumStrengthGenerator()->generateString(8, '0123456789');
    $code = $factory->getMediumStrengthGenerator()->generateString(20, '0123456789abcdefghijklmnopqrstuvwxyz');

    return [
      'user_id' => $user_id,
      'code' => $code,
    ];
  }

  private function checkPostParametersAdd($names, $allows)
  {
    if (!array_equal($names, $allows)) {
      return false;
    }
    return true;
  }
}
