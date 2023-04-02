<?php

namespace App\Support;

trait ConverteValor {
    public static function converteDecimal($valor) {
        $total = str_replace('.','',$valor);
        $total = str_replace(',','.',$total);

        return $total;
    }

    public static function calcularIdade($data){
        $idade = 0;
        $data_nascimento = date('Y-m-d', strtotime($data));
        // $data = explode("-",$data_nascimento);
        // $anoNasc    = $data[0];
        // $mesNasc    = $data[1];
        // $diaNasc    = $data[2];
    
        // $anoAtual   = date("Y");
        // $mesAtual   = date("m");
        // $diaAtual   = date("d");
    
        // $idade      = $anoAtual - $anoNasc;
        // if ($mesAtual < $mesNasc){
        //     $idade -= 1;
        // } elseif ( ($mesAtual == $mesNasc) && ($diaAtual <= $diaNasc) ){
        //     $idade -= 1;
        // }

        list($ano, $mes, $dia) = explode('-', $data_nascimento);

        // data atual
        $hoje = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
        // Descobre a unix timestamp da data de nascimento do fulano
        $nascimento = mktime( 0, 0, 0, $mes, $dia, $ano);

        // cálculo
        $idade = floor((((($hoje - $nascimento) / 60) / 60) / 24) / 365.25);
    
        return $idade;
    }
}