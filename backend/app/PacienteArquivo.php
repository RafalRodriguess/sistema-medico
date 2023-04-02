<?php

namespace App;

use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PacienteArquivo extends Model
{
    use SoftDeletes;
    use TraitLogInstituicao;

    protected $table = 'paciente_arquivos';

    protected $fillable = [
        'id',
        'paciente_pasta_id',
        'usuario_id',
        'nome',
        'diretorio',
    ];

    public function pasta()
    {
        return $this->belongsTo(PacientePasta::class, 'paciente_pasta_id');
    }
}
