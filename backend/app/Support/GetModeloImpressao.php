<?php

namespace App\Support;

trait GetModeloImpressao {
    
    public static function getModeloImpressao($user)
    {
        $prestador = $user->prestadorMedico()->first();
        $modelo = $prestador->modeloImpressao()->first();

        if(empty($modelo)){
            return null;
        }

        return [
            'tamanho_folha' => $modelo->tamanho_folha,
            'tamanho_fonte' => $modelo->tamanho_fonte,
            'margem_cabecalho' => $modelo->margem_cabecalho,
            'margem_direita' => $modelo->margem_direita,
            'margem_rodape' => $modelo->margem_rodape,
            'margem_esquerda' => $modelo->margem_esquerda,
            'cabecalho' => $modelo->cabecalho,
            'rodape' => $modelo->rodape,
        ];
    }
}