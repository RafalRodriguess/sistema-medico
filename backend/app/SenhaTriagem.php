<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\{
    FilaTotem,
    ClassificacaoTriagem
};
use App\Casts\Checkbox;
use App\Support\BaseClass;
use App\Support\ModelAceitaGanchos;
use App\Support\ModelOverwrite;
use App\Support\ModelPossuiLogs;
use Hamcrest\Type\IsInteger;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\Relation;
use stdClass;

class SenhaTriagem extends ModelAceitaGanchos
{
    use ModelPossuiLogs;
    use ModelOverwrite;

    protected $table = 'senhas_triagem';
    protected $fillable = [
        'filas_totem_id',
        'horario_retirada',
        'valor',
        'classificacoes_triagem_id',
        'queixa',
        'sinais_vitais',
        'pessoa_id',
        'horario_triagem',
        'encerrado',
        'primeiro_atendimento',
        'reincidencia',
        'doencas_cronicas',
        'alergias',
        'prestador_id',
        'chamado',
        'paciente_nome',
        'paciente_mae',
        'paciente_cpf',
    ];
    const UPDATED_AT = 'horario_triagem';
    const CREATED_AT = 'horario_retirada';

    protected $casts = [
        'primeiro_atendimento' => Checkbox::class,
        'reincidencia' => Checkbox::class
    ];

