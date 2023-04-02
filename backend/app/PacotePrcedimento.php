<?php

namespace App;

use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PacotePrcedimento extends Model
{
    use SoftDeletes;
    use TraitLogInstituicao;

    protected $table = 'pacotes_procedimentos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'descricao',
        'instituicao_id',
    ];

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

        return $query->where('descricao', 'like', "%{$search}%")->orderBy('id', 'desc');
    }

    public function procedimentoVinculo()
    {
        return $this->belongsToMany(Procedimento::class, 'pacotes_procedimentos_vinculos', 'pacote_id', 'procedimento_id');
    }
}
