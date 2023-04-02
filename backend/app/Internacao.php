<?php

namespace App;

use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Internacao extends Model
{
    use SoftDeletes;
    use TraitLogInstituicao;

    protected $table = 'internacoes';

    protected $fillable = [
        'id',
        'paciente_id',
        'origem_id',
        'medico_id',
        'especialidade_id',
        'cid_id',
        'unidade_id',
        'acompanhante',
        'tipo_internacao',
        'leito_id',
        'reserva_leito',
        'pre_internacao',
        'observacao',
        'acomodacao_id',
        'previsao',
        'possui_responsavel',
        'parentesco_responsavel',
        'nome_responsavel',
        'estado_civil_responsavel',
        'profissao_responsavel',
        'nacionalidade_responsavel',
        'telefone1_responsavel',
        'telefone2_responsavel',
        'identidade_responsavel',
        'cpf_responsavel',
        'contato_responsavel',
        'cep_responsavel',
        'endereco_responsavel',
        'numero_responsavel',
        'complemento_responsavel',
        'bairro_responsavel',
        'cidade_responsavel',
        'uf_responsavel',
        'status',
        'internacao_id',
        'atendimento_id',
        'previsao_alta',
        'instituicao_transferencia_id',
        'data_transferencia',
        'obs_transferencia',
        'alta_internacao',
        'alta_hospitalar'
			
    ];

    protected $casts = [
        'previsao' => "datetime",
    ];

    public function scopeSearch(Builder $query, string $search = '', $paciente_id = 0, $medico_id = 0, $data = null, $previsao_alta = 0, $tipo_internacao = 0, $convenio_id = 0, $acomodacao_id = 0, $unidade_id = 0, $leito_id = 0): Builder
    {
        if($paciente_id != 0){
            $query->whereHas('paciente', function($q) use($paciente_id){
                $q->where('id', $paciente_id);
            });
        }

        if($acomodacao_id != 0){
            $query->whereHas('internacaoLeitos', function($q) use($acomodacao_id){
                $q->where('acomodacao_id', $acomodacao_id)->limit(1);
            });
        }

        if($unidade_id != 0){
            $query->whereHas('internacaoLeitos', function($q) use($unidade_id){
                $q->where('unidade_id', $unidade_id);
            });
        }

        if($leito_id != 0){
            $query->whereHas('internacaoLeitos', function($q) use($leito_id){
                $q->where('leito_id', $leito_id);
            });
        }
        
        if($medico_id != 0){
            $query->whereHas('medico', function($q) use($medico_id){
                $q->where('id', $medico_id);
            });
        }

        if($tipo_internacao != 0){
            $query->where('tipo_internacao', $tipo_internacao);
        }

        if(!empty($data)){
            
            if($previsao_alta == 0){
                $query->whereBetween('created_at', [$data." 00:00:00", $data." 23:59:59"]);
            }else if($previsao_alta == 1){
                $query->whereBetween('previsao_alta', [$data." 00:00:00", $data." 23:59:59"]);
            }
        }

        if($convenio_id != 0){
            $query->whereHas('procedimentos', function($q) use($convenio_id){
                $q->where('convenio_id', $convenio_id);
            });
        }
        
        if(empty($search)){
            return $query;
        }

        if(preg_match('/^\d+$/', $search)){
            return $query->where('id','like', "{$search}%");
        }

        // dump($query->toSql());

        return $query->where('descricao', 'like', "%{$search}%");
    }

    public function paciente()
    {
        return $this->belongsTo(Pessoa::class, 'paciente_id');
    }

    public function medico()
    {
        return $this->belongsTo(Prestador::class, 'medico_id');
    }

    public function especialidade()
    {
        return $this->belongsTo(Especialidade::class, 'especialidade_id');
    }

    public function alta()
    {
        return $this->hasMany(Alta::class, 'internacao_id');
    }

    public function altaHospitalar()
    {
        return $this->hasMany(AltaHospitalar::class, 'internacao_id');
    }    

    public function atendimento()
    {
        return $this->belongsTo(AgendamentoAtendimento::class, 'atendimento_id');
    }

    public function agendamentos()
    {
        return $this->hasMany(Agendamentos::class, 'internacao_id');
    }

    public function origem()
    {
        return $this->belongsTo(Origem::class, 'origem_id');
    }

    public function acomodacao()
    {
        return $this->belongsTo(Acomodacao::class, 'acomodacao_id');
    }

    public function unidade()
    {
        return $this->belongsTo(UnidadeInternacao::class, 'unidade_id');
    }

    public function cid()
    {
        return $this->belongsTo(Cid::class, 'cid_id');
    }

    public function procedimentos()
    {
        return $this->belongsToMany(ConveniosProcedimentos::class, 'internacao_procedimentos', 'internacao_id', 'proc_conv_id', 'id')->withPivot(['valor', 'quantidade_procedimento']);
        // return $this->belongsToMany(ConveniosProcedimentos::class, 'internacao_procedimentos', 'internacao_id', 'proc_conv_id', 'id');
    }

    public function internacaoLeitos()
    {
        return $this->hasMany(InternacaoLeito::class, 'internacao_id');
    }

    public function internacaoMedicos()
    {
        return $this->hasMany(InternacaoMedico::class, 'internacao_id');
    }
 
    public function scopeGetCentroCirurgicoInternacao(Builder $query, string $nome = "", int $instituicao):Builder
    {
        $query->where('alta_hospitalar', 0)
        ->where('alta_internacao', 0)
        ->whereHas('paciente', function($q) use($nome, $instituicao){
            $q->where('instituicao_id', $instituicao);
            $q->where('tipo', '<>', 3);

            if(!empty($nome)){
                $q->where('nome', 'like', "%{$nome}%")
                ->orWhere('nome_fantasia', 'like', "%{$nome}%")
                ->orWhere('cpf', 'like', "%{$nome}%");
            }
            $q->orderBy('nome', 'DESC');
        })->with(['paciente' => function($q) use($nome, $instituicao){

            $q->where('instituicao_id', $instituicao);
            $q->where('tipo', '<>', 3);

            if(!empty($nome)){
                $q->where('nome', 'like', "%{$nome}%")
                    ->orWhere('nome_fantasia', 'like', "%{$nome}%")
                    ->orWhere('cpf', 'like', "%{$nome}%");
            }
            $q->orderBy('nome', 'DESC');
        }]);

        return $query;
    }
}