<?php

namespace App;

use App\Support\ModelPossuiLogs;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Comercial extends Model
{
    use SoftDeletes;
    use ModelPossuiLogs;

    protected $table = 'comerciais';

    protected $fillable = [
        'id',
        'nome_fantasia',
        'email',
        'cnpj',
        'razao_social',
        'categoria',
        'telefone',
        'rua',
        'numero',
        'cep',
        'bairro',
        'cidade',
        'estado',
        'realiza_entrega',
        'pagamento_cartao',
        'logo',
        'retirada_loja',
        'exibir',
        'complemento',
        'referencia',
        'banco_id',
        'max_parcela',
        'free_parcela',
        'valor_parcela',
        'taxa_tectotum',
        'valor_minimo',
        'cartao_credito',
        'cartao_entrega',
        'dinheiro',
    ];

    protected $casts = [
        'realiza_entrega' => 'boolean',
        'retirada_loja' => 'boolean',
        'cartao_credito' => 'boolean',
        'cartao_entrega' => 'boolean',
        'dinheiro' => 'boolean',
        'exibir' => 'boolean',
    ];



    protected $appends = ['logo_300px','logo_200px','logo_100px'];

    public function getLogo300pxAttribute()
    {
        if(is_null($this->logo) || empty($this->logo)){
            return null;
        }else{
            $caminho = Str::of($this->logo)->explode('/');

            return $caminho[0].'/'.$caminho[1].'/300px-'.$caminho[2];
        }
    }

    public function getLogo200pxAttribute()
    {
        if(is_null($this->logo) || empty($this->logo)){
            return null;
        }else{
            $caminho = Str::of($this->logo)->explode('/');

            return $caminho[0].'/'.$caminho[1].'/200px-'.$caminho[2];
        }
    }

    public function getLogo100pxAttribute()
    {
        if(is_null($this->logo) || empty($this->logo)){
            return null;
        }else{
            $caminho = Str::of($this->logo)->explode('/');

            return $caminho[0].'/'.$caminho[1].'/100px-'.$caminho[2];
        }
    }

    public function banco(){
        return $this->belongsTo(ContaBancaria::class,'banco_id');
    }

    public function comercialUsuarios()
    {
        return $this->belongsToMany(ComercialUsuario::class,'comercial_has_usuarios', 'comercial_id', 'usuario_id');
    }

    public function categorias()
    {
        return $this->hasMany(Categoria::class, 'comercial_id');
    }

    public function subCategorias()
    {
        return $this->hasMany(SubCategoria::class, 'comercial_id');
    }

    public function produtos()
    {
        return $this->hasMany(Produto::class, 'comercial_id');
    }


    public function fretes()
    {
        return $this->hasMany(Fretes::class, 'comercial_id');
    }
    
    public function horarioFuncionamento()
    {
        return $this->hasMany(HorarioFuncionamentoComercial::class, 'comercial_id');
    }

    public function scopeSearch(Builder $query, string $search = '', bool $pesquisarId = true): Builder
    {
        if(empty($search))
        {
            return $query;
        }

        if($pesquisarId && preg_match('/^\d+$/', $search))
        {
            return $query->where('id','like', "{$search}%");
        }

        return $query->where(function ($query) use ($search) {
            $query->orWhere('razao_social', 'like', "%{$search}%");
            $query->orWhere('nome_fantasia', 'like', "%{$search}%");
          });

    }

}
