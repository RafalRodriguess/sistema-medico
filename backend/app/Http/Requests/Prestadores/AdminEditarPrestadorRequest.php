<?php

namespace App\Http\Requests\Prestadores;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Prestador;

class AdminEditarPrestadorRequest extends FormRequest
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
            'personalidade' => [
                'required','integer',
                Rule::in(1, 2),
            ],
            'nome' => ['required','string'],
            // *** Se o prestador tiver personalidade fisica ***
            'nascimento' => [
                'nullable',
                'required_if:personalidade,1',
                'exclude_if:personalidade,2',
                'string',
            ],
            'sexo' => [
                'nullable',
                'required_if:personalidade,1',
                'exclude_if:personalidade,2',
                'numeric',
                Rule::in(array_keys(Prestador::opcoes_sexo))
            ],
            'cpf' => [
                'nullable',
                'required_if:personalidade,1',
                Rule::unique('prestadores','cpf')->ignore($this->prestadore)->whereNull('deleted_at'),
                'exclude_if:personalidade,2',
                'string',
            ],
            'identidade' => [
                'nullable',
                'required_if:personalidade,1',
                'exclude_if:personalidade,2',
                'string',
            ],
            'identidade_orgao_expedidor' => [
                'nullable',
                'required_if:personalidade,1',
                'exclude_if:personalidade,2',
                'string',
            ],
            'identidade_uf' => [
                'nullable',
                'required_if:personalidade,1',
                'exclude_if:personalidade,2',
                'string',
            ],
            'identidade_data_expedicao' => [
                'nullable',
                'required_if:personalidade,1',
                'exclude_if:personalidade,2',
                'string',
            ],
            'numero_cartao_sus' => [
                'nullable',
                'exclude_if:personalidade,2',
                'string',
            ],
            'nome_da_mae' => [
                'nullable',
                'required_if:personalidade,1',
                'exclude_if:personalidade,2',
                'string',
            ],
            'nome_do_pai' => [
                'nullable',
                'required_if:personalidade,1',
                'exclude_if:personalidade,2',
                'string',
            ],
            'naturalidade' => [
                'nullable',
                'required_if:personalidade,1',
                'exclude_if:personalidade,2',
                'string',
            ],
            'nacionalidade' => [
                'nullable',
                'required_if:personalidade,1',
                'exclude_if:personalidade,2',
                'string',
            ],
            // *** Se o prestador tiver personalidade Jurídica ***
            'cnpj' => [
                'nullable',
                'required_if:personalidade,2',
                Rule::unique('prestadores','cnpj')->ignore($this->prestadore)->whereNull('deleted_at'),
                'exclude_if:personalidade,1',
                'string',
            ],
            'razao_social' => [
                'nullable',
                'required_if:personalidade,2',
                'exclude_if:personalidade,1',
                'string',
            ],
            'cep' => [
                'nullable',
                'required_if:personalidade,2',
                'exclude_if:personalidade,1',
                'string',
            ],
            'estado' => [
                'nullable',
                'required_if:personalidade,2',
                'exclude_if:personalidade,1',
                'string',
            ],
            'cidade' => [
                'nullable',
                'required_if:personalidade,2',
                'exclude_if:personalidade,1',
                'string',
            ],
            'bairro' => [
                'nullable',
                'required_if:personalidade,2',
                'exclude_if:personalidade,1',
                'string',
            ],
            'rua' => [
                'nullable',
                'required_if:personalidade,2',
                'exclude_if:personalidade,1',
                'string',
            ],
            'numero' => [
                'nullable',
                'required_if:personalidade,2',
                'exclude_if:personalidade,1',
                'string',
            ],
        ];
    }

    public function messages()
    {
        return [
            'nome.required'=>'O nome do prestador é obrigatório',
            'nascimento.required_if'=>'A Data de Nascimento é obrigatória',
            'cpf.required_if'=>'O CPF é obrigatório',
            'sexo.required_if'=>'Campo obrigatório',
            'identidade.required_if'=>'o RG é obrigatório',
            'identidade_orgao_expedidor.required_if'=>'O Orgão Expedidor é obrigatório',
            'identidade_uf.required_if'=>'O UF é obrigatório',
            'identidade_data_expedicao.required_if'=>'A Data de Expedição é obrigatória',
            'nome_da_mae.required_if'=>'O Nome da Mãe é obrigatório',
            'nome_do_pai.required_if'=>'O Nome do Pai é obrigatório',
            'nacionalidade.required_if'=>'A nacionalidade é obrigatória',
            'naturalidade.required_if'=>'A Naturalidade é obrigatória',
            'cpf.unique'=> 'CPF já registrado em outro prestador',
            'cnpj.unique'=> 'CNPJ já registrado em outro prestador',
        ];
    }
}
