<?php

namespace App\Http\Requests\ComercialFretes\Retirada;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;

class CriarEnderecoRetirada extends FormRequest
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
        $regras = [
            'nome' => ['required'],
            'rua' => ['required'],
            'numero' => ['required'],
            'bairro' => ['required'],
            'cidade' => ['required'],
            'estado' => ['required'],
            'cep' => ['required'],
            'tipo_prazo_minimo' => ['required'],
            'tipo_prazo_maximo' => ['required'],
            'prazo_minimo' => ['required'],
            'prazo_maximo' => ['required'],
        ];

        //segunda
        if ($this->input('segunda')) {
            $regras['segunda'] = ['required'];
            $regras['inicio_segunda'] = ['required'];
            $regras['fim_segunda'] = ['required'];
        }

        //terca
        if ($this->input('terca')) {
            $regras['terca'] = ['required'];
            $regras['inicio_terca'] = ['required'];
            $regras['fim_terca'] = ['required'];
        }

        //quarta
        if ($this->input('quarta')) {
            $regras['quarta'] = ['required'];
            $regras['inicio_quarta'] = ['required'];
            $regras['fim_quarta'] = ['required'];
        }

        //quinta
        if ($this->input('quinta')) {
            $regras['quinta'] = ['required'];
            $regras['inicio_quinta'] = ['required'];
            $regras['fim_quinta'] = ['required'];
        }

        //sexta
        if ($this->input('sexta')) {
            $regras['sexta'] = ['required'];
            $regras['inicio_sexta'] = ['required'];
            $regras['fim_sexta'] = ['required'];
        }

        //sabado
        if ($this->input('sabado')) {
            $regras['sabado'] = ['required'];
            $regras['inicio_sabado'] = ['required'];
            $regras['fim_sabado'] = ['required'];
        }

        //domingo
        if ($this->input('domingo')) {
            $regras['domingo'] = ['required'];
            $regras['inicio_domingo'] = ['required'];
            $regras['fim_domingo'] = ['required'];
        }



        return $regras;
    }
}
