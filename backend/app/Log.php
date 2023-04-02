<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $table = 'logs';

    protected $fillable = [
        'comercial_id',
        'usuario_id',
        'usuario_type',
        'descricao',
        'dados',
    ];
      
    protected $casts = [
    'dados' => 'array',
    ];
    
    protected $hidden = [
    'dados',
    ];
      
    public function usuario() {
    return $this->morphTo();
    }
    
    public function registro() {
    return $this->morphTo();
    }
}
