<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('user')->id;

        return [
            'name'      => 'required|string|max:45',
            'email'     => [
                'required',
                'email',
                'max:45',
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'password'  => 'nullable|string|min:8|confirmed',
            'phone'     => 'nullable|string|max:45',
            'user_type' => 'required|in:admin,lawyer,client,staff',
            'is_active' => 'nullable|boolean',
            'roles'     => 'array',
            'roles.*'   => 'exists:roles,id',
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique'       => 'البريد الإلكتروني مستخدم من قبل.',
            'password.confirmed' => 'تأكيد كلمة المرور غير مطابق.',
            'password.min'       => 'كلمة المرور يجب أن تكون 8 أحرف على الأقل.',
        ];
    }
}