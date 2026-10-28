<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AppointmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'patient_id' => ['required', 'integer', Rule::exists('users', 'id')->where('role', 'patient')],
            'doctor_id' => ['required', 'integer', 'exists:doctors,id'],
            'appointment_date' => ['required', 'date', 'after:now'],
            'status' => ['required', 'string', 'in:scheduled,completed,canceled,absent'],
            'notes' => ['nullable', 'string'],
            'canceled_at' => ['nullable', 'date'],
        ];
    }
}
