<?php


namespace App;
use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Produto extends Model
{
    use SoftDeletes;
    use TraitLogInstituicao;

    protected $table = 'produtos';

    protected $fillable = [
        'id',
        'descricao',
        'kit',
        'mestre',
        'tipo',
        'generico',
        'unidade_id',
        'especie_id',
        'classificacao_abc',
        'classificacao_xyz',
        'classe_id',
        'opme',
    ];

    protected $casts = [
        'kit' => 'boolean',
        'mestre' => 'boolean',
        'generico' => 'boolean',
        'opme' => 'boolean',
    ];

    public function especie()
    {
        return $this->belongsTo(Especie::class,'especie_id');
    }

    public function classe()
    {
        return $this->belongsTo(Classe::class,'classe_id');
    }

    public function unidade()
    {
        return $this->belongsTo(Unidade::class, 'unidade_id');
    }

    public function entradas()
    {
        return $this->hasManyThrough(EstoqueEntradas::class, EstoqueEntradaProdutos::class, 'id_produto', 'id', 'id', 'id_entrada');
    }

    public function estoqueEntradas()
    {
        return $this->hasMany(EstoqueEntradaProdutos::class, 'id_produto');
    }
    
    public function estoqueSaidas()
    {
        return $this->hasMany(ProdutoBaixa::class, 'produto_id');
    }

    public function totalEmEstoque()
    {
        $instituicao = Instituicao::findOrFail(request()->session()->get('instituicao'));
        $result = DB::table('estoque_entradas_produtos')
            ->selectRaw('SUM(quantidade_estoque) as quantidade_estoque')
            ->join('estoque_entradas','estoque_entradas.id','id_entrada')
            ->where('id_produto', $this->id)
            ->where('estoque_entradas.instituicao_id', $instituicao->id)
            ->groupBy('id_produto')
            ->first();
        return !empty($result) ? $result->quantidade_estoque : 0;
    }

    public function scopeSearch(Builder $query, string $search = '', int $especie = 0 , int $classe = 0 , int $generico = 2 , int $mestre = 2 , int $kit = 2 , string $tipo = ''   ): Builder
    {

        if($especie != 0){
            $query->whereHas('especie', function($q) use($especie){
                $q->where('id', $especie);
            });
        }

        if($classe != 0){
            $query->whereHas('classe', function($q) use($classe){
                $q->where('id', $classe);
            });
        }

        if($generico != 2){
            $query->where('generico', $generico);
        }

        if($mestre != 2){
            $query->where('mestre', $mestre);
        }

        if($kit != 2){
            $query->where('kit', $kit);
        }

        if(!empty($tipo)){
            $query->where('tipo', $tipo);
        }

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
