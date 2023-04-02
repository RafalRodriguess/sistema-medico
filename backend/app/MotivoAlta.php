<?php

namespace App;

use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class MotivoAlta extends Model
{
    use SoftDeletes;
    use TraitLogInstituicao;

    protected $table = 'motivos_altas';

    protected $fillable = [
        'id',
        'descricao_motivo_alta',
        'tipo',
        'motivo_transferencia_id',
        'codigo_alta_sus',
        'instituicao_id'
    ];

    // Tipos ----------------------
    const alta_medica = 1;
    const alta_hospitalar = 2;
    const alta_administrativa = 3;
    const transferencia = 4;
    const obito = 5;
    // ----------------------------

    public static function getTipos()
    {
        return [
            self::alta_medica,
            self::alta_hospitalar,
            self::alta_administrativa,
            self::transferencia,
            self::obito
        ];
    }

    public static function getTipoTexto($tipo)
    {
        $dados = [
            self::alta_medica => 'Alta Médica',
            self::alta_hospitalar => 'Alta Hospitalar',
            self::alta_administrativa => 'Alta Administrativa',
            self::transferencia => 'Transferência',
            self::obito => 'Óbito',
        ];
        return $dados[$tipo];
    }

    // Motivos de Transferencia ---
    const administrativos = 1;
    const medicos = 2;
    const paciente = 3;
    const outros = 4;
    // ----------------------------

    public static function getMotivosTransferencia()
    {
        return [
            self::administrativos,
            self::medicos,
            self::paciente,
            self::outros
        ];
    }

    public static function getMotivoTransferenciaTexto($motivo_transferencia_id)
    {
        $dados = [
            self::administrativos => 'Administrativos',
            self::medicos => 'Médicos',
            self::paciente => 'Paciente',
            self::outros => 'Outros'
        ];
        return $dados[$motivo_transferencia_id];
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

        return $query->where('descricao_motivo_alta', 'like', "%{$search}%");
    }
}
