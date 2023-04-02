<?php

namespace App\Http\Requests\ComercialFretes\Entrega;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;
use App\Fretes;

class CriarFiltroEntregaRequest extends FormRequest
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


        $frete = Fretes::whereHas('comercial', function ($q) {
            $q->where('tipo_frete', 'entrega');
            $q->whereNull('fretes.deleted_at');
        })->get()->first();


        switch ($frete->tipo_filtro) {
            case 'cidade':
                return [
                    'cidade' => ['required'],
                    'valor' => ['required'],
                    'valor_minimo' => ['required'],
                    'tipo_prazo' => ['required'],
                    'prazo_minimo' => ['required'],
                    'prazo_maximo' => ['required'],
                ];
                break;
            case 'cidade_bairro':
                return [
                    'cidade' => ['required'],
                    'bairro' => ['required'],
                    'valor' => ['required'],
                    'valor_minimo' => ['required'],
                    'tipo_prazo' => ['required'],
                    'prazo_minimo' => ['required'],
                    'prazo_maximo' => ['required'],
                ];
            case 'faixa_cep':
                break;
            case 'cep_unico':
                break;
        }
    }
}
