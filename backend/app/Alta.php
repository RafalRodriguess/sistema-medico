<?php

namespace App;

use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Alta extends Model
{
    use TraitLogInstituicao;

    protected $table = 'altas_internacao';

    protected $fillable = [
        'id',
        'internacao_id',
        'data_alta',
        'motivo_alta_id',
        'infeccao_alta',
        'procedimento_alta_id',
        'especialidade_alta_id',
        'obs_alta',
        'declaracao_obito_alta',
        'setor_alta_id',
        'status',
        'motivo_cancel_alta',
        'data_cancel_alta',
        	
    ];

    public function scopeSearch(Builder $query, string $search = ''): Builder
    {
        if(empty($search)){
            return $query;
        }

        if(preg_match('/^\d+$/', $search)){
            return $query->where('id','like', "{$search}%");
        }

        return $query->where('descricao', 'like', "%{$search}%");
    }
}
