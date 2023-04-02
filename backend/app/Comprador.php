<?php

namespace App;

use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comprador extends Model
{
    use SoftDeletes;
    use TraitLogInstituicao;

    protected $table = 'comprador';

    protected $fillable = [
        'id',
        'descricao',
        'email',
        'ativo',
        'usuario_id'
    ];

    public function usuario()
    {
        return $this->belongsTo(InstituicaoUsuario::class,'usuario_id');
    }

    public function scopeSearch(Builder $query, string $search = '', int $usuario = 0 ): Builder
    {
        if(empty($search))
        {
            return $query;
        }

        if($usuario != 0){
            $query->where('usuario_id', $usuario);
        }

        if(preg_match('/^\d+$/', $search))
        {
            return $query->where('id','like', "{$search}%");
        }

        return $query->where('descricao', 'like', "%{$search}%");
    }
}
