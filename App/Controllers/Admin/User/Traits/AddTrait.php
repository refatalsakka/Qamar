<?php

namespace App\Controllers\Admin\User\Traits;

use RandomLib\Factory as Factory;

trait AddTrait
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

    if (!$this->checkAddValidator($names)) {
      $msg = $this->validator->getErrors();
      return json_encode($msg);
    }
    extract($this->generateUserIdCode());

    $table = $this->load->model('User')->getTable();

    $insertPersonalInfo = $this->insertPersonalInfo($posts, $table, $user_id, $code);
    $insertInAddress = $this->insertUserAddress($posts, $user_id);
    $insertUserActivities = $this->insertUserActivities($user_id);

    if (!$insertPersonalInfo || !$insertInAddress || !$insertUserActivities) {
      $msg = 'reload';
      return json_encode($msg);
    }
    $msg['success'] = $user_id;
    return json_encode($msg);
  }

  private function checkAddValidator($names)
  {
    $columns = $this->getUserConfigColumns();

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

  private function setNullToUserAddressWhenEmpty($posts)
  {
    $array = [
      'country' => $posts['country'],
      'state' => $posts['state'],
      'city' => $posts['city'],
      'zip' => $posts['zip'],
      'street' => $posts['street'],
      'house_number' => $posts['house_number'],
      'additional' => $posts['additional'],
    ];
    foreach($array as $key => $value) {
      if (!$value) {
        $value = null;
      }
      $array[$key] = $value;
    }
    return $array;
  }

  private function insertUserAddress($posts, $user_id)
  {
    $posts = $this->setNullToUserAddressWhenEmpty($posts);

    return $this->db->data([
      'user_id' => $user_id,
      'country' => $posts['country'],
      'state' => $posts['state'],
      'city' => $posts['city'],
      'zip' => $posts['zip'],
      'street' => $posts['street'],
      'house_number' => $posts['house_number'],
      'additional' => $posts['additional'],
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
}
