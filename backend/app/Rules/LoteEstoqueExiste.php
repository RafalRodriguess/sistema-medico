<?php

namespace App\Rules;

use App\EstoqueEntradaProdutos;
use Illuminate\Contracts\Validation\Rule;

class LoteEstoqueExiste implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return EstoqueEntradaProdutos::lotesProdutosEmEstoque()
            ->where('lote', '=', $value)
            ->count() > 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Esse produto não está em estoque';
    }
}
