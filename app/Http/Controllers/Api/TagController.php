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
        // $tags = Tag::all();
        $tags = Tag::with('users')
            ->orderBy('created_at', 'asc')
            ->cursorPaginate(20);

        return response()->json($tags);
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
        // tag_userテーブルの中間テーブルに紐付けデータを作成。募集のタグでも共用するので一旦コメントアウト　2023/09/13
        // $storedTag->users()->sync([Auth::id()]);

        return response()->json($storedTag);
    }

    public function join(Request $request, string $id)
    {
        $tag = Tag::where('id', $id)->first();
        if (!$tag) {
            abort(404, 'Not Found.');
        }
        if ($tag->users()->find(Auth::id())) {
            throw ValidationException::withMessages([
                'id' => 'Already Joined.',
            ]);
        }

        $tag->users()->attach(Auth::id());

        return response()->noContent();
    }

    public function leave(Request $request, string $id)
    {
        $tag = Tag::where('id', $id)->first();
        if (!$tag) {
            abort(404, 'Not Found.');
        }
        if (!$tag->users()->find(Auth::id())) {
            throw ValidationException::withMessages([
                'id' => 'Already Left.',
            ]);
        }

        $tag->users()->detach(Auth::id());

        return response()->noContent();
    }
}
