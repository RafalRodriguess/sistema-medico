<?php

namespace App;

use App\Support\ModelPossuiLogs;
use App\Acomodacao;
use App\Especialidade;
use App\Casts\Checkbox;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UnidadeLeito extends Model
{
    use SoftDeletes;
    use ModelPossuiLogs;

    protected $table = 'unidades_leitos';

    protected $fillable = [
        'id',
        'quantidade',
        'descricao',
        'tipo',
        'situacao',
        'sala',
        'data_desativacao',
        'especialidade_id',
        'medico_id',
        'caracteristicas',
        'unidade_id',
        'acomodacao_id',
        'leito_virtual'
    ];

    protected $casts = [
        'caracteristicas' => 'array',
        'leito_virtual' => Checkbox::class
    ];

    // Tipos -----------
    const sus = 1;
    const convenio = 2;
    const particular = 3;
    const outros = 4;
    // -----------------


    // Situações -------
    const vago = 1;
    const ocupado = 2;
    const limpeza = 3;
    const manutencao = 4;
    const infeccao = 5;
    const reservado = 6;
    const reforma = 7;
    const interditado = 8;
    // -----------------


    // Caracteristicas --------
    const ar_condicionado = 'Ar condicionado';
    const frigobar = 'Frigobar';
    const armario = 'Armario';
    const acompanhante = 'Acompanhante';
    // ------------------------


    public static function getCaracteristicasPropostas()
    {
        return [
            self::ar_condicionado,
            self::frigobar,
            self::armario,
            self::acompanhante
        ];
    }

    public static function getTipos()
    {
        return [
            self::sus,
            self::convenio,
            self::particular,
            self::outros,
        ];
    }

    public static function getTipoTexto($tipo_id)
    {
        $dados = [
            self::sus => 'SUS',
            self::convenio => 'Convênio',
            self::particular => 'Particular',
            self::outros => 'Outros',
        ];
        return $dados[$tipo_id];
    }

    public static function getSituacoes()
    {
        return [
            self::vago,
            self::ocupado,
            self::limpeza,
            self::manutencao,
            self::infeccao,
            self::reservado,
            self::reforma,
            self::interditado
        ];
    }

    public static function getSituacaoTexto($situacao)
    {
        $dados = [
            self::vago => 'Vago',
            self::ocupado => 'Ocupado',
            self::limpeza => 'Limpeza',
            self::manutencao => 'Manutenção',
            self::infeccao => 'Infecção',
            self::reservado => 'Reservado',
            self::reforma => 'Em Reforma',
            self::interditado => 'Interditado'
        ];
        return $dados[$situacao];
    }

    public function getAcomodacao(){

        return $this->hasOne(Acomodacao::class, 'id', 'acomodacao_id')->get();
    }


    public function especialidade(){
        if($this->especialidade_id){
            return $this->hasOne(Especialidade::class, 'id', 'especialidade_id')->get();
        } else {
            return null;
        }
    }

    public function medico(){
        if($this->medico_id){
            return $this->hasOne(Prestador::class, 'id', 'medico_id')->get();
        } else {
            return null;
        }
    }

    public function scopeSearch(Builder $query, string $search = ''): Builder
    {
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
            ->orWhere('caracteristicas', 'in', "%{$search}%")
            ->orWhere('quantidade', 'like', "%{$search}%");
    }
}
