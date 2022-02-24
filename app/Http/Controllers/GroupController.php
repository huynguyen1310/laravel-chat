<?php

namespace App\Http\Controllers;

use App\Events\AddUserToGroup;
use App\Events\GroupCreated;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function store()
    {
        $group = Group::create(['name' => request('name')]);

        $users = collect(request('users'));
        $users->push(auth()->user()->id);

        $group->users()->attach($users);

        broadcast(new GroupCreated($group))->toOthers();

        return $group;
    }

    public function addUserToGroup(Group $group)
    {
        $users = collect(request('users'));
        $group->users()->attach($users);

        broadcast(new addUserToGroup($group))->toOthers();
        return $group;
    }
}
