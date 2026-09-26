<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $clientId = $this->route('client')->id;

        return [
            'client_kind' => 'required|in:individual,company',
            'national_id' => [
                'required',
                'string',
                'max:20',
                Rule::unique('clients', 'national_id')->ignore($clientId),
            ],
            'users_id' => [
                'required',
                'exists:users,id',
                Rule::unique('clients', 'users_id')->ignore($clientId),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'national_id.unique' => 'الرقم الوطني مسجل مسبقاً لموكل آخر.',
            'users_id.unique'    => 'هذا المستخدم مسجل بالفعل كموكل آخر.',
        ];
    }
}