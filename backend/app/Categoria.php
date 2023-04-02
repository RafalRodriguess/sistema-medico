<?php

namespace App;

use App\Support\ModelPossuiLogs;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Categoria extends Model
{
    //
    use SoftDeletes;
    use ModelPossuiLogs;

    protected $table = 'categorias';

    protected $fillable = [
        'id',
        'nome',
    ];

    public function comercial()
    {
        return $this->belongsTo(Comercial::class, 'comercial_id');
    }

    public function subCategorias()
    {
        return $this->hasMany(SubCategoria::class, 'categoria_id');
    }

    public function produto(){
        return $this->hasMany(Produto::class, 'categoria_id');
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
