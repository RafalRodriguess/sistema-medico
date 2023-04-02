<?php

namespace App\Http\Controllers\Instituicao;

use App\Carteirinha;
use App\Convenio;
use App\ConvenioPlano;
use App\Http\Controllers\Controller;
use App\Http\Requests\Carteirinha\CriarCarteirinhaRequest;
use App\Instituicao;
use App\Pessoa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Carteirinhas extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Pessoa $pessoa)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_carteirinha');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $pessoa->instituicao_id, 403);
        $carteirinhas = Carteirinha::where('pessoa_id', $pessoa->id)->get();
        $convenios = Convenio::get();

        // dd($convenios->toArray());

        return view('instituicao.pessoas.carteirinhas.lista', compact('pessoa'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, Pessoa $pessoa)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_carteirinha');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $pessoa->instituicao_id, 403);
        $convenios = $instituicao->convenios()->get();

        // dd($convenios->toArray());

        return view('instituicao.pessoas.carteirinhas.criar', compact('pessoa', 'convenios'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CriarCarteirinhaRequest $request, Pessoa $pessoa)
    {
        //$this->authorize('habilidade_instituicao_sessao', 'cadastrar_cartao_credito');

        $instituicao = Instituicao::find($request->session()->get("instituicao"));
        // dd( $request->validated() );
        DB::transaction(function() use ($request, $instituicao){
            $usuario_logado = $request->user('instituicao');
            $dados = $request->validated();

            $carteirinha = Carteirinha::create($dados);
            $carteirinha->criarLogCadastro($usuario_logado, $instituicao->id);
        });

        return redirect()->route('instituicao.carteirinhas.index', [$pessoa])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Carteirinha de convenio criada com sucesso!'
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
    public function edit(Request $request, Pessoa $pessoa, Carteirinha $carteirinha)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_carteirinha');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $pessoa->instituicao_id, 403);
        $convenios = $instituicao->convenios()->get();
        $planos = ConvenioPlano::where('convenios_id', $carteirinha->convenio_id)->get();

        // dd($convenios->toArray());

        return view('instituicao.pessoas.carteirinhas.editar', compact('pessoa', 'convenios', 'carteirinha', 'planos'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CriarCarteirinhaRequest $request, Pessoa $pessoa, Carteirinha $Carteirinha)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_carteirinha');

        $instituicao = $request->session()->get("instituicao");
        abort_unless($instituicao===$pessoa->instituicao_id, 403);
        DB::transaction(function() use ($request, $instituicao, $Carteirinha){
            $usuario_logado = $request->user('instituicao');
            $dados = $request->validated();

            $Carteirinha->update($dados);
            $Carteirinha->criarLogEdicao($usuario_logado, $instituicao);
        });

        return redirect()->route('instituicao.carteirinhas.edit', [$pessoa, $Carteirinha])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Carteirinha de convenio alterada com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Pessoa $pessoa, Carteirinha $carteirinha)
    {
        $this->authorize('habilidade_instituicao_sessao', 'excluir_carteirinha');

        $instituicao = $request->session()->get("instituicao");
        abort_unless($instituicao===$pessoa->instituicao_id, 403);
        DB::transaction(function () use ($carteirinha, $request, $instituicao) {
            $usuario_logado = $request->user('instituicao');
            $carteirinha->delete();
            $carteirinha->criarLogExclusao($usuario_logado, $instituicao);

            return $carteirinha;
        });

        return redirect()->route('instituicao.carteirinhas.index', [$pessoa])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Carteirinha de convenio excluídas com sucesso!'
        ]);
    }

    public function getPlanos(Request $request)
    {
        $planos = [];
        
        if(!empty($request->input('convenio_id'))){
            $planos = ConvenioPlano::where('convenios_id', $request->input('convenio_id'))->get();
        }

        return response()->json($planos);
    }
}
