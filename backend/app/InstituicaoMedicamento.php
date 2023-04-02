<?php

namespace App;

use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Client\Request;

class InstituicaoMedicamento extends Model
{
    use TraitLogInstituicao;
    use SoftDeletes;

    protected $table = "instituicao_medicamentos";

    protected $fillable = [
        'instituicao_id',
        'via_administracao',
        'nome',
        'forma_farmaceutica',
        'concentracao',
        'composicao',
        'tipo',
        'status',
    ];

    protected $casts = [
        'composicao' => 'array',
    ];

    public static function convertViaParaEscrito($via)
    {
        $data = [
            '1' => 'Oral',
            '2' => 'Sublingual',
            '3' => 'Retal',
            '4' => 'Bucal',
            '5' => 'Gástrica',
            '6' => 'Duodenal',
            '7' => 'Nasal',
            '8' => 'Ocular',
            '9' => 'Vaginal',
            '10' => 'Uretral e peniana',
            '11' => 'Transdérmica',
            '12' => 'Cutânea',
            '13' => 'Pulmonar',
            '14' => 'Tópico',
            '15' => 'Intradérmica',
            '16' => 'Intramuscular',
            '17' => 'Intra-arterial',
            '18' => 'Intratecal',
            '19' => 'Intraperitoneal',
            '20' => 'Intrapleural',
            '21' => 'Intravesical',
            '22' => 'Intra-articular',
            '23' => 'Intraraquídea',
            '24' => 'Intra-óssea',
            '25' => 'Intracardíaca',
        ];
        
        return $data[$via];
    }

    public function usuario()
    {
        return $this->belongsToMany(InstituicaoUsuario::class, 'medicamentos_add_prestador', 'instituicao_medicamento_id', 'instituicao_usuario_id')->withPivot('quantidade', 'posologia')->where('id', Request()->user('instituicao')->id);
    }

    public function scopeSearch(Builder $query, string $search = ''): Builder
    {
        if(empty($search))
        {
            return $query->orderBy('id', 'desc');
        }

        if(preg_match('/^\d+$/', $search))
        {
            return $query->where('id','like', "{$search}%")->orderBy('id', 'desc');
        }

        return $query->where('nome', 'like', "%{$search}%")->orderBy('id', 'desc');
    }
}
