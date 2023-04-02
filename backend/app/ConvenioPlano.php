<?php

namespace App;

use App\Support\ModelPossuiLogs;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use App\Casts\Checkbox;
use App\Support\TraitLogInstituicao;

class ConvenioPlano extends Model
{

    use SoftDeletes;
    use TraitLogInstituicao;

    protected $table = 'convenios_planos';

    protected $fillable = [
        'id',
        'nome',
        'convenios_id',
        'ativo',
        'descricao',
        'paga_acompanhante',
        'validade_indeterminada',
        'senha_guia_obrigatoria',
        'valida_guia',
        'permissao_internacao',
        'permissao_emergencia',
        'permissao_home_care',
        'permissao_ambulatorio',
        'permissao_externo',
        'regra_cobranca_id'
    ];

    /**
     * Cast de checkbox aceita on e 1 como true
     */
    protected $casts = [
        'validade_indeterminada' => Checkbox::class,
        'paga_acompanhante' => Checkbox::class,
        'senha_guia_obrigatoria' => 'boolean',
        'valida_guia' => 'boolean',
        'permissao_internacao' => Checkbox::class,
        'permissao_emergencia' => Checkbox::class,
        'permissao_home_care' => Checkbox::class,
        'permissao_ambulatorio' => Checkbox::class,
        'permissao_externo' => Checkbox::class,
    ];

    public function convenios()
    {
        return $this->belongsTo(Convenio::class, 'convenios_id');
    }

    public function regraCobranca()
    {
        return $this->belongsTo(RegraCobranca::class, 'regra_cobranca_id');
    }

    public function scopeSearch(Builder $query, string $search = ''): Builder
    {
        if(empty($search))
        {
            return $query->orderBy('id', 'desc');
        }

        if(preg_match('/^\d+$/', $search))
        {
            return $query->where('id','like', "{$search}%")->orderBy('id', 'desc');
        }

        return $query->where('nome', 'like', "%{$search}%")->orderBy('id', 'desc');
    }
}
