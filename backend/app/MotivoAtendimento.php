<?php

namespace App;

use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MotivoAtendimento extends Model
{
    use TraitLogInstituicao;
    use SoftDeletes;

    protected $table = 'motivos_atendimento';

    protected $fillable = [
        'id',
        'instituicao_id',
        'descricao',
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
