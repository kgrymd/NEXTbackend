<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserDataRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // return false;
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'iconFile' => ['nullable', 'image', 'max:5120'], // 画像で、最大5MB
            'name' => ['nullable', 'string', 'max:255'], // 文字列で、最大長255
            'age' => ['nullable', 'integer', 'between:0,150'], // 整数で、0から150の間
            'introduction' => ['nullable', 'string', 'max:500'], // 文字列で、最大長500
            'prefecture_id' => ['nullable', 'integer', 'exists:prefectures,id'], // prefecturesテーブルのidが存在するか
            'tags' => ['nullable', 'array'], // 配列
            'tags.*' => ['integer', 'exists:tags,id'], // tagsテーブルのidが存在するか
        ];
    }
}
