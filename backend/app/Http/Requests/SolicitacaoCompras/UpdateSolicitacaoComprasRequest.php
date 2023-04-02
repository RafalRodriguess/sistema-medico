<?php

namespace App\Http\Requests\SolicitacaoCompras;

use App\Instituicao;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSolicitacaoComprasRequest extends FormRequest
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
        // $instituicao = Instituicao::findOrFail(request()->session()->get('instituicao'));
        return [
            'data_solicitacao' => ['required', 'date'],
            'data_maxima' =>['date'],
            'data_impressao' =>['date'],
            'setores_exames_id' => ['required'], 
       //   'cod_usuario' =>  ['required'],
             'nome_solicitante' => [
                'string',
                'required',
            ],
            'motivo_pedido_id' => ['required'],
            'comprador_id' => ['required'],
            'estoque_id' => ['required'],
       //   'sol_agrup' => ['required'],
            'servico_produto' => ['required'],
            'urgente' => ['nullable'],
            'solicitacao_opme' => ['required'],
        /*  'atendimento' => [
                'string',
                'required',
            ],
              'pre_int' => [
                'string',
                'required',
            ],
            'av_cirurgia' => [
                'string',
                'required',
            ], 
            'data_maxima_apoio_cotacao' => ['required', 'date'],  */
        ];
       
    }
}
