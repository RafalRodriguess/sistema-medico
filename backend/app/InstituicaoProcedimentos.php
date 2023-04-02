<?php

namespace App;

use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Instituicao;

class InstituicaoProcedimentos extends Model
{
    use SoftDeletes;
    use TraitLogInstituicao;




    protected $table = 'procedimentos_instituicoes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'procedimentos_id',
        'instituicoes_id',
        'grupo_id',
        'tipo',
        'modalidades_exame_id'
    ];

    // Valida a modalidade exame antes de criar e alterar registros
    public static function boot()
    {
        $validate_model = function ($model) {
            if (!empty($model->modalidades_exame_id)) {
                // Garante que exista a modalidade especificada
                ModalidadeExame::findOrFail($model->modalidades_exame_id);
                // Garante que só procedimentos do tipo exame tem modalidades
                $procedimento = Procedimento::findOrFail($model->procedimentos_id);
                if ($procedimento->tipo != 'exame') {
                    $procedimento->tipo = 'exame';
                    $procedimento->save();
                }
            }
        };

        parent::boot();
        self::creating($validate_model);
        self::updating($validate_model);
    }

    public function modalidadeExame()
    {
        $this->belongsTo(ModalidadeExame::class, 'modalidades_exame_id');
    }

    public function instituicao()
    {
        return $this->belongsTo(Instituicao::class, 'instituicoes_id');
    }

    public function procedimento()
    {
        return $this->belongsTo(Procedimento::class, 'procedimentos_id');
    }
    
    public function procedimentoTrashed()
    {
        return $this->belongsTo(Procedimento::class, 'procedimentos_id')->withTrashed();
    }

    public function agenda()
    {
        return $this->hasMany(InstituicoesAgenda::class, 'procedimentos_instituicoes_id');
    }


    public function procedimentoConvenios()
    {
        return $this->hasOne(Procedimento::class, 'procedimentos_id');
    }

    public function instituicaoProcedimentosConvenios()
    {
        return $this->belongsToMany(Convenio::class, 'procedimentos_instituicoes_convenios', 'procedimentos_instituicoes_id', 'convenios_id')->withPivot(['id', 'valor'])->whereNull('procedimentos_instituicoes_convenios.deleted_at');
    }

    public function conveniosProcedimentos()
    {
        return $this->hasMany(ConveniosProcedimentos::class, 'procedimentos_instituicoes_id');
    }

    public function grupoProcedimento()
    {
        return $this->belongsTo(GruposProcedimentos::class, 'grupo_id');
    }

    public function scopeSearch(Builder $query, string $search = '', Instituicao $instituicao): Builder
    {


        $query->with('instituicaoProcedimentosConvenios')
            ->where('instituicoes_id', $instituicao->id);

        if (empty($search)) {
            return $query;
        }

        if (preg_match('/^\d+$/', $search)) {
            return $query->where('id', 'like', "{$search}%");
        }
        $query->where(function ($q) use ($search) {
            $q->where(function ($q) use ($search) {
                $q->wherehas('procedimento', function ($q) use ($search) {

                    $q->where('descricao', 'like', "%{$search}%");
                    // ->orWhere('tipo', 'like', "%{$search}%");

                });
            })
                ->orWhere(function ($q) use ($search) {
                    $q->wherehas('grupoProcedimento', function ($q) use ($search) {
                        $q->where('nome', 'like', "%{$search}%");
                    });
                });
        });



        return $query;
    }

    public function scopeSearchProcedimentos(Builder $query, string $search = '', int $procedimento): Builder
    {

        if ($procedimento != 0) {
            $query->wherehas('procedimento', function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%");
            });
        }

        if (empty($search)) {
            return $query;
        }

        if (preg_match('/^\d+$/', $search)) {
            return $query->wherehas('procedimento', function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%");
            });
        }

        return $query->wherehas('procedimento', function ($q) use ($search) {
            $q->where('descricao', 'like', "%{$search}%");
        });
    }
}
