<?php

namespace App;

use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class AtividadeMedica extends Model
{
    use SoftDeletes;
    use TraitLogInstituicao;
    
    protected $table = 'atividades_medicas';

    protected $fillable = [
        'instituicao_id',
        'descricao',
        'ordem_apresentacao',
        'tipo_funcao',
    ];

    public function instituicao()
    {
        return $this->belongsTo(Instituicao::class, 'instituicao_id');
    }

    public static function getTipoFuncaoOptions()
    {
        $type = DB::select(DB::raw("SHOW COLUMNS FROM atividades_medicas WHERE Field = 'tipo_funcao'"))[0]->Type;
        
        preg_match('/^enum\((.*)\)$/', $type, $matches);
        
        $enumValues = array();
        
        foreach (explode(',', $matches[1]) as $value) {
            $v = trim($value, "'");
            $enumValues[] = $v;
        }

        return $enumValues;
    }
}
