<?php

namespace App\Http\Requests\Api;

use App\Models\ChatGroup;
use Illuminate\Foundation\Http\FormRequest;

class MessagePollingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // $this->route('uuid') でパスパラメータを取得しつつ、chat_groupsテーブルからチャンネルデータを取得
        $chatGroup = ChatGroup::where('uuid', $this->route('uuid'))->first();
        // 先ほど作成したポリシークラスのshowメソッドに渡してチェック
        return $this->user()->can('show', $chatGroup);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            //
        ];
    }
}
