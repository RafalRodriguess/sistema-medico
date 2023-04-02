<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Support\TraitLogInstituicao;

class EspecializacaoEspecialidade extends Model
{
    use TraitLogInstituicao;

    protected $table = 'especializacoes_especialidade';

    protected $fillable = [
        'especializacoes_id',
        'especialidades_id'
    ];

    /**
     * Cria um registro verificando se não existe outro igual
     * @param int $especializacoes_id O id da especializacao
     * @param int $especialidades_id O id da especialidade
     * @return \app\Especializacao retorna a especializacao criada
     */
    public static function createIfUnique(int $especialidades_id, int $especializacoes_id)
    {
        // Cache especializacoes
        if (!self::where('especializacoes_id', '=', $especializacoes_id)->where('especialidades_id', '=', $especialidades_id)->exists()) {
            return self::create(compact('especializacoes_id', 'especialidades_id'));
        }
    }

    /**
     * Limpa as especializacoes de uma especialidade e insere
     * múltiplos registros de especializacao na especialiade
     * @param int $especialidades_id Id da especialidade
     * @param array $especializacoes O array com os ids de especialziacoes
     */
    public static function overwrite(int $especialidades_id, array $especializacoes = [])
    {
        self::where('especialidades_id', '=', $especialidades_id)->delete();
        if (!empty($especializacoes)) {
            $especializacoes = collect($especializacoes)->map(function ($item) use ($especialidades_id) {
                return [
                    'especialidades_id' => $especialidades_id,
                    'especializacoes_id' => $item
                ];
            });
            self::insert($especializacoes->toArray());
        }
    }

    public function especialidade()
    {
        return $this->belongsTo(Especialidade::class, 'especialidades_id');
    }

    public function especializacao()
    {
        return $this->belongsTo(Especializacao::class, 'especializacoes_id');
    }
}
