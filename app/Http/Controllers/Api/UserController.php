<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PublicUserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show($id)
    {
        // UserモデルをIDで検索
        $user = User::with('tags')->findOrFail($id);

        return new PublicUserResource($user);
    }
}
