<?php

namespace App\Policies;

use App\Models\Recruitment;
use App\Models\User;

class RecruitmentPolicy
{
    public function update(User $user, Recruitment $recruitment)
    {
        return $recruitment->creator->id === $user->id;
    }
}
