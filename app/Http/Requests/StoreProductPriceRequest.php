<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductPriceRequest extends FormRequest
{
    /**
     * The attributes that a product price accepts.
     *
     * @var list<string>
     */
    private const ALLOWED_ATTRIBUTES = [
        'currency_id',
        'price',
    ];

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'product_id' => ['prohibited'],
            'currency_id' => ['required', 'integer', 'exists:currencies,id'],
            'price' => ['required', 'numeric', 'min:0'],
        ];
    }

    /**
     * Reject attributes that do not belong to the product price.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $accepted = array_merge(self::ALLOWED_ATTRIBUTES, array_keys($this->rules()));

            foreach (array_diff(array_keys($this->all()), $accepted) as $attribute) {
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
            'product_id.prohibited' => 'El producto se obtiene de la ruta y no debe enviarse en el cuerpo de la solicitud.',
            'currency_id.required' => 'La moneda es obligatoria.',
            'currency_id.exists' => 'La moneda seleccionada no existe.',
            'price.required' => 'El precio es obligatorio.',
            'price.numeric' => 'El precio debe ser un valor numérico.',
            'price.min' => 'El precio no puede ser negativo.',
        ];
    }
}
