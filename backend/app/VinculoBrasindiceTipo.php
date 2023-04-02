<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VinculoBrasindiceTipo extends Model
{
    protected $table = "vinculo_brasindice_tipos";

    protected $fillable = [
        'descricao',
    ];

    public function vinculos_tuss()
    {
        return $this->hasMany(VinculoBrasindice::class, 'id', 'tipo_id');
    }
}
