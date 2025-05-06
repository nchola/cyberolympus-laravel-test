<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user() && auth()->user()->account_role === 'admin';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
        'first_name' => 'required|string|max:255',
        'last_name' => 'nullable|string|max:255',
        'address' => 'required|string|max:500',
        'phone' => 'required|string|max:20',
        'tanggal_registrasi' => 'required|date',
        ];
    }
}
