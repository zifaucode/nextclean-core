<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $customer = $this->route('customer');
        $customerId = $customer instanceof \App\Models\Customer ? $customer->id : $customer;

        $uniqueRule = Rule::unique('customers', 'member_code');
        if ($customerId) {
            $uniqueRule->ignore($customerId);
        }

        return [
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'member_code' => [
                'nullable',
                'string',
                'max:50',
                $uniqueRule
            ],
            'outlet_id' => 'nullable|integer',
        ];
    }
}
