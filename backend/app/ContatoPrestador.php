<?php

namespace App;

use App\Support\ModelPossuiLogs;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContatoPrestador extends Model
{
    //
    use SoftDeletes;
    use ModelPossuiLogs;
    
    protected $table = 'contatos_prestadores';

    protected $fillable = [
        'id',
        'contato',
        'tipo_contato_id',
        'prestador_id'
    ];

    // Tipos de Contatos
        const endereco_email = 1;
        const telefone_celular = 2;
        const telefone_fixo = 3;
    //

    public static function getTiposContatos()
    {
        return [
            self::endereco_email,
            self::telefone_celular,
            self::telefone_fixo
        ];
    }

    public static function getTipoContatoTexto($tipo_contato)
    {
        $dados = [
            self::endereco_email => 'E-mail',
            self::telefone_celular => 'Número de Celular',
            self::telefone_fixo => 'Número de Telefone Fixo'
        ];
        return $dados[$tipo_contato];
    }

}