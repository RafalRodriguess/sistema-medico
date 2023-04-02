<?php

namespace App\Http\Requests\RegraCobranca;

use App\RegraCobrancaItem;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CriarRegraCobrancaItemRequest extends FormRequest
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
            'itens.*.grupo_procedimento_id' => ['required', 'exists:grupos_procedimentos,id'],
            'itens.*.faturamento_id' => ['required', 'exists:faturamentos,id'],
            'itens.*.pago' => ['required'],
            'itens.*.base' => ['required', Rule::in(RegraCobrancaItem::base())]
        ];
    }
}
