<?php

namespace App;

use App\Support\ModelPossuiLogs;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Medicamento extends Model
{
    use SoftDeletes;
    use ModelPossuiLogs;

    protected $table = 'medicamentos';

    protected $fillable = [
        'id',
        'componente',
        'codigo_externo'
    ];

    public function produtos()
    {
        return $this->belongsToMany(Produto::class, 'produto_medicamentos','medicamento_id', 'produto_id')->withPivot('unidade', 'quantidade');

    }

    public function scopeSearch(Builder $query, string $search = ''): Builder
    {
        if(empty($search))
        {
            return $query->orderBy('id', 'desc');
        }

        if(preg_match('/^\d+$/', $search))
        {
            return $query->where('id','like', "{$search}%")->orderBy('id', 'desc');
        }

        return $query->where('componente', 'like', "%{$search}%")->orderBy('id', 'desc');
    }
}
