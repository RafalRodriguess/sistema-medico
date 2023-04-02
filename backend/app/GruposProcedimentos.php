<?php

namespace App;

use App\Support\ModelPossuiLogs;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GruposProcedimentos extends Model
{
    use SoftDeletes;
    use ModelPossuiLogs;

    protected $table = 'grupos_procedimentos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'nome',
        'principal',
        'grupo_faturamento_id',
        'tipo',
    ];

    protected $casts = [
        'principal' => 'boolean'
    ];

    public function procedimentos_instituicoes()
    {
        return $this->hasMany(InstituicaoProcedimentos::class, 'grupo_id');
    }

    public function instituicoes()
    {
        return $this->hasMany(GruposInstituicoes::class, 'grupo_id');
    }

    public function grupoFaturamento()
    {
        return $this->belongsTo(GrupoFaturamento::class, 'grupo_faturamento_id');
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

    public function scopeGetGrupoDashboard(Builder $query, $data):Builder
    {
        $query->whereHas('procedimentos_instituicoes', function($q) use($data){
            $q->where('instituicoes_id', request()->session()->get('instituicao'));
            $q->whereHas('conveniosProcedimentos', function($query) use($data){
                $query->whereHas('orcamentosItens', function($q) use($data){
                    $q->whereDate('created_at', '>=', $data[0])
                    ->whereDate('created_at', '<=', $data[1]);
                });
            });
        });
        
        $query->with(['procedimentos_instituicoes' => function($q) use($data){
            $q->where('instituicoes_id', request()->session()->get('instituicao'));
            $q->whereHas('conveniosProcedimentos', function($query) use($data){
                $query->whereHas('orcamentosItens', function($q) use($data){
                    $q->whereDate('created_at', '>=', $data[0])
                    ->whereDate('created_at', '<=', $data[1]);
                });
            });
            $q->with(['conveniosProcedimentos' => function($query) use($data){
                $query->whereHas('orcamentosItens', function($q) use($data){
                    $q->whereDate('created_at', '>=', $data[0])
                    ->whereDate('created_at', '<=', $data[1]);
                });
                $query->with(['orcamentosItens' => function($q) use($data) {
                    $q->whereDate('created_at', '>=', $data[0])
                    ->whereDate('created_at', '<=', $data[1]);
                }]);
            }]);
        }]);

        return $query;
    }

}
