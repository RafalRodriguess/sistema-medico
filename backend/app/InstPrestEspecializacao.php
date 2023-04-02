<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InstPrestEspecializacao extends Model
{
    protected $table = 'inst_prest_especializacoes';

    protected $fillable = [
        'instituicoes_prestadores_id',
        'especializacoes_id',
    ];

    public $timestamps = false;

    public function especializacao()
    {
        return $this->belongsTo(Especializacao::class, 'especializacoes_id');
    }
}
