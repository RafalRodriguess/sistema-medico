<?php

namespace App\Http\Requests\API\AuthController;

use App\Usuario;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
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
            'nome' => ['required', 'string', 'max:255'],
            'telefone' => ['required'],
            'email' => ['required', 'string', 'email:rfc,dns'],
            'cpf' => ['required', 'string', 'cpf', Rule::unique('usuarios', 'cpf')],
            'data_nascimento' => ['required', 'date', 'before:now'],
            'password' => ['required', 'string', 'min:3'],
            'convenio_id' => ['nullable'],
            'device.manufacturer' => ['required', 'string'],
            'device.model' => ['required', 'string'],
            'device.platform' => ['required', 'string'],
            'device.uuid' => ['required_unless:device.platform,browser', 'string'],
        ];
    }

    public function messages(){
        return [
            'data_nascimento.before' => 'O valor informardo para a data de nascimento vdee ser inferior a hoje.',
        ];
    }
}
