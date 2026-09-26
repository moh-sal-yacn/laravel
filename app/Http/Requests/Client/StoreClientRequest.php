<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

class StoreClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'client_kind' => 'required|in:individual,company',
            'national_id' => 'required|string|max:20|unique:clients,national_id',
            'users_id'    => 'required|exists:users,id|unique:clients,users_id',
        ];
    }

    public function messages(): array
    {
        return [
            'client_kind.in'     => 'نوع الموكل يجب أن يكون فرداً أو شركة.',
            'national_id.unique' => 'الرقم الوطني مسجل مسبقاً لموكل آخر.',
            'users_id.unique'    => 'هذا المستخدم مسجل بالفعل كموكل.',
            'users_id.exists'    => 'المستخدم المحدد غير موجود.',
        ];
    }
}