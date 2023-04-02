<?php

namespace App;

use App\Http\Controllers\Instituicao\Setores;
use App\Support\ModelOverwrite;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class EstoqueBaixa extends Model
{
    use SoftDeletes;
    use TraitLogInstituicao;
    use ModelOverwrite;

    protected $table = 'estoque_baixa';

    protected $fillable = [
        'id',
        'estoque_id',
        'setor_id', // Setor exame
        'motivo_baixa_id',
        'data_emissao',
        'data_hora_baixa',
        'usuario_id',
        'instituicao_id',

    ];

     protected $casts = [
        'id' => 'interger',
        'estoque_id'=>'interger',
        'usuario_id'=>'interger',
        'instituicao_id'=>'interger',
    ];

    protected $allowed_overwrite = [
        ProdutoBaixa::class,
    ];

    public function estoqueBaixaProdutos()
    {
        return $this->hasMany(ProdutoBaixa::class, 'baixa_id');
    }

    public function estoqueEntradasProdutos()
    {
        return $this->hasManyThrough(EstoqueEntradaProdutos::class, ProdutoBaixa::class, 'baixa_id', 'id', 'id', 'id_entrada_produto');
    }


    public function setorExame()
    {
        return $this->belongsTo(SetorExame::class, 'setor_id');
    }

    public function motivoBaixa()
    {
         return $this->belongsTo(MotivoBaixa::class, 'motivo_baixa_id','id');
    }

    public function estoque()
    {
        return $this->belongsTo(Estoque::class, 'estoque_id','id');
    }

    public function usuario()
    {
        return $this->belongsTo(InstituicaoUsuario::class, 'usuario_id','id');
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

        // return $query->whereHas('estoque_baixa', function ($query) use ($search) {
        //     $query->where('estoque_id', 'like', "%{$search}%");
        //   });
        return $query->whereHas('estoque', function ($query) use ($search) {
            $query->where('id', 'like', "%{$search}%");
          });
    }

    public function delete()
    {
        $this->estoqueBaixaProdutos()->get()->map(function($produto) {
            $produto->delete();
        });
        DB::table($this->table)->where('id', $this->id)->update(['deleted_at' => date('Y-m-d H:i:s')]);
    }
}
