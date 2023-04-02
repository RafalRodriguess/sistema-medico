<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Representa uma relação entre filas_triagem e totens
 */
class FilaTotem extends Model
{
    protected $table = 'filas_totem';
    protected $fillable = [
        'filas_triagem_id',
        'totens_id'
    ];
    public $timestamps = false;

    public function totem()
    {
        return $this->belongsTo(Totem::class, 'totens_id');
    }

    public function filaTriagem()
    {
        return $this->belongsTo(FilaTriagem::class, 'filas_triagem_id');
    }

    public function senhasTriagem()
    {
        return $this->hasMany(SenhaTriagem::class, 'filas_totem_id');
    }

    public function instituicao()
    {
        return $this->hasOneThrough(Instituicao::class, Totem::class, 'id', 'id', 'totens_id', 'instituicoes_id');
    }
}
