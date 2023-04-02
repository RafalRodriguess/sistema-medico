<?php

namespace App;

use App\Hooks\Interfaces\ModelDisparaGanchos;
use App\Support\ModelPossuiLogs;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class TipoChamadaTotem extends Model implements ModelDisparaGanchos
{
    use ModelPossuiLogs;

    protected $table = 'tipos_chamada_totem';
    protected $fillable = [
        'descricao',
        'instituicoes_id',
        'ganchos_id'
    ];

    public function gancho()
    {
        return GanchoModel::make($this->attributes['ganchos_id']);
    }

    public function modelAlvo() : string
    {
        return SenhaTriagem::class;
    }

    public function tiposChamada()
    {
        return $this->hasMany(PainelTotemHasTipo::class, 'tipos_chamada_id');
    }

    public function paineisTotem()
    {
        return $this->hasManyThrough(PainelTotem::class, PainelTotemHasTipo::class, 'tipos_chamada_id', 'id', 'id', 'paineis_totem_id');
    }

    public function scopeSearch(Builder $query, string $search = ''): Builder
    {
        if(empty($search))
        {
            return $query;
        }

        if(preg_match('/^\d+$/', $search))
        {
            return $query->where('id','like', "{$search}%");
        }

        return $query->where('descricao', 'like', "%{$search}%");
    }

    public static function getOpcoes(Instituicao $instituicao, PainelTotem $painel = null)
    {
        return self::with([
            'tiposChamada' => function($query) use ($painel) {
                $query->where('paineis_totem_id', $painel ? $painel->id : null);
            }
        ])
        ->where('instituicoes_id', $instituicao->id);
    }
}
