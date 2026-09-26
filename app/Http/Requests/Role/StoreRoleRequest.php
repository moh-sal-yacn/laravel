<?php

namespace App\Http\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'role_name'     => 'required|string|max:45|unique:roles,role_name',
            'description'   => 'nullable|string|max:1000',
            'permissions'   => 'array',
            'permissions.*' => 'exists:permissions,id',
        ];
    }

    public function messages(): array
    {
        return [
            'role_name.unique'    => 'اسم الدور مستخدم مسبقاً، اختر اسماً آخر.',
            'role_name.required'  => 'اسم الدور مطلوب.',
            'role_name.max'       => 'اسم الدور يجب ألا يزيد عن 45 حرفاً.',
            'permissions.*.exists'=> 'إحدى الصلاحيات المحددة غير موجودة.',
        ];
    }
}