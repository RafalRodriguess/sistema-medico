<?php

namespace App\Http\Controllers\Instituicao;

use App\Convenio;
use App\ConvenioPlano;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProcedimentoAtendimento\CriarProcedimentoAtendimentoRequest;
use App\Instituicao;
use App\Procedimento;
use App\ProcedimentoAtendimento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProcedimentosAtendimentos extends Controller
{
    public function index()
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_procedimentos_atendimentos');

        return view('instituicao.procedimento_atendimento.lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_procedimentos_atendimentos');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        $convenios = $instituicao->convenios()->get();
        $origens = $instituicao->origens()->get();
        $internacoes = $instituicao->unidadesInternacoes()->get();
        $grupos_faturamento = $instituicao->gruposFaturamento()->get();
        return view('instituicao.procedimento_atendimento.criar', \compact('convenios', 'origens', 'internacoes', 'grupos_faturamento'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CriarProcedimentoAtendimentoRequest $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_procedimentos_atendimentos');
        $instituicao = Instituicao::find($request->session()->get("instituicao"));
         
        DB::transaction(function() use ($request, $instituicao){
            $usuario_logado = $request->user('instituicao');
            $dados = $request->validated();

            $proc = collect($request->validated()['proc'])
                ->filter(function($proc) {
                    return !is_null($proc['procedimento_id']);
                })
                ->map(function($proc) {
                    return [
                        'grupo_faturamento_id' => $proc['grupo_faturamento_id'],
                        'procedimento_cod' => $proc['procedimento_cod'],
                        'procedimento_id' => $proc['procedimento_id'],
                        'quantidade' => $proc['quantidade'],
                    ];
                });
            
            $procedimento = $instituicao->procedimentoAtendimentos()->create($dados);
            $procedimento->criarLogCadastro($usuario_logado, $instituicao->id);

            $procedimento->procedimento()->attach($proc);
            $procedimento->criarLog($usuario_logado, 'Procedimentos vinculados', $proc, $instituicao->id);
        });

        return redirect()->route('instituicao.procedimentoAtendimentos.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Procedimentos do atendimento criado com sucesso!'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, ProcedimentoAtendimento $procedimento)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_procedimentos_atendimentos');

        $instituicaoId = $request->session()->get('instituicao');
        $instituicao = Instituicao::find($instituicaoId);
        abort_unless($instituicao->id === $procedimento->instituicao_id, 403);
        $convenios = $instituicao->convenios()->get();
        $origens = $instituicao->origens()->get();
        $internacoes = $instituicao->unidadesInternacoes()->get();
        $grupos_faturamento = $instituicao->gruposFaturamento()->get();
        $planos = ConvenioPlano::where('convenios_id', $procedimento->convenio_id)->get();
        return view('instituicao.procedimento_atendimento.editar', \compact('regra', 'convenios', 'origens', 'procedimento', 'internacoes', 'grupos_faturamento', 'planos'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CriarProcedimentoAtendimentoRequest $request, ProcedimentoAtendimento $procedimento)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_procedimentos_atendimentos');

        $instituicao = $request->session()->get("instituicao");
        abort_unless($instituicao===$procedimento->instituicao_id, 403);

        DB::transaction(function() use ($request, $instituicao, $procedimento){
            $usuario_logado = $request->user('instituicao');
            $dados = $request->validated();
            
            $proc = collect($request->validated()['proc'])
                ->filter(function($proc) {
                    return !is_null($proc['procedimento_id']);
                })
                ->map(function($proc) {
                    return [
                        'grupo_faturamento_id' => $proc['grupo_faturamento_id'],
                        'procedimento_cod' => $proc['procedimento_cod'],
                        'procedimento_id' => $proc['procedimento_id'],
                        'quantidade' => $proc['quantidade'],
                    ];
                });
            
            $procedimento->update($dados);
            $procedimento->criarLogEdicao($usuario_logado, $instituicao);

            $procedimento->procedimento()->detach();
            $procedimento->procedimento()->attach($proc);
            $procedimento->criarLog($usuario_logado, 'Procedimentos editados vinculados', $proc, $instituicao);

        });

        return redirect()->route('instituicao.procedimentoAtendimentos.edit', [$procedimento])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Procedimentos do atendimento alterado com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, ProcedimentoAtendimento $procedimento)
    {
        $this->authorize('habilidade_instituicao_sessao', 'excluir_tipos_documentos');
        
        $instituicao = $request->session()->get("instituicao");
        abort_unless($instituicao===$procedimento->instituicao_id, 403);

        DB::transaction(function () use ($procedimento, $request, $instituicao) {
            $usuario_logado = $request->user('instituicao');
            $procedimento->delete();
            $procedimento->criarLogExclusao($usuario_logado, $instituicao);

            return $procedimento;
        });
            
        return redirect()->route('instituicao.procedimentoAtendimentos.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Procedimentos do atendimento excluído com sucesso!'
        ]);
    
    }

    public function getPlanos(Request $request, Convenio $convenio)
    {
        $instituicaoId = $request->session()->get('instituicao');
        abort_unless($instituicaoId === $convenio->instituicao_id, 403);

        $planos = $convenio->planos()->get();

        return response()->json($planos);
    }
    
    public function getCodProcedimento(Request $request, Procedimento $procedimento)
    {
        return response()->json($procedimento->cod);
    }

    public function getProcedimentoGerais(Request $request)
    {
        if ($request->ajax())
        {
            $instituicao_id = $request->session()->get('instituicao');
            $nome = ($request->input('q')) ? $request->input('q') : '';

            $procedimentos = Procedimento::where('descricao', 'like', "%{$nome}%")->whereHas('procedimentoInstituicao', function($q) use($instituicao_id){
                $q->where('instituicoes_id', $instituicao_id);
            })->simplePaginate(100);

            $morePages=true;
            if (empty($procedimentos->nextPageUrl())){
                $morePages=false;
            }

            $results = array(
                "results" => $procedimentos->items(),
                "pagination" => array(
                    "more" => $morePages,
                )
            );
            // dd($pacientes->per_page());
            return response()->json($results);
        }
    }
}
