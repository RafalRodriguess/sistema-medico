<?php

namespace App;

use App\Support\ModelPossuiLogs;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MotivoCancelamentoExame extends Model
{
    use SoftDeletes;
    use ModelPossuiLogs;

    protected $table = 'motivos_cancelamento_exame';

    protected $fillable = [
        'descricao',
        'tipo',
        'ativo',
        'procedimento_instituicao_id',
    ];

    const tipos = [
        'administrativo',
        'médico',
        'paciente',
        'transferência'
    ];

    // Validando se o registro existe
    public function setProcedimentoInstituicaoIdAttribute($value)
    {
        // Verifica se o procedimento selecionado é um exame antes de inserir
        if ($value != null && InstituicaoProcedimentos::findOrFail($value)->procedimento->tipo == 'exame')
            $this->attributes['procedimento_instituicao_id'] = $value;
        else
            $this->attributes['procedimento_instituicao_id'] = null;
    }

    public function instituicaoProcedimento()
    {
        return $this->belongsTo(InstituicaoProcedimentos::class, 'procedimento_instituicao_id');
    }

    public function procedimento()
    {
        return $this->instituicaoProcedimento()->first()->procedimento();
    }

    public function instituicao()
    {
        return $this->instituicaoProcedimento()->first()->instituicao();
    }

    public function scopeSearch(Builder $query, string $search = ''): Builder
    {
        if (empty($search)) {
            return $query;
        }

        if (preg_match('/^\d+$/', $search)) {
            return $query->where('id', 'like', "{$search}%");
        }

        return $query->where('descricao', 'like', "%{$search}%");
    }
}
