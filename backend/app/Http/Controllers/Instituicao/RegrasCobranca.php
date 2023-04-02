<?php

namespace App\Http\Controllers\Instituicao;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegrasCobranca\CriarRegraCobrancaRequest;
use App\Instituicao;
use App\RegraCobranca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RegrasCobranca extends Controller
{
    public function index()
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_regras_cobranca');

        return view('instituicao.regra_cobranca.lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_regras_cobranca');
        
        return view('instituicao.regra_cobranca.criar');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CriarRegraCobrancaRequest $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_regras_cobranca');
        $instituicao = Instituicao::find($request->session()->get("instituicao"));
         
        DB::transaction(function() use ($request, $instituicao){
            $usuario_logado = $request->user('instituicao');
            $dados = $request->validated();
            $dados['horario_especial'] = $request->boolean('horario_especial');
            $dados['internacao'] = $request->boolean('internacao');
            $dados['ambulatorial'] = $request->boolean('ambulatorial');
            $dados['urgencia_emergencia'] = $request->boolean('urgencia_emergencia');
            $dados['externo'] = $request->boolean('externo');
            $dados['home_care'] = $request->boolean('home_care');
            
            $regra = $instituicao->regrasCobranca()->create($dados);
            $regra->criarLogCadastro($usuario_logado, $instituicao->id);
        });

        return redirect()->route('instituicao.regrasCobranca.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Regra de cobrança criada com sucesso!'
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
    public function edit(Request $request, RegraCobranca $regra)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_regras_cobranca');

        $instituicaoId = $request->session()->get('instituicao');
        $instituicao = Instituicao::find($instituicaoId);
        abort_unless($instituicao->id === $regra->instituicao_id, 403);

        return view('instituicao.regra_cobranca.editar', \compact('regra'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CriarRegraCobrancaRequest $request, RegraCobranca $regra)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_regras_cobranca');

        $instituicao = $request->session()->get("instituicao");
        abort_unless($instituicao===$regra->instituicao_id, 403);

        DB::transaction(function() use ($request, $instituicao, $regra){
            $usuario_logado = $request->user('instituicao');
            $dados = $request->validated();
            $dados['horario_especial'] = $request->boolean('horario_especial');
            $dados['internacao'] = $request->boolean('internacao');
            $dados['ambulatorial'] = $request->boolean('ambulatorial');
            $dados['urgencia_emergencia'] = $request->boolean('urgencia_emergencia');
            $dados['externo'] = $request->boolean('externo');
            $dados['home_care'] = $request->boolean('home_care');
            
            $regra->update($dados);
            $regra->criarLogEdicao($usuario_logado, $instituicao);
        });

        return redirect()->route('instituicao.regrasCobranca.edit', [$regra])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Regra de cobrança alterada com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, RegraCobranca $regra)
    {
        $this->authorize('habilidade_instituicao_sessao', 'excluir_tipos_documentos');
        
        $instituicao = $request->session()->get("instituicao");
        abort_unless($instituicao===$regra->instituicao_id, 403);

        DB::transaction(function () use ($regra, $request, $instituicao) {
            $usuario_logado = $request->user('instituicao');
            $regra->delete();
            $regra->criarLogExclusao($usuario_logado, $instituicao);
            

            return $regra;
        });
            
        return redirect()->route('instituicao.regrasCobranca.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Regra de cobrança excluída com sucesso!'
        ]);
    
    }
}
