<?php

namespace App;

use App\Support\ModelOverwrite;
use App\Support\ModelPossuiLogs;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class PainelTotem extends Model
{
    use ModelPossuiLogs;
    use ModelOverwrite;

    protected $table = 'paineis_totem';
    protected $fillable = [
        'descricao',
        'origens_id',
        'instituicoes_id',
    ];
    protected $allowed_overwrite = [
        PainelTotemHasTipo::class
    ];

    public function painelHasTipo()
    {
        return $this->hasMany(PainelTotemHasTipo::class, 'paineis_totem_id');
    }

    public function tiposChamada()
    {
        return $this->hasManyThrough(TipoChamadaTotem::class, PainelTotemHasTipo::class, 'paineis_totem_id', 'id', 'id', 'tipos_chamada_id');
    }

    public function origem()
    {
        return $this->belongsTo(Origem::class, 'origens_id');
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
}
