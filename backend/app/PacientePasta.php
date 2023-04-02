<?php

namespace App;

use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PacientePasta extends Model
{
    use SoftDeletes;
    use TraitLogInstituicao;

    protected $table = 'paciente_pastas';

    protected $fillable = [
        'id',
        'paciente_id',
        'usuario_id',
        'nome',
        'slug',
    ];

    public function arquivo()
    {
        return $this->hasMany(PacienteArquivo::class, 'paciente_pasta_id');
    }
}
