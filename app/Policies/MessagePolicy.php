<?php

namespace App\Policies;

use App\Models\User;

class MessagePolicy extends Policy
{
    public function list(User $user)
    {
        $user = User::find(2);

        var_dump($user->can('message_list'));exit;
        return $user->can('message_list');
    }
}
