<?php

namespace App\Http\Requests\ChamadasTotem;

use App\ChamadaTotem;
use App\SenhaTriagem;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ChamarRequest extends FormRequest
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
            'senha' => ['string', Rule::exists('senhas_triagem', 'id')],
            'origem' => ['string', Rule::in(ChamadaTotem::origens_chamada)],
            'local' => ['string', 'nullable'],
            'completada' => ['nullable']
        ];
    }
}
