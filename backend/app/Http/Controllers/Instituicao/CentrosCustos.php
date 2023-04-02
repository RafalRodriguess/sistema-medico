<?php

namespace App\Http\Controllers\Instituicao;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\CentroCusto;
use App\Http\Requests\CentrosCustos\{
    InstituicaoCreateCentroCusto,
    InstituicaoEditCentroCusto,
};
use App\Instituicao;
use Illuminate\Support\Facades\DB;

class CentrosCustos extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_centro_de_custo');

        return view('instituicao.financeiro.cc.lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_centro_de_custo');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        return view('instituicao.financeiro.cc.criar', [
            'centros_custos' => $instituicao->centrosCustos()->get(),
            'setores_exame' => $instituicao->setoresExame()->get(),
            'grupos' => CentroCusto::getGrupos(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InstituicaoCreateCentroCusto $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_centro_de_custo');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $dados = $request->validated();
        // Validar se o setor faz parte da instituição que o usuario está logado pertence
        $dados['setor_exame_id'] = !empty($instituicao->setoresExame()->find($dados['setor_exame_id'])) ? $dados['setor_exame_id'] : null;
        DB::transaction(function () use ($dados, $instituicao, $request) {
            if ($dados['pai_id'] == 0) $dados['pai_id'] = null;
            $dados['lancamento'] = (isset($dados['lancamento'])) ? true : false;
            $dados['ativo'] = (isset($dados['ativo'])) ? true : false;
            $centro_custo = $instituicao->centrosCustos()->create($dados);
            $centro_custo->criarLogInstituicaoCadastro($request->user('instituicao'), $instituicao->id);
        });

        return redirect()->route('instituicao.financeiro.cc.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Centro de Custo criado com sucesso!'
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
    public function edit(Request $request, CentroCusto $centro_custo)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_centro_de_custo');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        return view('instituicao.financeiro.cc.editar', [
            'centro_custo' => $centro_custo,
            'setores_exame' => $instituicao->setoresExame()->get(),
            'grupos' => CentroCusto::getGrupos(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(InstituicaoEditCentroCusto $request, CentroCusto $centro_custo)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_centro_de_custo');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $centro_custo->instituicao_id, 403);

        $dados = $request->validated();
        // Validar se o setor faz parte da instituição que o usuario está logado pertence
        $dados['setor_exame_id'] = !empty($instituicao->setoresExame()->find($dados['setor_exame_id'])) ? $dados['setor_exame_id'] : null;
        $centro_custo = DB::transaction(function () use ($dados, $request, $centro_custo, $instituicao) {
            $dados['pai_id'] = $centro_custo->pai_id;
            $dados['lancamento'] = (isset($dados['lancamento'])) ? true : false;
            $dados['ativo'] = (isset($dados['ativo'])) ? true : false;
            $centro_custo->update($dados);
            $centro_custo->criarLogInstituicaoEdicao($request->user('instituicao'), $instituicao->id);
            return $centro_custo;
        });

        return redirect()->route('instituicao.financeiro.cc.edit', [$centro_custo])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Centro de Custo atualizado com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, CentroCusto $centro_custo)
    {
        $this->authorize('habilidade_instituicao_sessao', 'excluir_centro_de_custo');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $centro_custo->instituicao_id, 403);

        DB::transaction(function () use ($centro_custo, $request) {
            if (!$centro_custo->pai_id) {
                $filhos = $centro_custo->filhos();
                foreach ($filhos as $filho) {
                    $filho->delete();
                }
            }
            $centro_custo->delete();
            $usuario_logado = $request->user('instituicao');
            $centro_custo->criarLogInstituicaoExclusao($usuario_logado);
            return $centro_custo;
        });

        return redirect()->route('instituicao.financeiro.cc.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Centro de Custo excluído com sucesso!'
        ]);
    }

    public function getCentrosDeCusto(Request $request)
    {
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        $busca = $request->get('search');
        $centros = $instituicao->centrosCustos()->where('descricao', 'like', "%$busca%")->simplePaginate(15);
        $morePages = true;
        if (empty($centros->nextPageUrl())) {
            $morePages = false;
        }
        return response()->json([
            "results" => $centros->items(),
            "pagination" => array(
                "more" => $morePages,
            )
        ]);
    }
}
