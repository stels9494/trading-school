<?php

namespace App\Http\Requests\Admin\UserController;

use Illuminate\Foundation\Http\FormRequest;

class Update extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'login' => 'required|string|unique:users,login',
            'firstname' => 'nullable|string|max:255',
            'lastname' => 'nullable|string|max:255',
            'patronymic' => 'nullable|string|max:255',
            'password' => 'required|string|between:3,20',
        ];
    }
}
