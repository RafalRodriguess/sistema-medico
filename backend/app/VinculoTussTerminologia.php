<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VinculoTussTerminologia extends Model
{
    use SoftDeletes;

    protected $table = "vinculo_tuss_terminologias";

    protected $fillable = [
        'cod_tabela',
        'descricao',
        'cabecalho',
    ];

    public function vinculos_tuss()
    {
        return $this->hasMany(VinculoTuss::class, 'id', 'terminologia_id');
    }
}
