<?php

namespace App\Controllers\Admin\User;

use System\Controller as Controller;

use App\Controllers\Admin\User\Traits\Helpers;
use App\Controllers\Admin\User\Traits\Show;
use App\Controllers\Admin\User\Traits\Add;
use App\Controllers\Admin\User\Traits\Update;
use App\Controllers\Admin\User\Traits\Search;

class UserController extends Controller
{
  use Helpers, Show, Add, Update, Search {
    // Add::checkPostParameters as checkPostParametersAdd;
    // Update::checkPostParameters as checkPostParametersUpdate;
  }
}
