<?php

namespace App;

use App\Http\Controllers\Instituicao\Convenios;
use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Prestador extends Model
{
    //
    use SoftDeletes;
    use TraitLogInstituicao;

    protected $table = 'prestadores';

    protected $fillable = [
        'id',
        'nome',
        'personalidade',
        'cpf',
        'cnpj',
        'razao_social',
        'cep',
        'rua',
        'bairro',
        'cidade',
        'estado',
        'numero',
        'sexo',
        'nascimento',
        'identidade',
        'identidade_orgao_expedidor',
        'identidade_uf',
        'identidade_data_expedicao',
        'nome_do_pai',
        'nome_da_mae',
        'naturalidade',
        'nacionalidade',
        'numero_cartao_sus',
        'sancoop_cod_coperado',
        'sancoop_desc_prestador',
        'sancoop_user_coperado',
        'teleatendimento_id_prestador',
        'email',
    ];

    public const opcoes_sexo = [
        0 => 'Masculino',
        1 => 'Feminino',
        2 => 'Outro',
    ];

    public static function getPersonalidadeTexto($personalidade)
    {
        return ($personalidade == 1) ? 'Pessoa Física' : 'Pessoa Jurídica';
    }

    public function prestadoresInstituicoes()
    {
        return $this->hasMany(InstituicoesPrestadores::class, 'prestadores_id');
    }
    
    public function prestadoresInstituicoesLocal()
    {
        return $this->hasMany(InstituicoesPrestadores::class, 'prestadores_id')->where('instituicoes_id', request()->session()->get('instituicao'));
    }

    public function instituicaoPrestador(int $instituicao_id){
        return InstituicoesPrestadores::query()
            ->where('instituicoes_id', $instituicao_id)
            ->where('prestadores_id', $this->id)
            ->get();
    }

    public function instituicaoPrestadorEspecialidade(){

        return $this->belongsToMany(instituicaoPrestadorEspecialidade::class, 'prestadores_id');
    }

    public function contatos(){
        return $this->hasMany(ContatoPrestador::class, 'prestador_id');
    }

    public function documentos()
    {
        return $this->hasMany(DocumentoPrestador::class, 'prestador_id');
    }


    public function especialidade()
    {
        return $this->belongsToMany(Especialidade::class, 'instituicoes_prestadores', 'prestadores_id', 'especialidade_id')->whereNull('instituicoes_prestadores.deleted_at');
    }

    public function prestadorInstituicaoByInstituicao(int $instituicao_id)
    {
        return InstituicoesPrestadores::query()->where('instituicoes_id', $instituicao_id)->first();
    }

    public function instituicoes()
    {
        return $this->belongsToMany(Instituicao::class, 'instituicoes_prestadores', 'prestadores_id', 'instituicoes_id')->whereNull('instituicoes_prestadores.deleted_at');
    }

    public function especialidadeInstituicao(){
        return $this->hasMany(InstituicoesPrestadores::class, 'prestadores_id');
    }

    public function repasseMedico()
    {
        return $this->belongsToMany(ConveniosProcedimentos::class, 'procedimentos_convenios_has_repasse_medico', 'prestador_id', 'procedimento_instituicao_convenio_id')->withPivot('tipo', 'valor_repasse', 'tipo_cartao', 'valor_repasse_cartao');
    }

    public function scopeSearchByInstituicao(Builder $query, string $search = '',
        int $especialidade, int $instituicao): Builder
    {

        if ($especialidade != 0) {
            $query->whereHas('especialidadeInstituicao',function($q) use ($instituicao, $especialidade){
                $q->where('instituicoes_id',$instituicao)
                ->where('especialidade_id', $especialidade);
            });

            $query->with(['especialidadeInstituicao' => function($q) use ($instituicao, $especialidade){
                $q->where('instituicoes_id',$instituicao)
                ->where('especialidade_id', $especialidade);
            }]);
        }else{
            $query->whereHas('especialidadeInstituicao',function($q) use ($instituicao){
                $q->where('instituicoes_id',$instituicao);
            });

            $query->with(['especialidadeInstituicao' => function($q) use ($instituicao){
                $q->where('instituicoes_id',$instituicao);
            }]);
        }


        if(empty($search))
        {
            return $query->orderBy('id', 'desc');
        }

        if(preg_match('/^\d+$/', $search))
        {
            return $query->where('id','like', "{$search}%")->orderBy('id', 'desc');
        }

        return $query->whereHas('prestadoresInstituicoesLocal', function($q) use($search){
            $q->where('nome', 'like', "%{$search}%");
        });

        return $query->Where('nome', 'like', "%{$search}%")->orderBy('id', 'desc');
    }

    public static function scopeGetByInstituicao(Builder $query, int $instituicao_id): Builder
    {
        return $query->whereHas('prestadoresInstituicoes', function($query) use ($instituicao_id){
            $query->where('instituicoes_id', $instituicao_id);
        });
    }

    public function scopeSearchMedicos(Builder $query): Builder
    {
        return $query->where('tipo', '=',  2);
    }

    public function scopeSearch(Builder $query, string $nome = ''): Builder
    {
        if(empty($nome)) return $query;

        if(preg_match('/^\d+$/', $nome)) return $query->where('id','like', "{$nome}%");

        return $query->where('nome', 'like', "%{$nome}%");
    }

    public function setoresExame() {
        return $this->belongsToMany(SetorExame::class, 'setores_prestadores_exame', 'prestadores_id', 'setores_exame_id');
    }

    public function prestadorVinculos()
    {
        return $this->hasMany(PrestadorVinculo::class, 'prestador_id');
    }
}
