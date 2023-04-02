<?php

namespace App\Http\Controllers\Instituicao;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Origem;
use App\Instituicao;
use App\Http\Requests\Origem\{
    InstituicaoCreateOrigem,
    InstituicaoEditOrigem
};
use Illuminate\Support\Facades\DB;

class Origens extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_origem');

        return view('instituicao.origem.lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_origem');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        return view('instituicao.origem.criar', [
            'centros_custos' => $instituicao->centrosCustos()->get(),
            'tipos' => Origem::getTipos(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InstituicaoCreateOrigem $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_origem');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $dados = $request->validated();

        DB::transaction(function () use($dados, $request, $instituicao) {
            $dados['ativo'] = (isset($dados['ativo']))? true: false;
            $origem = $instituicao->origens()->create($dados);
            $origem->criarLogInstituicaoCadastro($request->user('instituicao'), $instituicao->id);
        });

        return redirect()->route('instituicao.origem.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Origem registrada com sucesso!'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Origem $origem)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_origem');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $origem->instituicao_id, 403);

        return view('instituicao.origem.editar', [
            'centros_custos' => $instituicao->centrosCustos()->get(),
            'tipos' => Origem::getTipos(),
            'origem' => $origem,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(InstituicaoEditOrigem $request, Origem $origem)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_origem');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $origem->instituicao_id, 403);

        $dados = $request->validated();

        $origem = DB::transaction(function () use($dados, $request, $origem, $instituicao) {
            $dados['ativo'] = (isset($dados['ativo']))? true: false;
            $origem->update($dados);
            $origem->criarLogInstituicaoEdicao($request->user('instituicao'), $instituicao->id);
            return $origem;
        });

        return redirect()->route('instituicao.origem.edit', [$origem])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Origem Editada com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Origem $origem)
    {
        $this->authorize('habilidade_instituicao_sessao', 'excluir_origem');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $origem->instituicao_id, 403);

        DB::transaction(function () use ($origem, $request, $instituicao) {
            $origem->delete();
            $origem->criarLogExclusao($request->user('instituicao'), $instituicao->id);
        });

        return redirect()->route('instituicao.origem.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Origem excluída com sucesso!'
        ]);
    }

    public function buscarOrigem(Request $request)
    {
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        if($request->get('id', false)) {
            return response()->json($instituicao->origens()->find($request->get('id')));
        } else {
            return response()->json($instituicao->origens()->where('descricao', 'like', "%{$request->get('search')}%")->simplePaginate(15));
        }
    }
}
