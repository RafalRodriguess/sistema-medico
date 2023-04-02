<?php

namespace App;

use App\Support\ModelPossuiLogs;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UsuarioEndereco extends Model
{

    use SoftDeletes;
    use ModelPossuiLogs;
    
    protected $table = 'usuario_enderecos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'rua',
        'numero',
        'cep',
        'bairro',
        'cidade',
        'estado',
        'referencia',
        'complemento',
        'usuario_id',
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

        return $query->where(function ($query) use ($search) {
            $query->orWhere('rua', 'like', "%{$search}%");
            $query->orWhere('bairro', 'like', "%{$search}%");
            $query->orWhere('cidade', 'like', "%{$search}%");
            $query->orWhere('estado', 'like', "%{$search}%");
          });
    }
   
}
