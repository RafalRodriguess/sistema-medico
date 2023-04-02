<?php

namespace App;

use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EscalaMedica extends Model
{
    use SoftDeletes;
    use TraitLogInstituicao;

    protected $table = 'escalas_medicas';

    protected $fillable = [
        'id',
        'especialidade_id',
        'data',
        'regra',
        'origem_id',
        // 'horario_inicio',
        // 'horario_termino',
        'instituicao_id',
    ];

    public function dataFormatada()
    {
        $data = date_create($this->data);
        return date_format($data, 'd/m/Y');
    }

    public function instituicao()
    {
        return $this->belongsTo(Instituicao::class, 'instituicao_id');
    }

    public function origem()
    {
        return $this->belongsTo(Origem::class, 'origem_id');
    }

    public function especialidade()
    {
        return $this->belongsTo(Especialidade::class, 'especialidade_id');
    }

    public function escalaPrestadores()
    {
        return $this->belongsToMany(Prestador::class, 'escalas_prestadores', 'escala_medica_id', 'prestador_id')->withPivot('entrada', 'saida', 'observacao');
    }

    public function scopeSearchByEspecialidade(Builder $query, int $especialidade_id = 0): Builder
    {
        if ($especialidade_id != 0) $query->where('especialidade_id', '=', $especialidade_id);

        return $query;
    }

    public function scopeSearchByRegra(Builder $query, string $regra = ''): Builder
    {
        if ($regra != '') $query->where('regra', 'like', "%{$regra}%");

        return $query;
    }

    public function scopeSearchByOrigem(Builder $query, int $origem_id = 0): Builder
    {
        if ($origem_id != 0) $query->where('origem_id', '=', $origem_id);

        return $query;
    }

}
