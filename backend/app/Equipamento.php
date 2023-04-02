<?php

namespace App;

use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Equipamento extends Model
{
    use SoftDeletes;
    use TraitLogInstituicao;
    
    protected $table = 'equipamentos';

    protected $fillable = [
        'id',
        'descricao',
        'instituicao_id',
        'procedimento_id',
    ];

    public function procedimento()
    {
        return $this->belongsTo(Procedimento::class, 'procedimento_id');
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
