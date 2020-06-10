<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Requests\Api\UserRequest;

class UsersController extends Controller
{
    public function store(UserRequest $request)
    {

        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'password' => $request->password,
        ]);

        return new UserResource($user);
    }
}
