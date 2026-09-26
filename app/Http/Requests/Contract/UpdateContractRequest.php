<?php

namespace App\Http\Requests\Contract;

use Illuminate\Foundation\Http\FormRequest;

class UpdateContractRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'contract_type'   => 'required|string|max:45',
            'contract_status' => 'required|in:نشط,منتهي,ملغي',
            'parties'         => 'required|string|max:2000',
            'contract_value'  => 'required|numeric|min:0|max:99999999.99',
            'signed_at'       => 'required|date|before_or_equal:today',
            'clients_id'      => 'required|exists:clients,id',
            'users_id'        => 'required|exists:users,id',
        ];
    }

    public function messages(): array
    {
        return [
            'contract_type.max'         => 'نوع العقد يجب ألا يزيد عن 45 حرفاً.',
            'contract_status.in'        => 'حالة العقد غير صحيحة.',
            'parties.required'          => 'أطراف العقد مطلوبة.',
            'contract_value.min'        => 'قيمة العقد لا يمكن أن تكون سالبة.',
            'signed_at.before_or_equal' => 'تاريخ التوقيع لا يمكن أن يكون في المستقبل.',
        ];
    }
}