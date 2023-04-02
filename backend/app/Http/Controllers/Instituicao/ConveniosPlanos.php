<?php

namespace App\Http\Controllers\Instituicao;

use App\Http\Controllers\Controller;
use App\Http\Requests\ConveniosPlanos\CriarRequestConvenioPlano;
use App\Http\Requests\ConveniosPlanos\EditarRequestConvenioPlano;
use App\Convenio;
use App\ConvenioPlano;
use App\Http\Requests\ConveniosPlanos\GetPlanosRequest;
use App\Instituicao;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ConveniosPlanos extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Convenio $convenio)
    {

        $this->authorize('habilidade_instituicao_sessao', 'visualizar_convenio_planos');
     //   dd($convenio->toArray());
        $instituicao_id = $request->session()->get('instituicao');
        abort_unless($convenio->instituicao_id === $instituicao_id, 403);

        return view('instituicao.conveniosplanos/lista', [
            'convenio' => $convenio,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, Convenio $convenio)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_convenio_planos');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        abort_unless($convenio->instituicao_id === $instituicao->id, 403);
        $regrasCobranca = $instituicao->regrasCobranca()->get();
        return view('instituicao.conveniosplanos/criar', [
            'convenio' => $convenio,
            'regrasCobranca' => $regrasCobranca,
        ]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CriarRequestConvenioPlano $request, Convenio $convenio)
    {

        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_convenio');
        $instituicao_id = $request->session()->get('instituicao');
        abort_unless($convenio->instituicao_id === $instituicao_id, 403);

        $dados = $request->validated();

        DB::transaction(function () use ($request, $dados, $convenio, $instituicao_id){

            $convenios = $convenio->planos()->create($dados);

            $usuario_logado = $request->user('instituicao');

            $convenios->criarLogCadastro(
              $usuario_logado,
              $instituicao_id
            );

            return $convenios;
        });

        return redirect()->route('instituicao.convenios.planos.index',$convenio)->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Plano criado com sucesso!'
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
    public function edit(Request $request, Convenio $convenio, ConvenioPlano $plano)
    {

        $this->authorize('habilidade_instituicao_sessao', 'editar_convenio_planos');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        abort_unless($convenio->instituicao_id === $instituicao->id, 403);
        abort_unless($convenio->id === $plano->convenios_id, 403);
        $regrasCobranca = $instituicao->regrasCobranca()->get();
        return view('instituicao.conveniosplanos/editar', \compact('convenio','plano', 'regrasCobranca'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EditarRequestConvenioPlano $request, Convenio $convenio, ConvenioPlano $plano)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_convenio_planos');
        $instituicao_id = $request->session()->get('instituicao');
        abort_unless($convenio->instituicao_id === $instituicao_id, 403);
        abort_unless($convenio->id === $plano->convenios_id, 403);
        $dados = $request->validated();

        DB::transaction(function () use ($request, $dados, $plano, $instituicao_id){

            $plano->update($dados);
            $usuario_logado = $request->user('instituicao');
            $plano->criarLogEdicao(
              $usuario_logado,
              $instituicao_id
            );

            return $plano;
        });


        //return redirect()->route('instituicaoistradores.edit', [$usuario])->with('mensagem', 'Salvo com sucesso');
        return redirect()->route('instituicao.convenios.planos.index', [$convenio])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Plano atualizado com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Convenio $convenio, ConvenioPlano $plano)
    {

        $this->authorize('habilidade_instituicao_sessao', 'excluir_convenio_planos');
        $instituicao_id = $request->session()->get('instituicao');
        abort_unless($convenio->instituicao_id === $instituicao_id, 403);
        abort_unless($convenio->id === $plano->convenios_id, 403);

        DB::transaction(function () use ($request, $plano, $instituicao_id){
            $plano->delete($plano);

            $usuario_logado = $request->user('instituicao');
            $plano->criarLogExclusao(
              $usuario_logado,
              $instituicao_id
            );

            return $plano;
        });

        return redirect()->route('instituicao.convenios.planos.index', [$convenio ])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Plano excluído com sucesso!'
        ]);

    }

    public function getConveniosPlanos(GetPlanosRequest $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_convenio_planos');
        $instituicao = Instituicao::findOrFail($request->session()->get('instituicao'));
        $dados = $request->validated();
        $convenio = $instituicao->convenios()->where('id', $dados['convenio_id'])->first();
        $busca = $dados['search'] ?? '';

        $result = $convenio->planos()
            ->where('nome', 'like', "%$busca%")
            ->orderBy('id', 'desc')
            ->simplePaginate(15);
            
        return response()->json($result);
    }
}
