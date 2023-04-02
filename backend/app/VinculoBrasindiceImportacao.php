<?php

namespace App;

use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VinculoBrasindiceImportacao extends Model
{
    use SoftDeletes;
    use TraitLogInstituicao;

    protected $table = "vinculo_brasindice_importacoes";

    protected $fillable = [
        'instituicao_id',
        'tipo_id',
        'usuario_id',
        'edicao',
        'vigencia',
        'insercoes',
        'atualizacoes',
    ];

    public function instituicao()
    {
        return $this->belongsTo(Instituicao::class, 'id', 'instituicao_id');
    }

    public function tipo()
    {
        return $this->belongsTo(VinculoBrasindiceTipo::class, 'tipo_id');
    }
    
    public function usuario()
    {
        return $this->belongsTo(InstituicaoUsuario::class, 'usuario_id');
    }
}
