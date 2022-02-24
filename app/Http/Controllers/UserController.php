<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\User;

class UserController extends Controller
{
    public function index(Group $group)
    {
        return User::whereNotIn('id', $group->users()->pluck('user_id') )->get();
    }
}
