<?php

namespace App\Http\Requests\RegraCobranca;

use App\RegraCobrancaItem;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EditarRegraCobrancaItemRequest extends FormRequest
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
            'grupo_procedimento_id' => ['required', 'exists:grupos_procedimentos,id'],
            'faturamento_id' => ['required', 'exists:faturamentos,id'],
            'pago' => ['required'],
            'base' => ['required', Rule::in(RegraCobrancaItem::base())]
        ];
    }
}
