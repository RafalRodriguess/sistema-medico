<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NfeEnotas extends Model
{
    static $url = "https://api.enotasgw.com.br/v1";

    static $api_key = "YWRiZGFiZjUtZDYwNC00Mzg2LWE4NGQtNTE3Nzk1ODAwODAw";


    public static function getTipoAutTexto(int $tipo): string
    {
        $dados = [
            0 => "Nenhuma", 
            1 => "Certificado", 
            2 => "Usuario e senha", 
            3 => "Token", 
            4 => "Frase secreta e senha",
            5 => "Usuario e senha + Token"
        ];

        return $dados[$tipo];
    }

    public static function getAssDigitalTexto(int $tipo): string
    {
        $dados = [
            0 => "NaoUtiliza", 
            1 => "Opcional", 
            2 => "Obrigatorio"
        ];
        
        return $dados[$tipo];
    }


}

