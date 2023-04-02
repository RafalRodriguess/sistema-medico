<?php

namespace App;

use App\Support\ModelPossuiLogs;
use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CentroCirurgico extends Model
{
    use SoftDeletes;
    use TraitLogInstituicao;

    protected $table = 'centros_cirurgicos';

    protected $fillable = [
        'id',
        'descricao',
        'cc_id',
        'instituicao_id',
    ];

    public function instituicao()
    {
        return $this->belongsTo(Instituicao::class, 'id');
    }

    public function centroCusto()
    {
        return $this->belongsTo(CentroCusto::class, 'cc_id');
    }

    public function horarioFuncionamento()
    {
        return $this->hasOne(HorarioFuncionamento::class, 'centro_cirurgico_id');
    }

    public function salasCirurgicas()
    {
        return $this->hasMany(SalaCirurgica::class, 'centro_cirurgico_id');
    }

    public function agendamentos()
    {
        return $this->hasMany(AgendamentoCentroCirurgico::class, 'centro_cirurgico_id');
    }

    public function scopeSearch(Builder $query, string $search = ''): Builder
    {
        if(empty($search)) return $query;

        if(preg_match('/^\d+$/', $search)) return $query->where('id','like', "{$search}%");

        return $query->where('descricao', 'like', "%{$search}%");
    }
}
