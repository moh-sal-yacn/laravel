<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'      => 'required|string|max:45',
            'email'     => 'required|email|max:45|unique:users,email',
            'password'  => 'required|string|min:8|confirmed',
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
            'user_type.in'       => 'نوع المستخدم غير صحيح.',
            'roles.*.exists'     => 'أحد الأدوار المحددة غير موجود.',
        ];
    }
}