<?php

namespace App;

use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InternacaoMedico extends Model
{
    use SoftDeletes;
    use TraitLogInstituicao;

    protected $table = 'internacoes_medicos';

    protected $fillable = [
        'id',
        'medico_id',
    ];

    public function medico()
    {
        return $this->belongsTo(Prestador::class, 'medico_id');
    }

    
}

