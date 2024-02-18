<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{

	protected $rules = [
		'name' => ['required', 'string'],
		'description' => ['required', 'string'],
		'stock' => ['required', 'numeric'],
		'price' => ['required', 'numeric'],
		'category_id' => ['required', 'exists:categories,id'],
		'file' => ['required', 'image']
	];

	public function authorize()
	{
		return true;
	}

	public function rules()
	{
		return  $this->rules;
	}

	public function messages()
	{
		return [
			'name.required' => 'El titulo es requerido.',
			'name.string' => 'El nombre debe de ser valido.',
			'description.required' => 'La descripcion es requerida.',
			'description.string' => 'La descripcion debe de ser valida.',
			'stock.required' => 'La cantidad es requerida.',
			'stock.numeric' => 'La cantidad debe de ser un numero valido.',
			'price.required' => 'El precio es requerido.',
			'price.numeric' => 'El precio debe de ser un numero valido.',
			'category_id.required' => 'La categoria es requerida.',
			'file.required' => 'La imagen es requerida.',
			'file.image' => 'El archivo debe de ser una imagen valida.',
		];
	}
}
