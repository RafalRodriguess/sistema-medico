<?php

namespace App;

use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class PessoaDocumento extends Model
{
    use SoftDeletes;
    use TraitLogInstituicao;

    protected $table = 'pessoas_documentos';

    protected $fillable = [
        'id',
        'tipo',
        'descricao',
        'file_path_name',
        'pessoa_id',
        'created_at'
    ];

    // Tipos ------------
    const curriculo = 1;
    const anexo = 2;
    // ------------------


    public static function getTipos(): array
    {
        return [
            self::curriculo,
            self::anexo,
        ];
    }

    public static function getTipoTexto($tipo): string
    {
        $dados = [
            self::curriculo => 'Curriculo',
            self::anexo => 'Anexo',
        ];

        return $dados[$tipo];
    }

    public function formatadaDataUpload()
    {
        $data = date_create($this->data);
        return date_format($data, 'd/m/Y H:i:s');
    }

    public function pessoa()
    {
        return $this->belongsTo(Pessoa::class, 'pessoa_id');
    }


    public function scopeSearchByTipo(Builder $query, int $tipo = 0): Builder
    {
        if($tipo == 0) return $query;

        return $query->where('tipo', '=', $tipo);
    }

    public function scopeSearchByDescricao(Builder $query, string $descricao = ''): Builder
    {
        if(empty($descricao)) return $query;

        return $query->where('descricao', 'like', "%{$descricao}%");
    }
}
