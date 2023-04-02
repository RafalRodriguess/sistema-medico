<?php

namespace App;

use App\Support\ModelPossuiLogs;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Marca extends Model
{
    use SoftDeletes;
    use ModelPossuiLogs;

    protected $table = 'marcas';

    protected $fillable = [
        'id',
        'nome',
        'slug',
        'imagem',
    ];

    public function produtos()
    {
        return $this->hasMany(Produto::class, 'marca_id');
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

        return $query->where('nome', 'like', "%{$search}%");
    }
    
}
