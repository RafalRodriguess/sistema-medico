<?php

namespace App\Http\Controllers\Instituicao;

use App\Convenio;
use App\ConveniosProcedimentos;
use App\GruposProcedimentos;
use App\Http\Controllers\Controller;
use App\Http\Requests\Procedimentos\BuscarProcedimentosConvenios;
use App\Http\Requests\Procedimentos\BuscarProcedimentosInstituicao;
use App\Http\Requests\Procedimentos\CreateProcedimentoInstitucaoRequest;
use App\Http\Requests\Procedimentos\UpdateProcedimentoInstitucaoRequest;
use App\Http\Requests\Procedimentos\CriarProcedimentoRequest;
use App\Instituicao;
use App\InstituicaoProcedimentos;
use App\ModalidadeExame;
use Illuminate\Http\Request;
use App\Procedimento;
use Illuminate\Support\Facades\DB;
use App\Support\ConverteValor;

class Procedimentos extends Controller
{
        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
       $this->authorize('habilidade_instituicao_sessao', 'visualizar_cadastro_procedimentos');

       return view('instituicao.procedimentos/lista');
   }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_cadastro_procedimentos');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        $grupos = $instituicao->grupoProcedimentos()->get();
        $servicosHospitalares = Procedimento::getServicoHospitalares();
        $compromissos = $instituicao->compromissos()->get();
        return view('instituicao.procedimentos/criar', compact('grupos', 'servicosHospitalares', 'compromissos'));
    }


    public function edit(Request $request, Procedimento $cadastro_procedimento)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_cadastro_procedimentos');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        $servicosHospitalares = Procedimento::getServicoHospitalares();
        $compromissos = $instituicao->compromissos()->get();
        return view('instituicao.procedimentos/editar', \compact('cadastro_procedimento', 'servicosHospitalares', 'compromissos'));
    }


    public function update(UpdateProcedimentoInstitucaoRequest $request, Procedimento $cadastro_procedimento)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_cadastro_procedimentos');
        $instituicaoId = $request->session()->get('instituicao');
        $id = $cadastro_procedimento->id;

        $dados = $request->validated();
        $dados['odontologico'] = $request->boolean('odontologico');
        $dados['possui_regiao'] = $request->boolean('possui_regiao');
        $dados['pacote'] = $request->boolean('pacote');
        $dados['recalcular'] = $request->boolean('recalcular');
        $dados['busca_ativa'] = $request->boolean('busca_ativa');
        $dados['parto'] = $request->boolean('parto');
        $dados['diaria_uti_rn'] = $request->boolean('diaria_uti_rn');
        $dados['md_mt'] = $request->boolean('md_mt');
        $dados['pesquisa_satisfacao'] = $request->boolean('pesquisa_satisfacao');
        $dados['exige_quantidade'] = $request->boolean('exige_quantidade');
        $dados['n_cobrar_agendamento'] = $request->boolean('n_cobrar_agendamento');
        $dados['tipo_limpeza'] = $request->boolean('tipo_limpeza');
        $dados['valor_custo'] = ConverteValor::converteDecimal($dados['valor_custo']);
        //     'tipo_guia' => $request->tipo_guia,
        //     'divisao_tipo_guia' =>  $request->divisao_tipo_guia,
        // ];

        // dd($dados);
        abort_unless($instituicaoId === $cadastro_procedimento->where('id', $id)->with('procedimentoInstituicao')->first()->procedimentoInstituicao->first()->instituicoes_id, 403);


        DB::transaction(function () use ($request, $cadastro_procedimento, $dados, $instituicaoId){
            $cadastro_procedimento->update($dados);

            $usuario_logado = $request->user('instituicao');

            $cadastro_procedimento->criarLogEdicao($usuario_logado);

            return $cadastro_procedimento;
        });

        //return redirect()->route('administradores.edit', [$usuario])->with('mensagem', 'Salvo com sucesso');
        return redirect()->route('instituicao.cadastro-procedimentos.edit', [$cadastro_procedimento])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Procedimento atualizado com sucesso!'
        ]);
    }



    public function store(CreateProcedimentoInstitucaoRequest $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_cadastro_procedimentos');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        $usuario_logado = $request->user('instituicao');

        $dados = $request->validated();

        $dados['odontologico'] = $request->boolean('odontologico');
        $dados['possui_regiao'] = $request->boolean('possui_regiao');
        $dados['pacote'] = $request->boolean('pacote');
        $dados['recalcular'] = $request->boolean('recalcular');
        $dados['busca_ativa'] = $request->boolean('busca_ativa');
        $dados['parto'] = $request->boolean('parto');
        $dados['diaria_uti_rn'] = $request->boolean('diaria_uti_rn');
        $dados['md_mt'] = $request->boolean('md_mt');
        $dados['pesquisa_satisfacao'] = $request->boolean('pesquisa_satisfacao');
        $dados['exige_quantidade'] = $request->boolean('exige_quantidade');
        $dados['n_cobrar_agendamento'] = $request->boolean('n_cobrar_agendamento');
        $dados['tipo_limpeza'] = $request->boolean('tipo_limpeza');
        $procedimento = [
            'descricao' => $dados['descricao'],
            'tipo' => $dados['tipo'],
            'odontologico' => $dados['odontologico'],
            'possui_regiao' => $dados['possui_regiao'],
            'pacote' => $dados['pacote'],
            'recalcular' => $dados['recalcular'],
            'busca_ativa' => $dados['busca_ativa'],
            'parto' => $dados['parto'],
            'diaria_uti_rn' => $dados['diaria_uti_rn'],
            'md_mt' => $dados['md_mt'],
            'sexo' => $dados['sexo'],
            'qtd_maxima' => $dados['qtd_maxima'],
            'tipo_servico' => $dados['tipo_servico'],
            'tipo_consulta' => $dados['tipo_consulta'],
            'pesquisa_satisfacao' => $dados['pesquisa_satisfacao'],
            'cod' => $dados['cod'],
            'exige_quantidade' => $dados['exige_quantidade'],
            'n_cobrar_agendamento' => $dados['n_cobrar_agendamento'],
            'vinculo_tuss_id' => $dados['vinculo_tuss_id'],
            'valor_custo' => ConverteValor::converteDecimal($dados['valor_custo']),
            'duracao_atendimento' => $dados['duracao_atendimento'],
            'tipo_guia' => $request->tipo_guia,
            'compromisso_id' => $dados['compromisso_id'],
            'tipo_limpeza' => $dados['tipo_limpeza'],
        ];

        DB::transaction(function () use ($procedimento, $request, $dados,  $instituicao, $usuario_logado){
            $procedimento = Procedimento::create($procedimento);
            //$procedimento->criarLogCadastro($usuario_logado);

            $procInstituicao = [
                'grupo_id' => $dados['grupo_id'],
                'instituicoes_id' => $instituicao->id,
                'procedimentos_id' => $procedimento->id
            ];

            $procedimentosInstituicao = InstituicaoProcedimentos::create($procInstituicao);

                $procedimentosInstituicao->criarLogCadastroProcedimentos(
                $usuario_logado,
                $instituicao->id
            );

            return $procedimento;
        });

        return redirect()->route('instituicao.cadastro-procedimentos.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Procedimento criado com sucesso!'
        ]);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Procedimento  $procedimento
     * @return \Illuminate\Http\Response
     */

    public function destroy(Request $request, Procedimento $cadastro_procedimento)
    {

        $id = $cadastro_procedimento->id;
        $instituicaoId = $request->session()->get('instituicao');
        abort_unless($instituicaoId === $cadastro_procedimento->where('id', $id)->with('procedimentoInstituicao')->first()->procedimentoInstituicao->first()->instituicoes_id, 403);
        $procedimentoInstituicao = $cadastro_procedimento->where('id', $id)->with('procedimentoInstituicao')->first()->procedimentoInstituicao->first();

        // dd($procedimentoInstituicao);

        $this->authorize('habilidade_instituicao_sessao', 'excluir_cadastro_procedimentos');

        DB::transaction(function () use ($request, $cadastro_procedimento, $procedimentoInstituicao){
            $procedimentoInstituicao->delete();

            $usuario_logado = $request->user('instituicao');
            $procedimentoInstituicao->criarLogExclusao($usuario_logado);

            return $procedimentoInstituicao;
        });

        return redirect()->route('instituicao.cadastro-procedimentos.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Procedimento excluído com sucesso!'
        ]);
    }

    public function buscarProcedimentosConvenio(BuscarProcedimentosConvenios $request)
    {
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        $busca = $request->get('search', '');
        $convenio = $request->get('convenio_id');

        $procedimentos_convenio = ConveniosProcedimentos::join('procedimentos_instituicoes', 'procedimentos_instituicoes.id', 'procedimentos_instituicoes_id')
            ->select(
                'procedimentos_instituicoes_convenios.*',
                'procedimentos.descricao as procedimento_descricao',
                'procedimentos.id as procedimento_id'
            )
            ->join('procedimentos', 'procedimentos.id', 'procedimentos_instituicoes.procedimentos_id')
            ->where('procedimentos_instituicoes.instituicoes_id', $instituicao->id)
            ->where('procedimentos.descricao', 'like', "%$busca%");
        if(!empty($convenio)) {
            $procedimentos_convenio->where('convenios_id', $convenio);
        }

        $procedimentos_convenio = $procedimentos_convenio->simplePaginate(50);
        return response()->json([
            'result' => true,
            'procedimentos' => $procedimentos_convenio,
            'next_page' => $procedimentos_convenio->nextPageUrl()
        ]);
    }

    /**
     * Método utilizado para buscar procedimentos de instituicção atravéz
     * de ajax e post
     */
    public function buscarProcedimentosInstituicao(BuscarProcedimentosInstituicao $request)
    {
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        $dados = collect($request->validated());
        $busca = $dados->get('search', '');
        $procedimentos = $instituicao->procedimentosInstituicoes()
            ->select(
                'procedimentos_instituicoes.*',
                'procedimentos.descricao as procedimento_descricao',
                'procedimentos.id as procedimento_id'
            )
            ->join('procedimentos', 'procedimentos.id', 'procedimentos_id')
            ->join('procedimentos_instituicoes_convenios', 'procedimentos_instituicoes_convenios.procedimentos_instituicoes_id', 'procedimentos_instituicoes.id')
            ->where(function($query) use ($busca) {
                $query->where('procedimentos.descricao', 'like', "%$busca%")
                    ->orWhere('procedimentos.id', 'like', "%$busca%");
            })
            ->where('procedimentos_instituicoes_convenios.convenios_id', $dados->get('convenio_id'))
            ->whereNull([
                'procedimentos_instituicoes_convenios.deleted_at',
                'procedimentos_instituicoes.deleted_at',
                'procedimentos.deleted_at'
                ])
            ->simplePaginate(50);

        return response()->json([
            'procedimentos' => $procedimentos,
            'next_page' => $procedimentos->nextPageUrl(),
            'result' => true
        ]);
    }
}
