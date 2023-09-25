<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ChatGroupResource;
use App\Models\ChatGroup;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ChatGroupController extends Controller
{

    public function index(Request $request)
    {
        $chat_groups = ChatGroup::with('users')
            ->orderBy('created_at', 'asc')
            ->paginate(20);

        // return response()->json($chat_groups);
        return ChatGroupResource::collection($chat_groups);
    }

    public function show($uuid)
    {
        $chatGroup = ChatGroup::where('uuid', $uuid)->first();

        if (!$chatGroup) {
            return response()->json(['message' => 'Chat group not found'], 404);
        }

        return response()->json($chatGroup);
    }


    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:50',
        ]);

        $name = $request->name; // $request->input('name') でもOK ここを募集のタイトルにするが、後々の追加機能を考慮し逆にフロント側で募集のタイトルをnameとして送る

        // Eloquentを使ってDBに保存
        $storedChatGroup = ChatGroup::create([
            'name' => $name,
            'uuid' => \Str::uuid(),
        ]);

        // 現在の時間を取得
        $now = Carbon::now();

        // ChatGroupを作った人はそのままChatGroupに参加している状態を作りたい
        // つまり、chat_group_userテーブルの中間テーブルに紐付けデータを作成
        // $storedChatGroup->users()->sync([Auth::id()]);
        $storedChatGroup->users()->sync([
            Auth::id() => [
                'created_at' => $now,
                'updated_at' => $now
            ]
        ]);

        // return response()->json($storedChatGroup);
        return new ChatGroupResource($storedChatGroup);
    }

    public function join(Request $request, string $uuid)
    {

        $request->validate([
            'uuid' => 'required|uuid',
        ]);

        $chat_group = ChatGroup::where('uuid', $uuid)->first();
        if (!$chat_group) {
            abort(404, 'Not Found.');
        }
        if ($chat_group->users()->find(Auth::id())) {
            throw ValidationException::withMessages([
                'uuid' => 'Already Joined.',
            ]);
        }

        $chat_group->users()->attach(Auth::id());

        return response()->noContent();
    }

    public function leave(Request $request, string $uuid)
    {

        $request->validate([
            'uuid' => 'required|uuid',
        ]);

        $chat_group = ChatGroup::where('uuid', $uuid)->first();
        if (!$chat_group) {
            abort(404, 'Not Found.');
        }
        if (!$chat_group->users()->find(Auth::id())) {
            throw ValidationException::withMessages([
                'uuid' => 'Already Left.',
            ]);
        }

        $chat_group->users()->detach(Auth::id());

        return response()->noContent();
    }
}
