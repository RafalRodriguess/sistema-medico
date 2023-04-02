<?php

namespace App;

use App\Support\ModelPossuiLogs;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CentroCusto extends Model
{
    use SoftDeletes;
    use ModelPossuiLogs;

    protected $table = 'centros_de_custos';

    protected $fillable = [
        'id',
        'codigo',
        'pai_id',
        'grupo_id',
        'descricao',
        'lancamento',
        'ativo',
        'email',
        'gestor',
        'instituicao_id',
        'setor_exame_id',
    ];

    // Grupos -----
    const administrativo = 1;
    const apoio = 2;
    const produtivo = 3;
    const nao_operacional = 4;
    const sadt = 5;
    const obras_reformas = 6;
    const inativos = 7;
    // -------------

    public static function getGrupos()
    {
        return [
            self::administrativo,
            self::apoio,
            self::produtivo,
            self::nao_operacional,
            self::sadt,
            self::obras_reformas,
            self::inativos,
        ];
    }

    public static function getGrupoTexto(int $grupo)
    {
        $dados = [
            self::administrativo => 'Administrativo',
            self::apoio => 'Apoio',
            self::produtivo => 'Produtivo',
            self::nao_operacional => 'Não Operacional',
            self::sadt => 'SADT',
            self::obras_reformas => 'Obras/Reformas',
            self::inativos => 'Inativos',
        ];
        return $dados[$grupo];
    }

    public function scopeFilhos(Builder $query, $id) {

        return $query->where('pai_id', $id);
    }

    public function filhos(){

        return CentroCusto::query()->where('pai_id', $this->id)->withTrashed()->get();
    }


    public function scopePai(Builder $query) {

        return $query->where('id', $this->pai_id)->first();
    }


    public static function scopeOrfaos(Builder $query) {

        return $query->where('pai_id', null)->withTrashed();
    }

    public function scopeSearch(Builder $query, string $search = ''): Builder {

        if(empty($search))
        {
            return $query;
        }

        if(preg_match('/^\d+$/', $search))
        {
            return $query->where('id','like', "{$search}%");
        }

        return $query
            ->where('descricao', 'like', "%{$search}%")
            ->orWhere('codigo', 'like', "%{$search}%");
    }

    public function instituicao()
    {
        return $this->belongsTo(Instituicao::class, 'id', $this->instituicao_id);
    }

    public function setorExame() {
        return $this->belongsTo(SetorExame::class, 'setor_exame_id');
    }
}
