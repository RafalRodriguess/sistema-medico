<?php

namespace App;

use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VinculoBrasindice extends Model
{
    use SoftDeletes;
    use TraitLogInstituicao;

    protected $table = "vinculo_brasindice";

    protected $fillable = [
        'importacao_id',
        'instituicao_id',
        'tipo_id',
        'laboratorio_cod',
        'laboratorio',
        'medicamento_cod',
        'medicamento',
        'apresentacao_cod',
        'apresentacao',
        'preco_medicamento',
        'qtd_fracionamento',
        'tipo_preco',
        'valor_fracionado',
        'edicao',
        'ipi_medicamento',
        'flag_pis_confins',
        'ean',
        'tiss',
        'flag_generico',
        'tuss',
    ];

    public function importacao()
    {
        return $this->belongsTo(VinculoBrasindiceImportacao::class, 'importacao_id');
    }

    public function instituicao()
    {
        return $this->belongsTo(Instituicao::class, 'instituicao_id');
    }

    public function tipo()
    {
        return $this->belongsTo(VinculoBrasindiceTipo::class, 'tipo_id');
    }
}
