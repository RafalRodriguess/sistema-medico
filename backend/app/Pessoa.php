<?php

namespace App;

use App\Support\TraitLogInstituicao;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Gate;

class Pessoa extends Model
{
    use SoftDeletes;
    use TraitLogInstituicao;

    protected $table = 'pessoas';

    protected $fillable = [
        'id',
        'personalidade',
        'tipo',
        // Pessoa Física
        'nome',
        'cpf',
        'telefone1',
        'telefone2',
        'email',
        'cep',
        'estado',
        'cidade',
        'bairro',
        'complemento',
        'rua',
        'numero',
        'nome_pai',
        'nome_mae',
        'identidade',
        'orgao_expedidor',
        'data_emissao',
        'estado_civil',
        'sexo',
        'nascimento',

        // Pessoa Juridica
        'nome_fantasia',
        'cnpj',
        'razao_social',
        'site',
        'banco',
        'agencia',
        'conta_corrente',
        // Pessoa de Referencia
        'referencia_relacao',
        'referencia_nome',
        'referencia_telefone',
        // Avaliação
        'avaliação',
        'instituicao_id',
        'obs',

        'telefone3',
        'naturalidade',
        'indicacao_descricao',
        'profissao',
        'referencia_documento',

        'obs_consultorio',
        'teleatendimento_id_pessoa',
        'gerar_via_acompanhante',

        //Campos integração asaplan
        'asaplan_filial',
        'asaplan_tipo',
        'asaplan_chave_plano',
        'asaplan_situacao_plano',
        'asaplan_id_titular',
        'asaplan_nome_titular',
        'asaplan_ultima_atualizacao',

    ];

    protected $casts = [
        'gerar_via_acompanhante' => 'boolean'
    ];

    // Personalidade ----------
    const pessoa_fisica = 1;
    const pessoa_juridica = 2;
    // ------------------------

    // Tipos ------------------
    const cliente = 1;
    const paciente = 2;
    // ------------------------

    // Sexo ------------------
    const masculino = 'm';
    const feminino = 'f';
    // ------------------------


    public static function getPersonalidades(): array
    {
        return [
            self::pessoa_fisica,
            self::pessoa_juridica,
        ];
    }

    public static function getPersonalidadeTexto(int $personalidade): string
    {
        $dados = [
            self::pessoa_fisica => 'Pessoa Física',
            self::pessoa_juridica => 'Pessoa Jurídica',
        ];

        return $dados[$personalidade];
    }

    public static function getTipos(): array
    {
        return [
            self::cliente,
            self::paciente,
        ];
    }

    public static function getTipoTexto(int $tipo): string
    {
        $dados = [
            self::cliente => 'Cliente',
            self::paciente => 'Paciente',
        ];

        return $dados[$tipo];
    }

    public static function getSexos(): array
    {
        return [
            self::masculino,
            self::feminino,
        ];
    }

    public static function getSexoTexto(string $sexo): string
    {
        $dados = [
            self::masculino => 'Masculino',
            self::feminino => 'Feminino',
        ];

        return $dados[$sexo];
    }

    public static function getEstadosCivil(): array
    {
        return [
            'Solteiro', 'Casado', 'Viúvo', 'Divorciado',
        ];
    }
    
    public static function getRelacoesParentescos(): array
    {
        return [
            'Pai', 'Mãe', 'Avó', 'Avô', 'Tia', 'Tio', 'Madrasta', 'Padrasto', 'Cônjuge', 
            'Irmão', 'Irmã', 'Primo', 'Prima', 'Sobrinha', 'Sobrinho', 'Cunhado', 'Cunhada',
            'Amigo', 'Amiga', 'Filho', 'Filha', 'Namorada', 'Namorado'
        ];
    }


    public function instituicao()
    {
        return $this->belongsTo(Instituicao::class, 'instituicao_id');
    }

    public function documentos()
    {
        return $this->hasMany(PessoaDocumento::class, 'pessoa_id');
    }

    public function carteirnha()
    {
        return $this->hasMany(Carteirinha::class, 'pessoa_id');
    }

    public function carteirinha()
    {
        return $this->hasMany(Carteirinha::class, 'pessoa_id');
    }

    public function preInternacoes()
    {
        return $this->hasMany(PreInternacao::class, 'paciente_id')->where('pre_internacao', 1);
    }

    public function prontuarios($usuarioId)
    {
        return $this->hasMany(ProntuarioPaciente::class, 'paciente_id')->where('usuario_id', $usuarioId);
    }

    public function prontuario()
    {
        return $this->hasMany(ProntuarioPaciente::class, 'paciente_id');
    }
    
    public function receituarios($usuarioId)
    {
        return $this->hasMany(ReceituarioPaciente::class, 'paciente_id')->where('usuario_id', $usuarioId);
    }
    
