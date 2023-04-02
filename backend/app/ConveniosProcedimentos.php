<?php

namespace App;
use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class ConveniosProcedimentos extends Model
{
	use SoftDeletes;
	use TraitLogInstituicao;



	protected $table = 'procedimentos_instituicoes_convenios';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'id',
    	'valor',
        'valor_convenio',
        'procedimentos_instituicoes_id',
        'convenios_id',
        'sancoop_cod_procedimento',
        'sancoop_desc_procedimento',
        'codigo',
        'utiliza_parametro_convenio',
        'carteirinha_obrigatoria',
        'aut_obrigatoria',
    ];

    public function procedimento()
    {
        return $this->belongsTo(Procedimento::class, 'procedimentos_instituicoes_id', 'id');
    }

    public function procedimentoInstituicao()
    {
        return $this->belongsTo(InstituicaoProcedimentos::class, 'procedimentos_instituicoes_id', 'id');
    }

    public function procedimentoInstituicaoExcluidos()
    {
        return $this->belongsTo(InstituicaoProcedimentos::class, 'procedimentos_instituicoes_id', 'id')->withTrashed();
    }

    public function convenios()
    {
        return $this->belongsTo(Convenio::class, 'convenios_id', 'id');
    }
    
    public function conveniosTrashed()
    {
        return $this->belongsTo(Convenio::class, 'convenios_id', 'id')->withTrashed();
    }

    public function agendamentoProcedimento()
    {
        return $this->hasMany(AgendamentoProcedimento::class, 'procedimentos_instituicoes_convenios_id');
    }

    public function repasseMedico()
    {
        return $this->belongsToMany(Prestador::class, 'procedimentos_convenios_has_repasse_medico', 'procedimento_instituicao_convenio_id', 'prestador_id')->withPivot('tipo', 'valor_repasse', 'valor_cobrado', 'tipo_cartao', 'valor_repasse_cartao');
    }
    
    public function repasseMedicoId($id)
    {
        return $this->belongsToMany(Prestador::class, 'procedimentos_convenios_has_repasse_medico', 'procedimento_instituicao_convenio_id', 'prestador_id')->withPivot('tipo', 'valor_repasse', 'tipo_cartao', 'valor_repasse_cartao')->wherePivot('prestador_id', $id);
    }

    public function orcamentosItens()
    {
        return $this->hasMany(OdontologicoItemPaciente::class, 'procedimento_instituicao_convenio_id');
    }

    public function procedimentosExtra()
    {
        return $this->belongsToMany(Procedimento::class, 'procedimentos_convenios_has_procedimentos_extra', 'procedimento_instituicao_convenio_id', 'procedimento_id')->withPivot('quantidade', 'grupo_faturamento_id');
    }
}
