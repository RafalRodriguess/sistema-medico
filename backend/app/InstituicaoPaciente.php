<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class InstituicaoPaciente extends Pivot
{
    protected $table = 'instituicao_has_pacientes';
    protected $fillable = [
        'instituicao_id',
        'usuario_id',
        'id_externo',
        'metadados',
        'created_at',
        'updated_at',
    ];

    public $incrementing = true;

    public function paciente()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function instituicao()
    {
        return $this->belongsTo(Instituicao::class, 'instituicao_id');
    }
}