    public function receituario()
    {
        return $this->hasMany(ReceituarioPaciente::class, 'paciente_id');
    }

    public function atestado()
    {
        return $this->hasMany(AtestadoPaciente::class, 'paciente_id');
    }

    public function atestados($usuarioId)
    {
        return $this->hasMany(AtestadoPaciente::class, 'paciente_id')->where('usuario_id', $usuarioId);
    }
    
    public function conclusao()
    {
        return $this->hasMany(ConclusaoPaciente::class, 'paciente_id');
    }

    public function conclusoes($usuarioId)
    {
        return $this->hasMany(ConclusaoPaciente::class, 'paciente_id')->where('usuario_id', $usuarioId);
    }

    public function laudo()
    {
        return $this->hasMany(LaudoPaciente::class, 'paciente_id');
    }

    public function laudos($usuarioId)
    {
        return $this->hasMany(LaudoPaciente::class, 'paciente_id')->where('usuario_id', $usuarioId);
    }

    public function avaliacoes($usuarioId)
    {
        return $this->hasMany(Avaliacao::class, 'paciente_id')->where('usuario_id', $usuarioId);
    }

    public function avaliacao()
    {
        return $this->hasMany(Avaliacao::class, 'paciente_id');
    }
    
    public function encaminhamento()
    {
        return $this->hasMany(EncaminhamentoPaciente::class, 'paciente_id');
    }

    public function encaminhamentos($usuarioId)
    {
        return $this->hasMany(EncaminhamentoPaciente::class, 'paciente_id')->where('usuario_id', $usuarioId);
    }
    
    public function relatorio()
    {
        return $this->hasMany(RelatorioPaciente::class, 'paciente_id');
    }

    public function relatorios($usuarioId)
    {
        return $this->hasMany(RelatorioPaciente::class, 'paciente_id')->where('usuario_id', $usuarioId);
    }
    
    public function exame()
    {
        return $this->hasMany(ExamePaciente::class, 'paciente_id');
    }

    public function exames($usuarioId)
    {
        return $this->hasMany(ExamePaciente::class, 'paciente_id')->where('usuario_id', $usuarioId);
    }
    
    public function refracao()
    {
        return $this->hasMany(RefracaoPaciente::class, 'paciente_id');
    }

    public function refracoes($usuarioId)
    {
        return $this->hasMany(RefracaoPaciente::class, 'paciente_id')->where('usuario_id', $usuarioId);
    }
    
    public function odontologicos()
    {
        return $this->hasMany(OdontologicoPaciente::class, 'paciente_id');
    }
    
    public function pastas()
    {
        return $this->hasMany(PacientePasta::class, 'paciente_id');
    }
    
    public function atendimentoPaciente()
    {
        return $this->hasMany(AtendimentoPaciente::class, 'paciente_id');
    }

    public function scopeFornecedores(Builder $query): Builder
    {
        return $query->where('tipo', '=', 3);
    }

    public function scopeNotFornecedores(Builder $query): Builder
    {
        return $query->where('tipo', '!=', 3);
    }

    public function scopeSearchByNome(Builder $query, string $nome = ''): Builder
    {
        if(empty($nome)) return $query->orderBy('id', 'desc');

        if(preg_match('/^\d+$/', $nome)) return $query->where('id','like', "{$nome}%")->orderBy('id', 'desc');

        return $query->where('nome', 'like', "%{$nome}%")
            ->orWhere('nome_fantasia', 'like', "%{$nome}%")
            ->orWhere('cpf', 'like', "%{$nome}%")
            ->orderBy('id', 'desc');
    }

    public function agendamentos()
    {
        return $this->hasMany(Agendamentos::class, 'pessoa_id');
    }

    public function estoqueEntradas()
    {
        return $this->hasMany(EstoqueEntradas::class, 'id_fornecedor');
    }
    
