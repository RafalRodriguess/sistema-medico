<?php

namespace App;

use App\Support\ModelPossuiLogs;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Entregador extends Model
{
    //
    use SoftDeletes;
    use ModelPossuiLogs;
    
    protected $table = 'entregadores';

    protected $fillable = [
        'id',
        'nome',
        'telefone',
        'veiculo',
        'carga_maxima',
        'comercial_id',
        'comercial_usuario_id',
    ];

    public function comercial()
    {
        return $this->belongsTo(Comercial::class, 'comercial_id');
    }

    public function comercialUsuario()
    {
        return $this->belongsTo(ComercialUsuario::class, 'comercial_usuario_id');
    }
}
