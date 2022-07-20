<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ApiUserController extends Controller
{
    public function roles(User $user)
    {
        return $user->roles;
    }
}
