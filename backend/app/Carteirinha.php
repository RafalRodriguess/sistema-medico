<?php

namespace App;

use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Carteirinha extends Model
{
    use SoftDeletes;
    use TraitLogInstituicao;

    protected $table = 'pessoas_carteiras_planos_convenio';

    protected $fillable = [
        'id',
        'pessoa_id',
        'convenio_id',
        'plano_id',
        'carteirinha',
        'validade',
        'status',
    ];

    public function pessoa()
    {
        return $this->hasMany(Pessoa::class, 'id', 'pessoa_id');
    }

    public function convenio(){
        return $this->hasMany(Convenio::class, 'id', 'convenio_id');
    }

    public function plano(){
        return $this->hasMany(ConvenioPlano::class, 'id', 'plano_id');
    }

    public function planoUnico(){
        return $this->belongsTo(ConvenioPlano::class, 'plano_id');
    }

    public function scopeSearch(Builder $query, string $search = ''): Builder
    {
        $query->with('convenio');
        if(empty($search))
        {
            return $query;
        }

        return $query->where(function($q) use($search){
            $q->orWhere('carteirinha', 'like', "%{$search}%");
            $q->orWhere(function($qu) use($search){
                $qu->whereHas('convenio', function($que) use($search){
                    $que->where('nome', 'like', "%{$search}%");
                });
            });
        });
    }
    
    public function scopeGetCarteirinhas(Builder $query, string $search = ''): Builder
    {
        $query->with('convenio');
        if(empty($search))
        {
            return $query;
        }

        return $query->where(function($q) use($search){
            $q->orWhere('carteirinha', 'like', "%{$search}%");
            $q->orWhere(function($qu) use($search){
                $qu->whereHas('convenio', function($que) use($search){
                    $que->where('nome', 'like', "%{$search}%");
                });
            });
        });
    }
}
