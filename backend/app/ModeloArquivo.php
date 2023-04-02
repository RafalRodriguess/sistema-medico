<?php

namespace App;

use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModeloArquivo extends Model
{
    use SoftDeletes;
    use TraitLogInstituicao;

    protected $table = 'modelo_arquivos';

    protected $fillable = [
        'id',
        'instituicao_id',
        'descricao',
        'diretorio',
    ];

    public function scopeSearch(Builder $query, string $search = ""):Builder
    {
        if(empty($search)){
            return $query;
        }

        return $query->where('descricao', 'like', "%{$search}%");
    }
}
