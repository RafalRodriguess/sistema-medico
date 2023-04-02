<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SetorExamePrestadores extends Model
{
    protected $table = 'setores_prestadores_exame';

    protected $fillable = [
        'setores_exame_id',
        'prestadores_id'
    ];

    public function setor() {
        return $this->belongsTo(SetorExame::class, 'setores_exame_id');
    }

    public function prestador() {
        return $this->belongsTo(Prestador::class, 'prestadores_id');
    }
}
