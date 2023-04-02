<?php

namespace App;

use App\Support\ModelPossuiLogs;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UsuarioCartao extends Model
{
    use SoftDeletes;
    use ModelPossuiLogs;

    protected $table = 'usuario_cartoes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'id_pagarme',
        'ultimos_digitos',
        'bandeira',
        'usuario_id',
        'nome',
        'rua',
        'numero',
        'bairro',
        'cidade',
        'estado',
        'cep'
    ];

    /**
    * The relations to eager load on every query.
    *
    * @var array
    */

   public function usuario()
   {
       return $this->belongsTo(Usuario::class, 'usuario_id');
   }

}
