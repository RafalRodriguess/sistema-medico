<?php

namespace App;

use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PreInternacao extends Model
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
        'internacao_id'
			
    ];

    protected $casts = [
        'previsao' => "datetime",
    ];

    public function scopeSearch(Builder $query, string $search = '', $paciente_id = 0, $medico_id = 0): Builder
    {
        if($paciente_id != 0){
            $query->whereHas('paciente', function($q) use($paciente_id){
                $q->where('id', $paciente_id);
            });
        }
        
        if($medico_id != 0){
            $query->whereHas('medico', function($q) use($medico_id){
                $q->where('id', $medico_id);
            });
        }
        
        if(empty($search)){
            return $query;
        }

        if(preg_match('/^\d+$/', $search)){
            return $query->where('id','like', "{$search}%");
        }

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

    public function Especialidade()
    {
        return $this->belongsTo(Especialidade::class, 'especialidade_id');
    }

    public function procedimentos()
    {
        return $this->belongsToMany(ConveniosProcedimentos::class, 'internacao_procedimentos', 'internacao_id', 'proc_conv_id', 'id')->withPivot(['valor', 'quantidade_procedimento']);
        // return $this->belongsToMany(ConveniosProcedimentos::class, 'internacao_procedimentos', 'internacao_id', 'proc_conv_id', 'id');
    }


}
