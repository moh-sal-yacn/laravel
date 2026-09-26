<?php

namespace App\Http\Requests\Permission;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePermissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'permission_name' => [
                'required',
                'string',
                'max:45',
                Rule::unique('permissions', 'permission_name')
                    ->where('module', $this->input('module')),
            ],
            'module' => 'required|string|max:45',
        ];
    }

    public function messages(): array
    {
        return [
            'permission_name.required' => 'اسم الصلاحية مطلوب.',
            'permission_name.unique'   => 'توجد صلاحية بنفس الاسم في نفس الوحدة.',
            'permission_name.max'      => 'اسم الصلاحية يجب ألا يزيد عن 45 حرفاً.',
            'module.required'          => 'الوحدة مطلوبة.',
        ];
    }
}