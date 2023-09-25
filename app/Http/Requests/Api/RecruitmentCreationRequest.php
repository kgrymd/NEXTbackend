<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class RecruitmentCreationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
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
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'youtube_url' => 'nullable|url', // url バリデーションルールは、入力が有効なURLフォーマットであることを検証する
            'reference_url' => 'nullable|url',
            'prefecture_id' => 'nullable|integer|exists:prefectures,id',
            'age_from' => 'nullable|integer|min:0',
            'age_to' => 'nullable|integer|gte:age_from',
            'min_people' => 'nullable|integer|min:1',
            'max_people' => 'nullable|integer|min:1',
            'start_date' => 'required|date', // 'start_date' => 'required|date|after_or_equal:today', でも良いがテストデータ用に一旦
            'end_date' => 'required|date|after_or_equal:start_date',
            'images.*' => 'nullable|image|max:2048', // 複数の画像ファイル、各ファイルは2MBまで
            'tags' => 'nullable|array'
        ];
    }
}
