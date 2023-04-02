<?php

namespace App;

use App\Http\Controllers\Instituicao\Pessoas;
use App\Support\ModelOverwrite;
use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EstoqueEntradas extends Model
{
    use SoftDeletes;
    use TraitLogInstituicao;
    use ModelOverwrite;

    protected $table = 'estoque_entradas';

    protected $fillable = [
        'id',
        'instituicao_id',
        'id_tipo_documento',
        'id_estoque',
        'consignado',
        'contabiliza',
        'numero_documento',
        'serie',
        'id_fornecedor',
        'data_emissao',
        'data_hora_entrada',
    ];

    protected $casts = [
        'consignado' => 'boolean',
        'contabiliza' => 'boolean',
        'data_emissao' => 'date',
    ];

    protected $allowed_overwrite = [
        EstoqueEntradaProdutos::class
    ];

    public function instituicao()
    {
        return $this->belongsTo(Instituicao::class, 'instituicao_id','id');
    }

    public function tipoDocumento()
    {
        return $this->belongsTo(TipoDocumento::class, 'id_tipo_documento','id');
    }

    public function estoque()
    {
        return $this->belongsTo(Estoque::class, 'id_estoque','id');
    }
    
    public function estoqueTrashed()
    {
        return $this->belongsTo(Estoque::class, 'id_estoque','id')->withTrashed();
    }

    public function pessoas()
    {
        return $this->belongsTo(Pessoa::class, 'id_fornecedor','id');
    }

    public function fornecedor()
    {
        return $this->pessoas();
    }

    public function estoqueEntradaProdutos()
    {
        return $this->hasMany(EstoqueEntradaProdutos::class, 'id_entrada','id');
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

        return $query->where(function ($query) use ($search) {
            $query->orwhere('numero_documento', 'like', "%{$search}%");
          })->orderBy('id','desc');

    }

}
