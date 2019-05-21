<?php

namespace App\Models;

use System\Model;

class UsersGroupsModel extends Model
{
    protected $table = 'users_groups';

    public function get($id)
    {
        $userGroup = parent::get($id);

        pre($userGroup);
    }
}