<?php

namespace App\Models;

use System\Model;

class UsersGroupsModel extends Model
{
  protected $table = 'users_groups';

  public function get($id)
  {
    $userGroup = parent::get($id);

    if ($userGroup) {

      $userGroupInfos = $this->hasMany('UsersGroupPermissionsModel', $id);

      $userGroup->pages = [];

      foreach ($userGroupInfos as $userGroupInfo) {

        $userGroup->pages[] = $userGroupInfo->page;
      }

      return $userGroup;
    }
  }
}