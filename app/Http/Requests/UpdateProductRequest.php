<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    /**
     * The attributes that the product accepts on update.
     *
     * @var list<string>
     */
    private const ALLOWED_ATTRIBUTES = [
        'name',
        'description',
        'price',
        'currency_id',
        'tax_cost',
        'manufacturing_cost',
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
     * PUT requires the complete resource: mandatory fields stay mandatory
     * and the id can never be sent in the request body.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id' => ['prohibited'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'currency_id' => ['required', 'integer', 'exists:currencies,id'],
            'tax_cost' => ['required', 'numeric', 'min:0'],
            'manufacturing_cost' => ['required', 'numeric', 'min:0'],
        ];
    }

    /**
     * Reject attributes that do not belong to the product.
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
            'id.prohibited' => 'No está permitido modificar el identificador del producto.',
            'name.required' => 'El nombre del producto es obligatorio.',
            'description.required' => 'La descripcion del producto es obligatoria.',
            'description.string' => 'La descripcion del producto debe ser texto',
            'name.max' => 'El nombre del producto no puede superar los 255 caracteres.',
            'price.required' => 'El precio del producto es obligatorio.',
            'price.numeric' => 'El precio debe ser un valor numérico.',
            'price.min' => 'El precio no puede ser negativo.',
            'currency_id.required' => 'La moneda es obligatoria.',
            'currency_id.exists' => 'La moneda seleccionada no existe.',
            'tax_cost.required' => 'El costo de impuestos es obligatorio.',
            'tax_cost.numeric' => 'El costo de impuestos debe ser un valor numérico.',
            'tax_cost.min' => 'El costo de impuestos no puede ser negativo.',
            'manufacturing_cost.required' => 'El costo de fabricación es obligatorio.',
            'manufacturing_cost.numeric' => 'El costo de fabricación debe ser un valor numérico.',
            'manufacturing_cost.min' => 'El costo de fabricación no puede ser negativo.',
        ];
    }
}
