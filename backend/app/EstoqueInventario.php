<?php

namespace App;

use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Estoque;
use App\Support\ModelOverwrite;

class EstoqueInventario extends Model
{
    use SoftDeletes;
    use TraitLogInstituicao;
    use ModelOverwrite;

    protected $table = 'estoque_inventario';

    protected $fillable = [
        'id',
        'instituicao_id',
        'estoque_id',
        'data',
        'hora',
        'aberta',
        'tipo_contagem',
        'usuario_id'
    ];

    protected $casts = [
        'aberta' => 'boolean',
        'data' => 'date',
        'tipo_contagem'=>'string'
    ];

    protected $allowed_overwrite = [
        EstoqueInventarioProdutos::class
    ];

    public function usuario()
    {
        return $this->belongsTo(InstituicaoUsuario::class, 'usuario_id');
    }

    public function instituicao()
    {
        return $this->belongsTo(Instituicao::class, 'instituicao_id','id');
    }
    public function estoque()
    {
        return $this->belongsTo(Estoque::class, 'estoque_id','id');
    }

    public function estoqueInventarioProdutos()
    {
        return $this->hasMany(EstoqueInventarioProdutos::class, 'estoque_inventario_id','id');
    }

    public function estoques()
    {
        return $this->hasMany(Estoque::class, 'id');
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

        // return $query->where(function ($query) use ($search) {
        //     $query->orwhere('tipo_contagem', 'like', "%{$search}%");
        //   });
        return $query->whereHas('estoques', function ($query) use ($search) {
            $query->where('descricao', 'like', "%{$search}%");
          });

    }
}
