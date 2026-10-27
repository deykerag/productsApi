<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class StoreCurrencyRequest extends FormRequest
{
    /**
     * The attributes that the currency accepts.
     *
     * @var list<string>
     */
    private const ALLOWED_ATTRIBUTES = [
        'name',
        'code',
        'symbol',
        'exchange_rate',
    ];

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Normalize the currency code to uppercase before validation.
     */
    protected function prepareForValidation(): void
    {
        if (is_string($this->input('code'))) {
            $this->merge(['code' => Str::upper($this->input('code'))]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'size:3', 'alpha', 'unique:currencies,code'],
            'symbol' => ['required', 'string', 'max:10'],
            'exchange_rate' => ['required', 'numeric', 'gt:0'],
        ];
    }

    /**
     * Reject attributes that do not belong to the currency.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            foreach (array_diff(array_keys($this->all()), self::ALLOWED_ATTRIBUTES) as $attribute) {
                $validator->errors()->add($attribute, "El campo {$attribute} no está permitido.");
            }
        });
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'El nombre de la moneda es obligatorio.',
            'name.max' => 'El nombre de la moneda no puede superar los 255 caracteres.',
            'code.required' => 'El código de la moneda es obligatorio.',
            'code.size' => 'El código de la moneda debe tener exactamente 3 caracteres.',
            'code.alpha' => 'El código de la moneda solo puede contener letras.',
            'code.unique' => 'Ya existe una moneda con este código.',
            'symbol.required' => 'El símbolo de la moneda es obligatorio.',
            'symbol.max' => 'El símbolo de la moneda no puede superar los 10 caracteres.',
            'exchange_rate.required' => 'El tipo de cambio es obligatorio.',
            'exchange_rate.numeric' => 'El tipo de cambio debe ser un valor numérico.',
            'exchange_rate.gt' => 'El tipo de cambio debe ser mayor que cero.',
        ];
    }
}
