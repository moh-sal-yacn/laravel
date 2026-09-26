<?php

namespace App\Http\Requests\Case;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $caseId = $this->route('case')->id;

        return [
            'case_number' => [
                'required',
                'string',
                'max:50',
                Rule::unique('cases', 'case_number')->ignore($caseId),
            ],
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
            'case_number.unique' => 'رقم القضية مستخدم مسبقاً من قبل قضية أخرى.',
            'case_status.in'     => 'حالة القضية غير صحيحة.',
        ];
    }
}