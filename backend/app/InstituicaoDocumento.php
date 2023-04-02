<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InstituicaoDocumento extends Model
{
    protected $table = 'instituicao_has_pacientes_documentos';

    protected $fillable = [
        'id',
        'data_pedido',
    ];

    protected $casts = [
        'data_pedido' => 'datetime',
    ];

    public function instituicao()
    {
        return $this->hasOneThrough(Instituicao::class, InstituicaoPaciente::class, 'id', 'id', 'instituicao_has_pacientes_id',  'instituicao_id');
    }

    public function procedimentos_instituicao()
    {
        return $this->hasMany(InstituicaoProcedimentos::class, "procedimentos_idexterno", "codigo_exame")->with("procedimento");
    }
}
