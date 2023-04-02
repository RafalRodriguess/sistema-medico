<?php

namespace App;

use App\Support\ModelPossuiLogs;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubCategoria extends Model
{

    use SoftDeletes;
    use ModelPossuiLogs;

    protected $table = 'sub_categorias';

    protected $fillable = [
        'id',
        'nome',
        'categoria_id',
    ];



    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    public function produto(){
        return $this->hasMany(Produto::class, 'sub_categoria_id');
    }

    public function comercial()
    {
        return $this->belongsTo(Comercial::class, 'comercial_id');
    }

    public function scopeSearch(Builder $query, string $search = '', int $categoria): Builder
    {
        if ($categoria != 0) {
            $query->where('categoria_id', "{$categoria}");
        }

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
