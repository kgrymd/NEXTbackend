<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\MessageIndexRequest;
use App\Http\Resources\MessageResource;
use App\Models\ChatGroup;
use App\Models\Message;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MessageController extends Controller
{
    public function index(MessageIndexRequest $request, string $uuid)
    {
        /** @var \Illuminate\Pagination\CursorPaginator $messages */
        $messages = Message::with(['user', 'chat_group']) //chat_groupを追加　09192037
            ->whereHas('chat_group', function (Builder $builder) use ($uuid) {
                $builder->where('uuid', $uuid);
            })
            // ->orderBy('id', 'desc')
            ->orderBy('id', 'asc')->get();
        // ->cursorPaginate(20);
        // ->paginate(8);



        // return response()->json($messages);
        return MessageResource::collection($messages);
    }

    public function polling(Request $request, string $uuid)
    {
        $dateTimeString = Carbon::createFromTimestampMs(
            $request->input('ts')
        )->format('Y-m-d H:i:s.v');

        $messages = Message::with(['user'])
            ->whereHas('chat_group', function (Builder $builder) use ($uuid) {
                $builder->where('uuid', $uuid);
            })
            ->where('created_at', '>', $dateTimeString)
            ->orderBy('id', 'asc')
            ->get();

        // return response()->json($messages);
        return MessageResource::collection($messages);
    }

    public function store(Request $request, string $uuid)
    {

        $request->validate([
            'message_text' => 'required|max:1000',
        ]);

        $message = DB::transaction(function () use ($request, $uuid) {
            $message = Message::create([
                'chat_group_id' => ChatGroup::where('uuid', $uuid)->first()->id,
                'user_id' => Auth::id(),
                'message_text' => $request->input('message_text'),
            ]);


            $message->load(['user']);

            return $message;
        });

        // return response()->json($message);
        return new MessageResource($message);
    }

    public function destroy(Request $request, string $uuid, string $id)
    {
        $message = Message::find($id);
        $message->delete();

        return response()->noContent();
    }
}
