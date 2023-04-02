<?php

namespace App;
use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AgendamentoCentroCirurgico extends Model
{
    use TraitLogInstituicao;
    use SoftDeletes;

    protected $table = "agendamentos_centro_cirurgico";

    protected $fillable = [
        'centro_cirurgico_id',
        'sala_cirurgica_id',
        'cirurgia_id',
        'prestador_id',
        'tipo',
        'data',
        'hora_inicio',
        'hora_final',
        'paciente_id',
        'acomodacao_id',
        'unidade_internacao_id',
        'via_acesso_id',
        'anestesista_id',
        'tipo_anestesia_id',
        'cid_id',
        'pacote',
        'obs',
        'sala_cirurgica_entrada',
        'sala_cirurgica_saida',
        'anestesia_inicio',
        'anestesia_fim',
        'cirurgia_inicio',
        'cirurgia_fim',
        'limpeza_inicio',
        'limpeza_fim',
        'status',
        'saida_estoque_id',
        'tipo_paciente',
        'agendamento_id',
        'urgencia_id',
        'internacao_id',
    ];

    protected $casts = [
        'data' => 'date',
        'hora_inicio' => 'time',
        'hora_final' => 'time',
        'pacote' => 'boolean',
        'sala_cirurgica_entrada' => 'time',
        'sala_cirurgica_saida' => 'time',
        'anestesia_inicio' => 'time',
        'anestesia_fim' => 'time',
        'cirurgia_inicio' => 'time',
        'cirurgia_fim' => 'time',
        'limpeza_inicio' => 'time',
        'limpeza_fim' => 'time',
    ];


    public static function retornaStatus($status)
    {
        $dados = [
            // 'pendente' => 'pendente',
            'pendente' => 'confirmado',
            'confirmado' => 'em_atendimento',
            'em_atendimento' => 'finalizado',
        ];

        return $dados[$status];
    }

    public function pessoa()
    {
        return $this->belongsTo(Pessoa::class, 'paciente_id');
    }

    public function ambulatorio()
    {
        return $this->belongsTo(Agendamentos::class, 'paciente_id');
    }

    public function urgencia()
    {
        return $this->belongsTo(AgendamentoAtendimentoUrgencia::class, 'paciente_id');
    }

    public function internacao()
    {
        return $this->belongsTo(Internacao::class, 'paciente_id');
    }
    
    public function cirurgia()
    {
        return $this->belongsTo(Cirurgia::class, 'cirurgia_id');
    }

    public function cirurgiao()
    {
        return $this->belongsTo(Prestador::class, 'prestador_id');
    }
    
    public function anestesista()
    {
        return $this->belongsTo(Prestador::class, 'anestesista_id');
    }

    public function salaCirurgica()
    {
        return $this->belongsTo(SalaCirurgica::class, 'sala_cirurgica_id');
    }

    public function centroCirurgico()
    {
        return $this->belongsTo(CentroCirurgico::class, 'centro_cirurgico_id');
    }
    
    public function cid()
    {
        return $this->belongsTo(Cid::class, 'cid_id');
    }

    public function equipamentos()
    {
        return $this->belongsToMany(Equipamento::class, 'agendamentos_centro_cirurgico_has_equipamentos', 'agendamento_centro_cirurgico_id', 'equipamento_id')->withPivot('quantidade');
    }
    
    public function caixasCirurgicas()
    {
        return $this->belongsToMany(CaixaCirurgico::class, 'agendamentos_centro_cirurgico_has_caixas_cirurgicas', 'agendamento_centro_cirurgico_id', 'caixa_cirurgica_id')->withPivot('quantidade');
    }
    
    public function outrasCirurgias()
    {
        return $this->belongsToMany(Cirurgia::class, 'agendamentos_centro_cirurgico_has_cirurgias', 'agendamento_centro_cirurgico_id', 'cirurgia_id')->withPivot('via_acesso_id', 'convenio_id', 'cirurgiao_id', 'pacote', 'tempo');
    }
    
    public function outrasCirurgiasCirurgiao()
    {
        return $this->belongsToMany(Prestador::class, 'agendamentos_centro_cirurgico_has_cirurgias', 'agendamento_centro_cirurgico_id', 'cirurgiao_id')->withPivot('via_acesso_id', 'convenio_id', 'cirurgia_id', 'pacote', 'tempo');
    }
    
    public function produtos()
    {
        return $this->belongsToMany(Produto::class, 'agendamentos_centro_cirurgico_has_produtos', 'agendamento_centro_cirurgico_id', 'produto_id')->withPivot('fornecedor_id', 'quantidade', 'obs', 'lote_id', 'saida_estoque_produto_id');
    }
    
    public function sangueDerivados()
    {
        return $this->belongsToMany(SangueDerivado::class, 'agendamentos_centro_cirurgico_has_sangues_derivados', 'agendamento_centro_cirurgico_id', 'sangue_derivado_id')->withPivot('quantidade');
    }

    public function saidaEstoque()
    {
        return $this->belongsTo(SaidaEstoque::class, 'saida_estoque_id');
    }

    public function scopeSearch(Builder $query, $dados, $hora_inicio = null, $hora_fim = null): Builder
    {

        $query->where(function($q) use($dados){
            $q->whereDate('data', $dados['data']);
            $q->orWhereDate('hora_final', $dados['data']);
        });

        if($hora_inicio){
            $query->where(function($q) use($hora_fim, $hora_inicio){
                $q->whereBetween('hora_inicio', [$hora_inicio, $hora_fim]);
                $q->orWhere(function($q1) use($hora_fim, $hora_inicio){
                    $q1->whereBetween('hora_final', [$hora_inicio, $hora_fim]);
                });
            });
        }

        $query->orderBy('hora_inicio', 'ASC');

        return $query;
    }
}