    protected $allowed_overwrite = [
        EspecialidadeTriagem::class,
        AgendamentoAtendimentoUrgencia::class
    ];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $ultima_senha = SenhaTriagem::where('filas_totem_id', $model->filas_totem_id)->orderBy('id', 'desc')->first();
            if ($ultima_senha && date_create($ultima_senha->horario_retirada)->format('Y-m-d') == date('Y-m-d'))
                $model->valor = $ultima_senha->valor + 1;
            else
                $model->valor = 0;
        });
    }

    public function getStatusAttribute()
    {
        $chamada = $this->chamadaTotem()->first();
        $status = BaseClass::make([
            'id' => $chamada->origem_chamada ?? 0,
            'icone_status' => '',
            'etapa_completa' => !empty($chamada->etapa_completa) ? 1 : 0,
            'etapa' => !empty($chamada) ? $chamada->origem_chamada : 0
        ]);
        $status->etapa += $status->etapa_completa;

        switch ($status->id) {
            case 0:
                if ($status->etapa_completa == 0) {
                    if ($this->chamado == 0) {
                        // Paciente retirou a senha e aguarda ser chamado para atendimento na recepção
                        $status->cor_status = 'status-2';
                        $status->emoji_status = '<span data-toggle="tooltip" title="Paciente aguardando ser chamado na recepção"><i class="fas fa-clock"></i></span>';
                    } else {
                        // Paciente retirou a senha e foi chamado para atendimento na recepção ou está sendo atendido
                        $status->cor_status = 'status-2';
                        $status->emoji_status = '<span data-toggle="tooltip" title="Paciente chamado na recepção"><i class="fa fa-bullhorn"></i></span>';
                    }
                } else {
                    // Paciente aguardando triagem
                    $status->cor_status = 'status-1';
                    $status->emoji_status = '<span data-toggle="tooltip" title="Paciente aguardando triagem" class="fas fa-clock"></span>';
                }
                break;
            case 1:
                if ($status->etapa_completa == 0) {
                    if ($this->chamado == 0) {
                        // Paciente já passou pela recepção e aguarda triagem
                        $status->cor_status = 'status-1';
                        $status->emoji_status = '<span data-toggle="tooltip" title="Paciente aguardando ser chamado na triagem"><i class="fas fa-clock"></i></span>';
                    } else {
                        // Paciente foi chamado para triagem e pode estar sendo triado
                        $status->cor_status = 'status-1';
                        $status->emoji_status = '<span data-toggle="tooltip" title="Paciente chamado para triagem"><i class="fa fa-bullhorn"></i></span>';
                    }
                } else {
                    // Paciente foi triado e aguarda outros procedimentos
                    $status->cor_status = 'status-3';
                    $status->emoji_status = '<span data-toggle="tooltip" title="O paciente foi triado e aguarda atendimento" class="fas fa-smile"></span>';
                    $status->icone_status = '<span data-toggle="tooltip" title="Paciente triado" class="mx-1"><i class="fas fa-stethoscope"></i></span>';
                }
                break;
            default:
                if ($status->etapa_completa == 0) {
                    if ($this->chamado == 0) {
                        // Paciente retirou a senha e aguarda ser chamado para atendimento na recepção
                        $status->cor_status = 'status-3';
                        $status->emoji_status = '<span data-toggle="tooltip" title="Paciente aguardando ser chamado para atendimento"><i class="fas fa-clock"></i></span>';
                    } else {
                        // Paciente foi chamado para atendimento ou está sendo atendido no consultório
                        $status->cor_status = 'status-3';
                        $status->emoji_status = '<span data-toggle="tooltip" title="Paciente chamado para atendimento"><i class="fa fa-bullhorn"></i></span>';
                    }
                } else {
                    // Paciente foi atendido
                    $status->cor_status = 'status-5';
                    $status->icone_status .= '<span data-toggle="tooltip" title="Paciente foi atendido no consultório" class="mx-1"><i class="fas fa-heart"></i></span>';
                    $status->emoji_status = '<span data-toggle="tooltip" title="O paciente foi atendido"><i class="far fa-heart"></i></span>';
                }
                // Icone de triagem
                $status->icone_status .= '<span data-toggle="tooltip" title="Paciente triado" class="mx-1"><i class="fas fa-stethoscope"></i></span>';
        }

        if ($this->chamado == 1) {
            $status->icone_status .= '<span data-toggle="tooltip" title="Paciente foi chamado" class="mx-1"><i class="fa fa-bullhorn"></i></span>';
        }

        if ($this->filaTriagem()->first()->prioridade == 1) {
            $status->icone_status = '<span data-toggle="tooltip" title="Fila com prioridade" class="mx-1"><i class="fas fa-exclamation"></i></span>' . $status->icone_status;
        }

        return $status;
    }

    function setStatusAttribute($status)
    {
        $chamada = $this->chamadaTotem()->first();
        $opcoes = array_keys(ChamadaTotem::origens_chamada);
        $status = clamp($status, count($opcoes) - 1, -1, true);

        if (empty($chamada) && $status >= 0) {
            $this->chamadaTotem()->create([
                'origem_chamada' => $status,
                'instituicoes_id' => request()->session()->get('instituicao')
            ]);
        } else if ($status >= 0) {
            $chamada->update([
                'origem_chamada' => $status
            ]);
        } else if (!empty($chamada)) {
            $chamada->delete();
        }
    }

    public function instituicao(): Relation
    {
        try {
            return $this->totem()->first()->instituicao();
        } catch (\Exception $e) {
            return null;
        }
    }

    public function filaTriagem()
    {
        return $this->hasOneThrough(FilaTriagem::class, FilaTotem::class, 'id', 'id', 'filas_totem_id', 'filas_triagem_id');
    }

    public function totem()
    {
        return $this->hasOneThrough(Totem::class, FilaTotem::class, 'id', 'id', 'filas_totem_id', 'totens_id');
    }

    public function fila()
    {
        return $this->belongsTo(FilaTotem::class, 'filas_totem_id');
    }

    public function classificacao()
    {
        return $this->belongsTo(ClassificacaoTriagem::class, 'classificacoes_triagem_id');
    }

    public function paciente()
    {
        return $this->belongsTo(Pessoa::class, 'pessoa_id');
    }

    public function getPaciente()
    {
        $paciente = $this->paciente()->first();
        if (empty($paciente)) {
            return BaseClass::make([
                'nome' => $this->paciente_nome,
                'cpf' => $this->paciente_cpf,
                'nome_mae' => $this->paciente_mae,
                'personalidade' => 1,
                'tipo' => 2
            ]);
        }
        return $paciente;
    }

    public function atendimentoUrgencia()
    {
        return $this->hasOne(AgendamentoAtendimentoUrgencia::class, 'senhas_triagem_id');
    }

    public function concluirEtapa()
    {
        ChamadaTotem::completarChamada($this);
    }

    /**
     * Método que limpa a triagem e converte em
     * uma senha normal
     */
    public function clearTriagem()
    {
        $senha = SenhaTriagem::where('id', $this->id)->first();
        $senha->update([
            'classificacoes_triagem_id' => null,
            'queixa' => null,
            'sinais_vitais' => null,
            'horario_triagem' => null,
            'doencas_cronicas' => null,
            'alergias' => null,
            'primeiro_atendimento' => 0,
            'reincidencia' => 0,
            'pessoa_id' => null
        ]);
        $senha->status = null;
    }

    public function scopeSearch(Builder $query, string $search = ''): Builder
    {
        if (empty($search)) {
            return $query;
        }

        if (preg_match('/^\d+$/', $search)) {
            return $query->where('senhas_triagem.id', 'like', "{$search}%");
        }

        return $query->leftJoin('pessoas', 'pessoas.id', 'pessoa_id')
            ->join('filas_totem', 'filas_totem.id', 'filas_totem_id')
            ->join('filas_triagem', 'filas_triagem.id', 'filas_triagem_id')
            ->where(function ($query) use ($search) {
                $query->whereRaw("CONCAT(filas_triagem.identificador, senhas_triagem.valor) LIKE '%{$search}%'")
                    ->orWhere('nome_paciente', 'like', "%{$search}%")
                    ->orWhere('instituicao_usuarios.nome', 'like', "%{$search}%");
            });
    }

    public function getClass(): string
    {
        return self::class;
    }

    public function getSenhaAttribute()
    {
        $identificador = $this->filaTriagem()->first();
        return (!empty($identificador) ? $identificador->identificador : '') . $this->attributes['valor'];
    }

    public function chamadaTotem()
    {
        return $this->hasOne(ChamadaTotem::class, 'senhas_triagem_id');
    }

    public function especialidadesTriagem()
    {
        return $this->hasMany(EspecialidadeTriagem::class, 'triagem_id');
    }

    public function especialidades()
    {
        return $this->hasManyThrough(Especialidade::class, EspecialidadeTriagem::class, 'triagem_id', 'id', 'id', 'especialidades_id');
    }

    public function prestador()
    {
        return $this->belongsTo(Prestador::class, 'prestador_id');
    }
}
