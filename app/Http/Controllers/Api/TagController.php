<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TagController extends Controller
{


    public function index(Request $request)
    {
        $channels = Tag::with('users')
            ->orderBy('created_at', 'asc')
            ->paginate(20);

        return response()->json($channels);
    }

    public function store(Request $request)
    {

        // こんな風にリクエストパラメータを受け取る。
        $name = $request->name; // $request->input('name') でもOK

        // Eloquentを使ってDBに保存
        $storedTag = Tag::create([
            'name' => $name,
        ]);

        // タグを作った人はそのままタグに参加している状態を作りたいので、
        // tag_userテーブルの中間テーブルに紐付けデータを作成
        $storedTag->users()->sync([Auth::id()]);

        return response()->json($storedTag);
    }

    public function join(Request $request, string $id)
    {
        $channel = Tag::where('id', $id)->first();
        if (!$channel) {
            abort(404, 'Not Found.');
        }
        if ($channel->users()->find(Auth::id())) {
            throw ValidationException::withMessages([
                'id' => 'Already Joined.',
            ]);
        }

        $channel->users()->attach(Auth::id());

        return response()->noContent();
    }

    public function leave(Request $request, string $id)
    {
        $channel = Tag::where('id', $id)->first();
        if (!$channel) {
            abort(404, 'Not Found.');
        }
        if (!$channel->users()->find(Auth::id())) {
            throw ValidationException::withMessages([
                'id' => 'Already Left.',
            ]);
        }

        $channel->users()->detach(Auth::id());

        return response()->noContent();
    }
}
