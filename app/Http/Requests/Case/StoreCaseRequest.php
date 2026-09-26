<?php

namespace App\Http\Requests\Case;

use Illuminate\Foundation\Http\FormRequest;

class StoreCaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'case_number'   => 'required|string|max:50|unique:cases,case_number',
            'case_title'    => 'required|string|max:255',
            'case_status'   => 'required|in:قيد النظر,مؤجلة,منتهية,مؤرشفة',
            'opened_at'     => 'required|date',
            'description'   => 'nullable|string|max:2000',
            'clients_id'    => 'required|exists:clients,id',
            'courts_id'     => 'required|exists:courts,id',
            'categories_id' => 'nullable|exists:categories,id',
        ];
    }

    public function messages(): array
    {
        return [
            'case_number.unique'   => 'رقم القضية مستخدم مسبقاً، اختر رقماً آخر.',
            'case_status.in'       => 'حالة القضية غير صحيحة.',
            'clients_id.exists'    => 'الموكل المحدد غير موجود.',
            'courts_id.exists'     => 'المحكمة المحددة غير موجودة.',
            'categories_id.exists' => 'التصنيف المحدد غير موجود.',
        ];
    }
}