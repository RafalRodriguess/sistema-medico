<?php

namespace App;

use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cirurgia extends Model
{
    use SoftDeletes;
    use TraitLogInstituicao;

    protected $table = 'cirurgias';

    protected $fillable = [
        'id',
        'descricao',
        'porte',
        'obstetricia',
        'tipo_parto_id',
        'previsao',
        'orientacoes',
        'preparos',
        'grupo_cirurgia_id',
        'tipo_anestesia_id',
        'procedimento_id',
        'via_acesso_id',
        'convenio_id',
    ];

    const opcoes_porte = [
        'pequeno' => 'pequeno',
        'medio' => 'medio',
        'grande' => 'grande',
    ];

    const pequeno = 'pequeno';
    const medio = 'medio';
    const grande = 'grande';
    
    public static function getPortes($texto = null)
    {
        $dados = [
            self::pequeno => 'Pequeno',
            self::medio => 'Médio',
            self::grande => 'Grande',
        ];

        return $dados[$texto];
    }

    public function scopeSearch(Builder $query, string $search = ''): Builder
    {
        $query->orderBy('id','DESC');
        
        if(empty($search))
        {
            return $query;
        }

        if(preg_match('/^\d+$/', $search))
        {
            return $query->where('id','like', "{$search}%");
        }

        return $query->where('descricao', 'like', "%{$search}%");
    }

    public function partos(){
        return $this->belongsTo(TipoParto::class, 'tipo_parto_id');
    }

    public function grupos(){
        return $this->belongsTo(GrupoCirurgia::class, 'grupo_cirurgia_id');
    }

    public function tipoAnestesias(){
        return $this->belongsTo(TipoAnestesia::class, 'tipo_anestesia_id');
    }

    public function cirurgiasEquipamentos()
    {
        return $this->belongsToMany(Equipamento::class, 'cirurgias_equipamentos', 'cirurgia_id', 'equipamento_id')->withPivot('quantidade');
    }

    public function cirurgiasEspecialidades()
    {
        return $this->belongsToMany(Especialidade::class, 'cirurgias_especialidades', 'cirurgia_id', 'especialidade_id');
    }

    public function cirurgiasEquipes()
    {
        return $this->belongsToMany(EquipeCirurgica::class, 'cirurgias_equipes', 'cirurgia_id', 'equipe_id');
    }

    public function cirurgiasSalas()
    {
        return $this->belongsToMany(SalaCirurgica::class, 'cirurgias_salas', 'cirurgia_id', 'sala_id');
    }

    public function procedimento()
    {
        return $this->belongsTo(Procedimento::class, 'procedimento_id');
    }

    public function convenio()
    {
        return $this->belongsTo(Convenio::class, 'convenio_id');
    }
    
    public function convenioTrash()
    {
        return $this->belongsTo(Convenio::class, 'convenio_id')->withTrashed();
    }
}
