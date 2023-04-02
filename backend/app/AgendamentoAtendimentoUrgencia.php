<?php

namespace App;

use App\Support\ModelOverwrite;
use App\Support\ModelPossuiLogs;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class AgendamentoAtendimentoUrgencia extends Model
{
    use ModelPossuiLogs;
    use ModelOverwrite;

    protected $table = 'agendamentos_atendimentos_urgencia';
    protected $fillable = [
        // 'same',
        'senhas_triagem_id',
        'especialidades_id',
        'id_prestador',
        'origens_id',
        'local_procedencia_id',
        'destino_id',
        'atendimentos_id', // Carater de atendimento
        'agendamento_id',
        'data_hora',
        'cid',
        'observacoes',
        'instituicao_id',
        'carteirinha_id'
    ];

    protected $allowed_overwrite = [
        ProcedimentoAtendimentoUrgencia::class
    ];

    public function paciente()
    {
        return $this->hasOneThrough(Pessoa::class, AgendamentoAtendimento::class, 'id', 'id', 'agendamento_id', 'pessoa_id');
    }

    public function carteirinha()
    {
        return $this->belongsTo(Carteirinha::class, 'carteirinha_id');
    }

    public function instituicao()
    {
        return $this->belongsTo(Instituicao::class, 'instituicao_id');
    }

    public function agendamentoAtendimento()
    {
        return $this->belongsTo(AgendamentoAtendimento::class, 'agendamento_id');
    }

    public function senhaTriagem()
    {
        return $this->belongsTo(SenhaTriagem::class, 'senhas_triagem_id');
    }

    public function especialidade()
    {
        return $this->belongsTo(Especialidade::class, 'especialidades_id');
    }

    public function prestador()
    {
        return $this->belongsTo(Prestador::class, 'id_prestador');
    }

    public function origem()
    {
        return $this->belongsTo(Origem::class, 'origens_id');
    }

    public function procedencia()
    {
        return $this->belongsTo(Origem::class, 'local_procedencia_id');
    }

    public function destino()
    {
        return $this->belongsTo(Origem::class, 'destino_id');
    }

    public function caraterAtendimento()
    {
        return $this->belongsTo(Atendimento::class, 'atendimentos_id');
    }

    public function procedimentosAtendimentoUrgencia()
    {
        return $this->hasMany(ProcedimentoAtendimentoUrgencia::class, 'atendimento_urgencia_id');
    }

    public function getDataAttribute()
    {
        return date_format(date_create($this->attributes['data_hora']), 'd/m/Y');
    }

    public function getHoraAttribute()
    {
        return date_format(date_create($this->attributes['data_hora']), 'H:i');
    }

    public function setDataAttribute($value)
    {
        $value = collect(explode('-', str_replace('/', '-', $value)))->reverse()->implode('-');
        $this->attributes['data_hora'] = "$value " . explode(' ',$this->attributes['data_hora'])[1];
    }

    public function setHoraAttribute($value)
    {
        switch(count(explode(':', $value)) <=> 3) {
            case (-1):
                $value .= ':00';
            case 1:
                $value = implode(':',  array_slice(explode(':', $value), 0, 3));
        }
        $this->attributes['data_hora'] = explode(' ',$this->attributes['data_hora'])[0] . " $value";
    }

    public function scopeGetCentroCirurgicoUrgencia(Builder $query, string $nome = "", int $instituicao):Builder
    {
        $query->where('data_hora', '>=', date('Y-m-d', strtotime('-1 days')).' 00:00:00')
        ->where('instituicao_id', $instituicao)
        ->whereHas('senhaTriagem.paciente', function($q) use($nome, $instituicao){
            $q->where('instituicao_id', $instituicao);
            $q->where('tipo', '<>', 3);

            if(!empty($nome)){
                $q->where('nome', 'like', "%{$nome}%")
                ->orWhere('nome_fantasia', 'like', "%{$nome}%")
                ->orWhere('cpf', 'like', "%{$nome}%");
            }
        })->with(['senhaTriagem.paciente' => function($q) use($nome, $instituicao){

            $q->where('instituicao_id', $instituicao);
            $q->where('tipo', '<>', 3);

            if(!empty($nome)){
                $q->where('nome', 'like', "%{$nome}%")
                    ->orWhere('nome_fantasia', 'like', "%{$nome}%")
                    ->orWhere('cpf', 'like', "%{$nome}%");
            }
        }, 'senhaTriagem.classificacao'])
        ->orderBy('data_hora', 'DESC');

        return $query;
    }
}
