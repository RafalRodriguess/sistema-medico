<?php

namespace App;

use App\Casts\Checkbox;
use Illuminate\Database\Eloquent\Model;

class PainelTotemHasTipo extends Model
{
    protected $table = 'painel_totem_has_tipos';
    protected $fillable = [
        'tipos_chamada_id',
        'paineis_totem_id',
        'titulo',
        'local',
        'ativo',
    ];
    public $timestamps = false;

    protected $casts = [
        'ativo' => Checkbox::class
    ];

    public function painelTotem()
    {
        return $this->belongsTo(PainelTotem::class, 'paineis_totem_id');
    }

    public function tipoChamada()
    {
        return $this->belongsTo(TipoChamadaTotem::class, 'tipos_chamada_id');
    }
}