    public function agendamentoResumo($userId)
    {
        $usuario_logado = request()->user('instituicao');
        // $usuario_prestador = $usuario_logado->prestadorMedico()->get();
        $instituicao = request()->session()->get('instituicao');

        $instituicao_logada = $usuario_logado->instituicao->where('id', $instituicao)->first();
        $prestadoresIds = explode(',', $instituicao_logada->pivot->visualizar_prestador);

        return $this->hasMany(Agendamentos::class, 'pessoa_id')->where(function($query) use($userId, $prestadoresIds){
            $query->where(function($q) use($userId, $prestadoresIds){
                $q->whereHas('prontuario', function($q1) use($userId, $prestadoresIds){
                    if (!Gate::allows('habilidade_instituicao_sessao', 'visualizar_prontuario_compartilhado')) {
                        $q1->where('usuario_id', $userId);
                        // $q1->orWhere('compartilhado', 1);
                    }else{
                        if(!in_array('', $prestadoresIds)){
                            $q1->whereIn('id', $prestadoresIds);
                        }
                    }
                });
            });
            $query->orWhere(function($q) use($userId, $prestadoresIds){
                $q->whereHas('receituario', function($q1) use($userId, $prestadoresIds){
                    if (!Gate::allows('habilidade_instituicao_sessao', 'visualizar_prontuario_compartilhado')) {
                        $q1->where('usuario_id', $userId);
                        // $q1->orWhere('compartilhado', 1);
                    }else{
                        if(!in_array('', $prestadoresIds)){
                            $q1->whereIn('id', $prestadoresIds);
                        }
                    }
                });
            });
            $query->orWhere(function($q) use($userId, $prestadoresIds){
                $q->whereHas('atestado', function($q1) use($userId, $prestadoresIds){
                    if (!Gate::allows('habilidade_instituicao_sessao', 'visualizar_prontuario_compartilhado')) {
                        $q1->where('usuario_id', $userId);
                        // $q1->orWhere('compartilhado', 1);
                    }else{
                        if(!in_array('', $prestadoresIds)){
                            $q1->whereIn('id', $prestadoresIds);
                        }
                    }
                });
            });
            $query->orWhere(function($q) use($userId, $prestadoresIds){
                $q->whereHas('relatorio', function($q1) use($userId, $prestadoresIds){
                    if (!Gate::allows('habilidade_instituicao_sessao', 'visualizar_prontuario_compartilhado')) {
                        $q1->where('usuario_id', $userId);
                        // $q1->orWhere('compartilhado', 1);
                    }else{
                        if(!in_array('', $prestadoresIds)){
                            $q1->whereIn('id', $prestadoresIds);
                        }
                    }
                });
            });
            $query->orWhere(function($q) use($userId, $prestadoresIds){
                $q->whereHas('exame', function($q1) use($userId, $prestadoresIds){
                    if (!Gate::allows('habilidade_instituicao_sessao', 'visualizar_prontuario_compartilhado')) {
                        $q1->where('usuario_id', $userId);
                        // $q1->orWhere('compartilhado', 1);
                    }else{
                        if(!in_array('', $prestadoresIds)){
                            $q1->whereIn('id', $prestadoresIds);
                        }
                    }
                });
            });
            $query->orWhere(function($q) use($userId, $prestadoresIds){
                $q->whereHas('refracao', function($q1) use($userId, $prestadoresIds){
                    if (!Gate::allows('habilidade_instituicao_sessao', 'visualizar_prontuario_compartilhado')) {
                        $q1->where('usuario_id', $userId);
                        // $q1->orWhere('compartilhado', 1);
                    }else{
                        if(!in_array('', $prestadoresIds)){
                            $q1->whereIn('id', $prestadoresIds);
                        }
                    }
                });
            });
            $query->orWhere(function($q) use($userId, $prestadoresIds){
                $q->whereHas('encaminhamento', function($q1) use($userId, $prestadoresIds){
                    if (!Gate::allows('habilidade_instituicao_sessao', 'visualizar_prontuario_compartilhado')) {
                        $q1->where('usuario_id', $userId);
                        // $q1->orWhere('compartilhado', 1);
                    }else{
                        if(!in_array('', $prestadoresIds)){
                            $q1->whereIn('id', $prestadoresIds);
                        }
                    }
                });
            });
            $query->orWhere(function($q) use($userId, $prestadoresIds){
                $q->whereHas('laudo', function($q1) use($userId, $prestadoresIds){
                    if (!Gate::allows('habilidade_instituicao_sessao', 'visualizar_prontuario_compartilhado')) {
                        $q1->where('usuario_id', $userId);
                        // $q1->orWhere('compartilhado', 1);
                    }else{
                        if(!in_array('', $prestadoresIds)){
                            $q1->whereIn('id', $prestadoresIds);
                        }
                    }
                });
            });
            $query->orWhere(function($q) use($userId, $prestadoresIds){
                $q->whereHas('conclusao', function($q1) use($userId, $prestadoresIds){
                    if (!Gate::allows('habilidade_instituicao_sessao', 'visualizar_prontuario_compartilhado')) {
                        $q1->where('usuario_id', $userId);
                        // $q1->orWhere('compartilhado', 1);
                    }else{
                        if(!in_array('', $prestadoresIds)){
                            $q1->whereIn('id', $prestadoresIds);
                        }
                    }
                });
            });
        })->with(['prontuario' => function($q1) use($userId, $prestadoresIds){
            if (!Gate::allows('habilidade_instituicao_sessao', 'visualizar_prontuario_compartilhado')) {
                        $q1->where('usuario_id', $userId);
                        // $q1->orWhere('compartilhado', 1);
                    }else{
                        if(!in_array('', $prestadoresIds)){
                            $q1->whereIn('id', $prestadoresIds);
                        }
                    }
                },'receituario' => function($q1) use($userId, $prestadoresIds){
                    if (!Gate::allows('habilidade_instituicao_sessao', 'visualizar_prontuario_compartilhado')) {
                        $q1->where('usuario_id', $userId);
                        // $q1->orWhere('compartilhado', 1);
                    }else{
                        if(!in_array('', $prestadoresIds)){
                            $q1->whereIn('id', $prestadoresIds);
                        }
                    }
                },'atestado' => function($q1) use($userId, $prestadoresIds){
                    if (!Gate::allows('habilidade_instituicao_sessao', 'visualizar_prontuario_compartilhado')) {
                        $q1->where('usuario_id', $userId);
                        // $q1->orWhere('compartilhado', 1);
                    }else{
                        if(!in_array('', $prestadoresIds)){
                            $q1->whereIn('id', $prestadoresIds);
                        }
                    }
                },'relatorio' => function($q1) use($userId, $prestadoresIds){
                    if (!Gate::allows('habilidade_instituicao_sessao', 'visualizar_prontuario_compartilhado')) {
                        $q1->where('usuario_id', $userId);
                        // $q1->orWhere('compartilhado', 1);
                    }else{
                        if(!in_array('', $prestadoresIds)){
                            $q1->whereIn('id', $prestadoresIds);
                        }
                    }
                },'exame' => function($q1) use($userId, $prestadoresIds){
                    if (!Gate::allows('habilidade_instituicao_sessao', 'visualizar_prontuario_compartilhado')) {
                        $q1->where('usuario_id', $userId);
                        // $q1->orWhere('compartilhado', 1);
                    }else{
                        if(!in_array('', $prestadoresIds)){
                            $q1->whereIn('id', $prestadoresIds);
                        }
                    }
                },'refracao' => function($q1) use($userId, $prestadoresIds){
                    if (!Gate::allows('habilidade_instituicao_sessao', 'visualizar_prontuario_compartilhado')) {
                        $q1->where('usuario_id', $userId);
                        // $q1->orWhere('compartilhado', 1);
                    }else{
                        if(!in_array('', $prestadoresIds)){
                            $q1->whereIn('id', $prestadoresIds);
                        }
                    }
                },'encaminhamento' => function($q1) use($userId, $prestadoresIds){
                    if (!Gate::allows('habilidade_instituicao_sessao', 'visualizar_prontuario_compartilhado')) {
                        $q1->where('usuario_id', $userId);
                        // $q1->orWhere('compartilhado', 1);
                    }else{
                        if(!in_array('', $prestadoresIds)){
                            $q1->whereIn('id', $prestadoresIds);
                        }
                    }
                },'laudo' => function($q1) use($userId, $prestadoresIds){
                    if (!Gate::allows('habilidade_instituicao_sessao', 'visualizar_prontuario_compartilhado')) {
                        $q1->where('usuario_id', $userId);
                        // $q1->orWhere('compartilhado', 1);
                    }else{
                        if(!in_array('', $prestadoresIds)){
                            $q1->whereIn('id', $prestadoresIds);
                        }
                    }
                },'conclusao' => function($q1) use($userId, $prestadoresIds){
                    if (!Gate::allows('habilidade_instituicao_sessao', 'visualizar_prontuario_compartilhado')) {
                        $q1->where('usuario_id', $userId);
                        // $q1->orWhere('compartilhado', 1);
                    }else{
                        if(!in_array('', $prestadoresIds)){
                            $q1->whereIn('id', $prestadoresIds);
                        }
                    }
                }]);
    }

    public function scopeGetPacientes(Builder $query, string $nome = ''): Builder
    {

        $query->where('tipo', '<>', 3);

        $query->orderBy('id', 'desc');

        if(empty($nome)) return $query;

        if(preg_match('/^\d+$/', $nome)) return $query->where('id','like', "{$nome}%");

        return $query->where('nome', 'like', "%{$nome}%")
            ->orWhere('nome_fantasia', 'like', "%{$nome}%")
            ->orWhere('cpf', 'like', "%{$nome}%");
    }

    public function scopeGetFornecedoresEntrada(Builder $query, $instituicao, $produto_id):Builder
    {
        $query->where('instituicao_id', $instituicao);
        // $query->where('tipo', 2);
        $query->with(['estoqueEntradas.estoqueEntradaProdutos' => function($q) use($produto_id){
            $q->where('id_produto', $produto_id);
        }]);
        $query->whereHas('estoqueEntradas.estoqueEntradaProdutos', function($q) use($produto_id){
            $q->where('id_produto', $produto_id);
        });
        return $query;
    }
}
