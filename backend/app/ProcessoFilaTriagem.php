<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\FilaTriagem;
use App\ProcessoTriagem;

class ProcessoFilaTriagem extends Model
{
    protected $table = 'processos_fila_triagem';
    protected $fillable = [
        'processos_triagem_id',
        'filas_triagem_id',
        'ordem',
    ];
    public $timestamps = false;

    public function filaTriagem()
    {
        return $this->belongsTo(FilaTriagem::class, 'filas_triagem_id');
    }

    public function processoTriagem()
    {
        return $this->belongsTo(ProcessoTriagem::class, 'processos_triagem_id');
    }
}
