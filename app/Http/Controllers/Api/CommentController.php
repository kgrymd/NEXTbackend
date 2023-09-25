<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            // 'user_id' => 'required|exists:users,id',
            'recruitment_id' => 'required|exists:recruitments,id',
            'comment_text' => 'required|max:1000',
        ]);

        $comment = Comment::create([
            // 'user_id' => $request->user_id,
            'user_id' => $request->user()->id,
            'recruitment_id' => $request->recruitment_id,
            'comment_text' => $request->comment_text,
        ]);

        return response()->json($comment, 201);
    }
}
