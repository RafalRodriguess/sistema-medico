<?php

namespace App;

use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FaturamentoLote extends Model
{
    use SoftDeletes;
    use TraitLogInstituicao;

    protected $table = 'faturamento_protocolos';

    protected $fillable = [
        'id',
        'cod_externo',
        'descricao',
        'tipo',
        'status',
        'instituicao_id',
        'prestadores_id'
    ];

    static function getStatus(int $status = null){
        $lista_status = [
            0 => 'Aberto',
            1 => 'Transmitida',
            2 => 'Conferidas e auditadas',
            3 => 'Com pendência'
        ];

        if($status === null){
            return $lista_status;
        }else{
            return (array_key_exists($status, $lista_status)) ? $lista_status[$status] : null;
        }
    }


    public function guias()
    {
        return $this->hasMany(FaturamentoLoteGuia::class, 'faturamento_protocolo_id');
    }


    public function prestador()
    {
        return $this->belongsTo(Prestador::class, 'prestadores_id');
    }

    public function protocoloSancoop()
    {
        return 'tess';
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

    public function scopeSearchSancoop(Builder $query, string $search = ''): Builder
    {
        $query->where('tipo', 2);
        
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
