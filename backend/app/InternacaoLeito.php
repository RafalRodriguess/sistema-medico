<?php

namespace App;

use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class InternacaoLeito extends Model
{
    use SoftDeletes;
    use TraitLogInstituicao;

    protected $table = 'internacoes_leitos';

    protected $fillable = [
        'id',
        'internacao_id',
        'acomodacao_id',
        'unidade_id',
        'leito_id'			
    ];

    public function leito()
    {
        return $this->belongsTo(UnidadeLeito::class, 'leito_id');
    }

    public function acomodacao()
    {
        return $this->belongsTo(Acomodacao::class, 'acomodacao_id');
    }

    public function unidade()
    {
        return $this->belongsTo(UnidadeInternacao::class, 'unidade_id');
    }
}
