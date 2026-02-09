<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'avatar'   => ['nullable', 'image', 'mimes:jpeg,png'],
            'name'     => ['required', 'string', 'max:20'],
            'postcode' => ['required', 'regex:/^\d{3}-\d{4}$/'],
            'address'  => ['required', 'string'],
            'building' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'ユーザー名を入力してください',
            'name.max' => 'ユーザー名は20文字以内で入力してください',
            'postcode.required' => '郵便番号を入力してください',
            'postcode.regex' => '郵便番号は「123-4567」の形式で入力してください',
            'address.required' => '住所を入力してください',
            'avatar.mimes' => '画像はjpegまたはpng形式でアップロードしてください',
        ];
    }
}
