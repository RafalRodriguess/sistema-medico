<?php

namespace App;

use App\Agendamentos as Agendamento;
use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FaturamentoLoteGuia extends Model
{
    use SoftDeletes;
    use TraitLogInstituicao;

    protected $table = 'faturamento_protocolos_guias';

    protected $fillable = [
        'id',
        'cod_externo',
        'status',
        'faturamento_protocolo_id',
        'agendamento_id',
    ];

    // static function getStatus(int $status = null){
    //     $lista_status = [
    //         0 => 'criado',
    //         1 => 'finalizado',
    //         2 => 'pendente'
    //     ];

    //     if($status === null){
    //         return $lista_status;
    //     }else{
    //         return (array_key_exists($status, $lista_status)) ? $lista_status[$status] : null;
    //     }
    // }

    public function agendamento_paciente()
    {
        return $this->belongsTo(Agendamento::class, 'agendamento_id');
    }

}
