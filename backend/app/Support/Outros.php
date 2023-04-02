<?php

namespace App\Support;

use App\Log;

trait Outros {

    public static function valorPorExtenso( $valor = 0, $bolExibirMoeda = true, $bolPalavraFeminina = false )
    {
 
        $valor = self::removerFormatacaoNumero( $valor );
        
        $singular = null;
        $plural = null;
 
        if ( $bolExibirMoeda )
        {
            $singular = array("centavo", "real", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
            $plural = array("centavos", "reais", "mil", "milhões", "bilhões", "trilhões","quatrilhões");
        }
        else
        {
            $singular = array("", "", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
            $plural = array("", "", "mil", "milhões", "bilhões", "trilhões","quatrilhões");
        }
 
        $c = array("", "cem", "duzentos", "trezentos", "quatrocentos","quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos");
        $d = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta","sessenta", "setenta", "oitenta", "noventa");
        $d10 = array("dez", "onze", "doze", "treze", "quatorze", "quinze","dezesseis", "dezessete", "dezoito", "dezenove");
        $u = array("", "um", "dois", "três", "quatro", "cinco", "seis","sete", "oito", "nove");
 
 
        if ( $bolPalavraFeminina )
        {
        
            if ($valor == 1) 
            {
                $u = array("", "uma", "duas", "três", "quatro", "cinco", "seis","sete", "oito", "nove");
            }
            else 
            {
                $u = array("", "um", "duas", "três", "quatro", "cinco", "seis","sete", "oito", "nove");
            }
            
            
            $c = array("", "cem", "duzentas", "trezentas", "quatrocentas","quinhentas", "seiscentas", "setecentas", "oitocentas", "novecentas");
            
            
        }
 
        $z = 0;

        if (mb_strpos($valor, '.') !== false) {
            $valor = number_format( $valor, 2, ".", "." );
        }

        $inteiro = explode( ".", $valor );
 
        for ( $i = 0; $i < count( $inteiro ); $i++ ) 
        {
            for ( $ii = mb_strlen( $inteiro[$i] ); $ii < 3; $ii++ ) 
            {
                $inteiro[$i] = "0" . $inteiro[$i];
            }
        }

        // $fim identifica onde que deve se dar junção de centenas por "e" ou por "," ;)
        $rt = null;
        $fim = count( $inteiro ) - ($inteiro[count( $inteiro ) - 1] > 0 ? 1 : 2);
        for ( $i = 0; $i < count( $inteiro ); $i++ )
        {
            $valor = $inteiro[$i];
            $rc = (($valor > 100) && ($valor < 200)) ? "cento" : $c[$valor[0]];
            $rd = ($valor[1] < 2) ? "" : $d[$valor[1]];
            $ru = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : "";
 
            $r = $rc . (($rc && ($rd || $ru)) ? " e " : "") . $rd . (($rd && $ru) ? " e " : "") . $ru;
            $t = count( $inteiro ) - 1 - $i;
            $r .= $r ? " " . ($valor > 1 ? $plural[$t] : $singular[$t]) : "";
            if ( $valor == "000")
                $z++;
            elseif ( $z > 0 )
                $z--;
                
            if ( ($t == 1) && ($z > 0) && ($inteiro[0] > 0) )
                $r .= ( ($z > 1) ? " de " : "") . $plural[$t];
                
            if ( $r )
                $rt = $rt . ((($i > 0) && ($i <= $fim) && ($inteiro[0] > 0) && ($z < 1)) ? ( ($i < $fim) ? ", " : " e ") : " ") . $r;
        }
 
        $rt = mb_substr( $rt, 1 );
 
        return($rt ? trim( $rt ) : "zero");
 
    }

    public static function removerFormatacaoNumero( $strNumero )
    {
        
        $strNumero = number_format(floatval($strNumero), 2, ',', '.');
        
        $strNumero = trim( str_replace( "R$", 'null', $strNumero ) );
        $vetVirgula = explode( ",", $strNumero );
        if ( count( $vetVirgula ) == 1 )
        {
            $acentos = array(".");
            $resultado = str_replace( $acentos, "", $strNumero );

            return $resultado;
        }
        else if ( count( $vetVirgula ) != 2 )
        {
            return $strNumero;
        }
 
        $strNumero = $vetVirgula[0];
        $strDecimal = mb_substr( $vetVirgula[1], 0, 2 );
 
        $acentos = array(".");
        $resultado = str_replace( $acentos, "", $strNumero );
        $resultado = $resultado . "." . $strDecimal;
        
        return $resultado;
 
    }

    public static function extenso($valor = 0) {
        $valor = self::removerFormatacaoNumero( $valor );
 
        $singular = null;
        $plural = null;
 
        $singular = array('centimetro', 'metro quadrado', 'mil', 'milhão', 'bilhão', 'trilhão', 'quatrilhão');
        $plural = array('centimetros', 'metros quadrados', 'mil', 'milhões', 'bilhões', 'trilhões',
    'quatrilhões');
        
 
        $c = array("", 'cem', 'duzentos', 'trezentos', 'quatrocentos',
        'quinhentos', 'seiscentos', 'setecentos', 'oitocentos', 'novecentos');
        $d = array("", 'dez', 'vinte', 'trinta', 'quarenta', 'cinquenta',
        'sessenta', 'setenta', 'oitenta', 'noventa');
        $d10 = array('dez', 'onze', 'doze', 'treze', 'quatorze', 'quinze',
        'dezesseis', 'dezesete', 'dezoito', 'dezenove');
        $u = array("", 'um', 'dois', 'três', 'quatro', 'cinco', 'seis',
        'sete', 'oito', 'nove');
                $z = 0;
        $rt = '';
 
 
 
 
        $z = 0;
        if (mb_strpos($valor, '.') !== false) {
            $valor = number_format( $valor, 2, ".", "." );
        }
        //  dd($valor);
        $inteiro = explode( ".", $valor );
 
        for ( $i = 0; $i < count( $inteiro ); $i++ ) 
        {
            for ( $ii = mb_strlen( $inteiro[$i] ); $ii < 3; $ii++ ) 
            {
                $inteiro[$i] = "0" . $inteiro[$i];
            }
        }
 
        // $fim identifica onde que deve se dar junção de centenas por "e" ou por "," ;)
        $rt = null;
        $fim = count( $inteiro ) - ($inteiro[count( $inteiro ) - 1] > 0 ? 1 : 2);
        for ( $i = 0; $i < count( $inteiro ); $i++ )
        {
            $valor = $inteiro[$i];
            $rc = (($valor > 100) && ($valor < 200)) ? "cento" : $c[$valor[0]];
            $rd = ($valor[1] < 2) ? "" : $d[$valor[1]];
            $ru = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : "";
 
            $r = $rc . (($rc && ($rd || $ru)) ? " e " : "") . $rd . (($rd && $ru) ? " e " : "") . $ru;
            $t = count( $inteiro ) - 1 - $i;
            $r .= $r ? " " . ($valor > 1 ? $plural[$t] : $singular[$t]) : "";
            if ( $valor == "000")
                $z++;
            elseif ( $z > 0 )
                $z--;
                
            if ( ($t == 1) && ($z > 0) && ($inteiro[0] > 0) )
                $r .= ( ($z > 1) ? " de " : "") . $plural[$t];
                
            if ( $r )
                $rt = $rt . ((($i > 0) && ($i <= $fim) && ($inteiro[0] > 0) && ($z < 1)) ? ( ($i < $fim) ? ", " : " e ") : " ") . $r;
        }
 
        $rt = mb_substr( $rt, 1 );
 
        return($rt ? trim( $rt ) : "zero");
    }

    public static function getbackground($posicao)
    {
        
        $dados  = array(
            '#26c6da',
            '#1e88e5',
            '#ffcf8e',
            '#745af2',
            "#CD5C5C",
            "#1ab394",   //inspinia green
            "#dcdcdc",
            "#b5b8cf",
            "#a3e1d4",
            "#B0D602",
            "#FFC300",
            "#900C3F",
            "#1194F5",
            "#EA7FFF",
            "#800000",
            "#FA8072",
            "#FF6347",
            "#FF7F50",
            "#FF4500",
            "#8B0000",
            "#B22222",
            "#FF0000",
            "#D2691E",
            "#F4A460",
            "#FF8C00",
            "#FFA500",
            "#B8860B",
            "#008000",
            "#00FF00",
            "#32CD32",
            "#00FF7F",
            "#00FA9A",
            "#40E0D0",
            "#20B2AA",
            "#48D1CC",
            "#008080",
            "#008B8B",
            "#00FFFF",
            "#00FFFF",
            "#00CED1",
            "#00BFFF",
            "#1E90FF",
            "#4169E1",
            "#000080",
            "#00008B",
            "#DAA520",
            "#FFD700",
            "#808000",
            "#FFFF00",
            "#9ACD32",
            "#ADFF2F",
            "#7FFF00",
            "#7CFC00",
            "#0000CD",
            "#0000FF",
            "#8A2BE2",
            "#9932CC",
            "#9400D3",
            "#800080",
            "#8B008B",
            "#FF00FF",
            "#FF00FF",
            "#C71585",
            "#FF1493",
            "#FF69B4",
            "#DC143C",
            "#A52A2A",
            "#BC8F8F",
            "#F08080",
            "#FFFAFA",
            "#FFE4E1",
            "#E9967A",
            "#FFA07A",
            "#A0522D",
            "#FFF5EE",
            "#8B4513",
            "#FFDAB9",
            "#CD853F",
            "#FAF0E6",
            "#FFE4C4",
            "#DEB887",
            "#D2B48C",
            "#FAEBD7",
            "#FFDEAD",
            "#FFEBCD",
            "#FFEFD5",
            "#FFE4B5",
            "#F5DEB3",
            "#FDF5E6",
            "#FFFAF0",
            "#FFF8DC",
            "#F0E68C",
            "#FFFACD",
            "#EEE8AA",
            "#BDB76B",
            "#F5F5DC",
            "#FAFAD2",
            "#FFFFE0",
            "#FFFFF0",
            "#6B8E23",
            "#556B2F",
            "#8FBC8F",
            "#006400",
            "#228B22",
            "#90EE90",
            "#98FB98",
            "#F0FFF0",
            "#2E8B57",
            "#3CB371",
            "#F5FFFA",
            "#66CDAA",
            "#7FFFD4",
            "#2F4F4F",
            "#AFEEEE",
            "#E0FFFF",
            "#F0FFFF",
            "#5F9EA0",
            "#B0E0E6",
            "#ADD8E6",
            "#87CEEB",
            "#87CEFA",
            "#4682B4",
            "#F0F8FF",
            "#708090",
            "#778899",
            "#B0C4DE",
            "#6495ED",
            "#E6E6FA",
            "#F8F8FF",
            "#191970",
            "#6A5ACD",
            "#483D8B",
            "#7B68EE",
            "#9370DB",
            "#4B0082",
            "#BA55D3",
            "#DDA0DD",
            "#EE82EE",
            "#D8BFD8",
            "#DA70D6",
            "#FFF0F5",
            "#DB7093",
            "#FFC0CB",
            "#FFB6C1",
            "#000000",
            "#696969",
            "#808080",
            "#A9A9A9",
            "#C0C0C0",
            "#D3D3D3",
            "#DCDCDC",
            "#F5F5F5",
            "#FFFFFF"
        );

        return $dados[$posicao];
    }

}

