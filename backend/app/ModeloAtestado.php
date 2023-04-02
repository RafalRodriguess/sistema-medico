<?php

namespace App;

use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Gate;

class ModeloAtestado extends Model
{
    use SoftDeletes;
    use TraitLogInstituicao;

    protected $table = "modelo_atestados";

    protected $fillable = [
        'instituicao_prestador_id',
        'descricao',
        'texto',
    ];

    public function instituicaoPrestador()
    {
        return $this->belongsTo(InstituicoesPrestadores::class, 'instituicao_prestador_id');
    }

    public function scopeSearch(Builder $query, string $search = '', int $instituicaoId, int $usuarioId): Builder
    {
        $query->whereHas('instituicaoPrestador', function($query) use($instituicaoId, $usuarioId){
            $query->where('instituicoes_id', $instituicaoId);
            if(!Gate::allows('habilidade_instituicao_sessao', 'visualizar_all_modelo_atestado')){
                $query->where('instituicao_usuario_id', $usuarioId);
            }
        });

        $query->whereHas('instituicaoPrestador.prestador', function($query) use($search){
            $query->where('nome', 'like', "%{$search}%");
        });

        if(empty($search)) return $query;

        if(preg_match('/^\d+$/', $search)) return $query->where('id','like', "{$search}%");

        return $query;
    }
}
