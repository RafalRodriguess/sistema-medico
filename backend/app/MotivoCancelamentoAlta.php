<?php

namespace App;

use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MotivoCancelamentoAlta extends Model
{
    use SoftDeletes;
    use TraitLogInstituicao;

    protected $table = 'motivos_cancelamento_altas';

    protected $fillable = [
        'id',
        'descricao_motivo_cancelamento_alta',
        'tipo',
        'ativo',
        'instituicao_id'
    ];

    // Tipos ----------------------
    const administrativo = 1;
    const medico = 2;
    const paciente = 3;
    const transferencia = 4;
    // ----------------------------

    public static function getTipos()
    {
        return [
            self::administrativo,
            self::medico,
            self::paciente,
            self::transferencia,
        ];
    }

    public static function getTipoTexto($tipo)
    {
        $dados = [
            self::administrativo => 'Administrativo',
            self::medico => 'Médico',
            self::paciente => 'Paciente',
            self::transferencia => 'Transferência',
        ];
        return $dados[$tipo];
    }

    public function instituicao()
    {
        return $this->belongsTo(Instituicao::class, 'id', 'instituicao_id');
    }

    public function scopeSearchByTipo(Builder $query, int $tipo = 0): Builder
    {
        if($tipo === 0) return $query;

        return $query->where('tipo', $tipo);
    }

    public function scopeSearch(Builder $query, string $search = ''): Builder
    {
        if(empty($search)) return $query;

        if(preg_match('/^\d+$/', $search)) return $query->where('id','like', "{$search}%");

        return $query->where('descricao_motivo_cancelamento_alta', 'like', "%{$search}%");
    }
}
