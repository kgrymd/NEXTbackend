<?php

namespace App\Policies;

use App\Models\ChatGroup;
use App\Models\User;

class ChatGroupPolicy
{
    public function show(User $user, ChatGroup $chatGroup)
    {
        return $chatGroup->users->contains('id', $user->id);
    }
}
