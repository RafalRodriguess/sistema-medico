<?php

namespace App;

use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AgendamentoAtendimento extends Model
{
    use SoftDeletes;
    use TraitLogInstituicao;

    protected $table = 'agendamento_atendimentos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'agendamento_id',
        'pessoa_id',
        'data_hora',
        'tipo',
        'status',
        'internacao_id',
        // 'profissional_id'
    ];

    protected $casts = [
        'data_hora' => 'datetime'
    ];

    //TIPOS
    const ambulatorio = 1;
    const urgencia_emergencia = 2;
    const internação = 3;

    public static function getTipo()
    {
        return [
            self::ambulatorio,
            self::urgencia_emergencia,
            self::internação,
        ];
    }

    public static function getTipoText($value)
    {
        $dados = [
            self::ambulatorio => 'Ambulatorio',
            self::urgencia_emergencia => 'Urgencia Emergencia',
            self::internação => 'Internação',
        ];

        return $dados[$value];
    }

    //STATUS
    const em_atendimento = 1;
    const finalizado = 2;

    public static function getStatus()
    {
        return [
            self::em_atendimento,
            self::finalizado,
        ];
    }

    public static function getStatusText($value)
    {
        $dados = [
            self::em_atendimento => 'Em atendimento',
            self::finalizado => 'Finalizado',

        ];

        return $dados[$value];
    }


    public function agendamento()
    {
        return $this->belongsTo(Agendamentos::class, 'agendamento_id');
    }

    public function pessoa()
    {
        return $this->belongsTo(Pessoa::class, 'pessoa_id');
    }

    public function internacao(){
        return $this->belongsToMany(Internacao::class, 'internacao_id');
    }

    // COLUNA REMOVIDA DO DB
    // public function profissional()
    // {
    //     return $this->belongsTo(Prestador::class, 'profissional_id');
    // }

    public static function buscarAgendamentoPorPacientes($instituicao)
    {
        return self::with([
            'pessoa',
            'agendamento',
            'agendamento.instituicoesPrestadores',
            'agendamento.instituicoesPrestadores.prestador',
            'agendamento.instituicoesPrestadores.instituicao'
        ])
        ->whereHas('agendamento.instituicoesPrestadores.instituicao', function(Builder $query) use ($instituicao) {
            $query->where('instituicoes.id', '=', $instituicao->id);
        });
    }
}
