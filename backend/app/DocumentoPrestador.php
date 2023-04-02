<?php

namespace App;

use App\Support\ModelPossuiLogs;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentoPrestador extends Model
{
    //
    use SoftDeletes;
    use ModelPossuiLogs;

    protected $table = 'documentos_prestadores';

    protected $fillable = [
        'id',
        'file_path_name',
        'tipo',
        'descricao',
        'prestador_id',
        'instituicao_id',
        'instituicao_prestador_id'
    ];

    // Tipos de Documento
        const anexo = 1;
        const curriculo = 2;
        const documento_pessoal = 3;
        const certificado = 4;
    //

    public static function getTiposDocumentos()
    {
        return [
            self::anexo,
            self::curriculo,
            self::documento_pessoal,
            self::certificado
        ];
    }

    public static function getTipoDocumentoTexto($tipo_documento)
    {
        $dados = [
            self::anexo => 'Anexo',
            self::curriculo => 'Currículo',
            self::documento_pessoal => 'Documento Pessoal',
            self::certificado => 'Certificado'
        ];
        return $dados[$tipo_documento];
    }

    public function scopeSearch(Builder $query, string $search = ''): Builder
    {
        if(empty($search))
        {
            return $query;
        }

        if(preg_match('/^\d+$/', $search))
        {
            return $query->where('id','like', "{$search}%");
        }

        return $query->where('descricao', 'like', "%{$search}%");
    }

}
