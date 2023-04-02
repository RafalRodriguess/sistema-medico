<?php

namespace App;

use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AgendamentoListaEspera extends Model
{
    use TraitLogInstituicao;
    use SoftDeletes;

    protected $table = "agendamentos_lista_espera";

    protected $fillable = [
        'id',
        'instituicao_id',
        'paciente_id',
        'convenio_id',
        'prestador_id',
        'especialidade_id',
        'obs',
        'status',
    ];

    public function pessoa()
    {
        return $this->belongsTo(Pessoa::class, "paciente_id");
    }
    public function convenio()
    {
        return $this->belongsTo(Convenio::class, "convenio_id");
    }
    public function prestador()
    {
        return $this->belongsTo(Prestador::class, "prestador_id");
    }
    public function especialidade()
    {
        return $this->belongsTo(Especialidade::class, "especialidade_id");
    }
    public function prestadorExcluidos()
    {
        return $this->belongsTo(Prestador::class, "prestador_id")->withTrashed();
    }
    public function especialidadeExcluidos()
    {
        return $this->belongsTo(Especialidade::class, "especialidade_id")->withTrashed();
    }

    public function scopeSearch(Builder $query, $search = 0, $exibirTodos = 0): Builder
    {
        if($exibirTodos == 0){
            $query->where('status', 0);
        }

        $query->orderBy('created_at', "ASC");

        if($search == 0){
            return $query;
        }

        return $query->where('paciente_id', "{$search}");
    }
    
    public function scopeGetListaEspera(Builder $query, $prestador_id, $especialidade_id): Builder
    {
        $query->where(function($q) use($prestador_id, $especialidade_id){
            $q->where('prestador_id', $prestador_id);
            $q->orWhere('especialidade_id', $especialidade_id);
        });

        $query->where('status', 0);

        $query->orderBy('created_at', "ASC");

        return $query;
    }
}
