<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProntuarioConfiguracaoItem extends Model
{
    protected $table = 'prontuario_configuracoes_itens';

    protected $fillable = [
        'id',
        'prontuario_configuracao_id',
        'tipo_item',
        'item',
        'status',
    ];

    protected $casts = [
        'item' => 'array'
    ];

    public function prontuario()
    {
        return $this->hasMany(ProntuarioConfiguracao::class, 'prontuario_configuracao_id');
    }
}
