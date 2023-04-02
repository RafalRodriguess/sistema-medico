<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Prontuario extends Model
{
    protected $table = 'instituicao_has_pacientes_atendimentos';

    protected $fillable = [
        'id',
        'data',
        'anamnese_qp'
    ];

    protected $casts = [
        'data' => 'datetime',
    ];

    public function instituicao()
    {
        return $this->hasOneThrough(Instituicao::class, InstituicaoPaciente::class, 'id', 'id', 'instituicao_has_pacientes_id',  'instituicao_id');
    }
}
