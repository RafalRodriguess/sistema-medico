<?php

namespace App\Integracoes;

use App\Instituicao;

class Factory {

    public static function make(Instituicao $instituicao): Integracao
    {
        if ($instituicao->chave_unica === "santacasa") {
            return resolve(SantaCasa::class);
        }

        throw new \Exception("Integracao desconhecida!");
    }

}