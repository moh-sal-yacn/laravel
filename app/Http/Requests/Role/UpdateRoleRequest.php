<?php

namespace App\Http\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $roleId = $this->route('role')->id;

        return [
            'role_name' => [
                'required',
                'string',
                'max:45',
                Rule::unique('roles', 'role_name')->ignore($roleId),
            ],
            'description'   => 'nullable|string|max:1000',
            'permissions'   => 'array',
            'permissions.*' => 'exists:permissions,id',
        ];
    }

    public function messages(): array
    {
        return [
            'role_name.unique' => 'اسم الدور مستخدم مسبقاً من قبل دور آخر.',
        ];
    }
}