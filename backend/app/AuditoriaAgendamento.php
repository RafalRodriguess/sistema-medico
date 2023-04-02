<?php

namespace App;

use App\Agendamentos;
use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class AuditoriaAgendamento extends Model
{
    use TraitLogInstituicao;

    protected $table = 'auditoria_agendamentos';

    protected $fillable = [
        'id',
        'status',
        'data',
        'log',
        'informacao',
        'agendamento_id',
        'usuario_id'
    ];

    public function agendamentos(){
        return $this->hasMany(Agendamentos::class, 'id', 'agendamento_id');
    }
    
    public function usuarios(){
        return $this->belongsTo(InstituicaoUsuario::class, 'usuario_id');
    }

    public function scopeSearch(Builder $query, string $search = ''): Builder
    {
        if(empty($search))
        {
            return $query;
        }

        if(preg_match('/^\d+$/', $search))
        {
            return $query->where('id','like', "{$search}%");
        }

        return $query->where('descricao', 'like', "%{$search}%");
    }

    static function getLogTextos($log){
        $functions = [
            'AGENDAMENTO_CANCELADO' => "Agendamento cancelado",
            'AGENDAMETNO_FINALIZADO' => 'Agendamento finalizado',
            'ATENDIMENTO_FINALIZADO' => 'Atendimento finalizado',
            'AGENDAMENTO_CONFIRMADO' => 'Atendimento confirmado',
            'ATENDIMENTO_INICIADO' => 'Atendimento iniciado',
            'AGENDAMENTO_REATIVADO' => 'Agendamento reativado',
            'AGENDAMENTO_EXCLUIDO' => 'Agendamento removido',
            'PACIENTE_AGENDAMENTO_AUSENTE' => 'Paciente ausente',
            'HORARIO_ALTERADO' => 'Horario alterado',
            'HORARIO_CANCELADO' => 'Horario cancelado',
            'AGENDAMENTO_CRIADO' => "Agendamento criado",
            'AGENDAMENTO_EDITADO' => "Agendamento editado",
            'AGENDAMENTO_AUSENTE_AUTOMATICO' => "Agendamento ausente automatico",
            'ATENDIMENTO_INICIADO_CONSULTORIO' => "Atendimento iniciado no consultório",
            'ATENDIMENTO_RETORNADO_PENDENTE' => "Atendimento retornado para pendente",
            'DESISTENCIA_REALIZADA_NA_INSTITUICAO' => "Desistência registrada"
        ];

        return $functions[$log];
    }

    static function logAgendamento($agendamento_id, $status, $usuario_id, $funcao, $informacao = null){
       $functions = [
           'cancelar_agendamento' => 'AGENDAMENTO_CANCELADO',
           'finalizar_agendamento' => 'AGENDAMETNO_FINALIZADO',
           'finalizar_atendimento' => 'ATENDIMENTO_FINALIZADO',
           'confirmar_agendamento' => 'AGENDAMENTO_CONFIRMADO',
           'iniciar_atendimento' => 'ATENDIMENTO_INICIADO',
           'reativar_agendamento' => 'AGENDAMENTO_REATIVADO',
           'remover_agendamento' => 'AGENDAMENTO_EXCLUIDO',
           'ausente_agendamento' => 'PACIENTE_AGENDAMENTO_AUSENTE',
           'alterar_horario' => 'HORARIO_ALTERADO',
           'cancelar_horario' => 'HORARIO_CANCELADO',
           'salvarProcedimentoPaciente' => "AGENDAMENTO_CRIADO",
           'editarAgendamento' => "AGENDAMENTO_EDITADO",
           'ausenteAutomatico' => "AGENDAMENTO_AUSENTE_AUTOMATICO",
           'atender_consultorio' => "ATENDIMENTO_INICIADO_CONSULTORIO",
           'retorno_pendente' => "ATENDIMENTO_RETORNADO_PENDENTE",
           'desistencia' => "DESISTENCIA_REALIZADA_NA_INSTITUICAO"
       ];

       date_default_timezone_set("America/Fortaleza");
       
        $dados = [
            'status' => (in_array($status, ['agendado','confirmado','cancelado','pendente','finalizado','excluir','ausente','em_atendimento','finalizado_medico', 'desistencia'])) ? $status : null,
            'data' => date("Y-m-d H:i:s"),
            'log' => (!empty($functions[$funcao])) ? $functions[$funcao] : null,
            'informacao' => $informacao,
            'agendamento_id' => $agendamento_id,
            'usuario_id' => $usuario_id
        ];        

        if($dados['log']){    
            try {
                AuditoriaAgendamento::create($dados);
            }catch(\Throwable $th){
               return false;
            }

            return true;
        }else{
            return false;
        }   
    }

    public function scopeGetRelatorioAuditoria(Builder $query, $dados):Builder
    {
        if($dados['tipo'] == "data_auditoria"){
            $query->whereDate('data', '>=', $dados['data_inicio'])
                ->whereDate('data', '<=', $dados['data_fim']);
        }

        $query->whereIn('status', $dados['status']);

        if (array_key_exists('usuarios', $dados)) {
            $query->whereIn('usuario_id', $dados['usuarios']);
            $query->where('log', '<>', 'AGENDAMENTO_AUSENTE_AUTOMATICO');
        }

        $query->whereHas('agendamentos', function($q) use($dados){
            if($dados['tipo'] == "data_agendamento"){
                $q->whereDate('data', '>=', $dados['data_inicio'])
                ->whereDate('data', '<=', $dados['data_fim']);
            }
            $q->whereHas('pessoa', function($p){
                $p->where('instituicao_id', request()->session()->get('instituicao'));
            });
        });

        return $query;
    }
}
