<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $employeeId = $this->route('employee') ? $this->route('employee')->id : null;

        return [
            'user_id' => 'nullable|integer',
            'outlet_id' => 'nullable|integer',
            'employee_code' => [
                'required',
                'string',
                'max:50',
                $employeeId ? \Illuminate\Validation\Rule::unique('employees', 'employee_code')->ignore($employeeId) : \Illuminate\Validation\Rule::unique('employees', 'employee_code'),
            ],
            'position' => 'nullable|string|max:100',
            'salary' => 'nullable|numeric|min:0',
        ];
    }

    protected function prepareForValidation()
    {
        if (empty($this->employee_code)) {
            do {
                $code = 'EMP-' . strtoupper(bin2hex(random_bytes(3)));
            } while (\App\Models\Employee::where('employee_code', $code)->exists());

            $this->merge([
                'employee_code' => $code,
            ]);
        }
    }
}
