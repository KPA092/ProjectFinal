<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{

	public function authorize()
	{
		return true;
	}


	public function rules()
	{
		$rules = [
			'name' => ['required', 'string'],
			'last_name' => ['required', 'string'],
			'number_id' => ['required', 'numeric'],
			'email' => ['required', 'email'],
			'password' => ['confirmed', 'string', 'min:8'],
		];

		if ($this->method() == 'POST') {
			array_push($rules['number_id'], 'unique:users,number_id');
			array_push($rules['email'], 'unique:users,email');
			array_push($rules['password'], 'required');
		} else {
			array_push($rules['number_id'], 'unique:users,number_id,' . $this->user->id);
			array_push($rules['email'], 'unique:users,email,' . $this->user->id);
			array_push($rules['password'], 'nullable');
		}

		return $rules;
	}

	public function messages()
	{
		return [
			'name.required' => 'El nombre es requerido',
			'name.string' => 'El nombre debe de ser valido',
		];
	}
}
