<?php

namespace App\Controllers\Admin\User;

use System\Controller as Controller;

use App\Controllers\Admin\User\Traits\HelpersTrait as Helpers;
use App\Controllers\Admin\User\Traits\ShowTrait as Show;
use App\Controllers\Admin\User\Traits\AddTrait as Add;
use App\Controllers\Admin\User\Traits\UpdateTrait as Update;
use App\Controllers\Admin\User\Traits\SearchTrait as Search;

class UserController extends Controller
{
  use Helpers, Show, Add, Update, Search;
}
