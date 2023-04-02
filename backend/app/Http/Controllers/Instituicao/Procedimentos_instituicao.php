<?php

namespace App\Http\Controllers\Instituicao;

use App\Convenio;
use App\Http\Controllers\Controller;
use App\Instituicao;
use App\GruposProcedimentos;
use App\Procedimento;
use App\InstituicaoProcedimentos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ProcedimentosInstituicao\CriarProcedimentoInstituicaoRequest;
use App\Http\Requests\ProcedimentosInstituicao\EditarProcedimentoInstituicaoRequest;
use App\ModalidadeExame;

class Procedimentos_instituicao extends Controller
{

    public function index(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_procedimentos');

        return view('instituicao.procedimentos_instituicao/lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_procedimentos');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $grupos = GruposProcedimentos::all();
        $modalidades = ModalidadeExame::all();

        return view('instituicao.procedimentos_instituicao/criar', \compact('grupos', 'modalidades'));
    }


    public function store(CriarProcedimentoInstituicaoRequest $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_procedimentos');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        $usuario_logado = $request->user('instituicao');

        // Valida os dados e coloca em uma coleção
        $dados = collect($request->validated())
                ->merge([
                    'instituicoes_id' => $request->session()->get('instituicao'),
                ]);

        // Busca o procedimento e verifica se já existe o procedimento
        $procedimento = Procedimento::find($dados->get('procedimentos_id'));
        if ($procedimento->procedimentoInstituicao()->where('procedimentos_id', '=', $dados->get('procedimentos_id'))->count()) {
            return redirect()->back()->withErrors([
                'procedimentos_id' => 'Este procedimento já está cadastrado!'
            ]);
        }

        // Verifica se a modalidade está correta
        if ($procedimento->tipo == 'exame') {
            if (empty($dados->get('modalidades_exame_id'))) {
                return redirect()->back()->withErrors([
                    'procedimentos_id' => 'Procedimentos do tipo exame devem pertencer à uma modalidade'
                ]);
            }
        } else {
            // Caso não seja exame, remove a modalidade
            $dados = $dados->except(['modalidades_exame_id']);
        }

        $procedimentosInstituicao = InstituicaoProcedimentos::create($dados->toArray());

        $procedimentosInstituicao->criarLogCadastroProcedimentos(
            $usuario_logado,
            $instituicao->id
        );

        return redirect()->route('instituicao.procedimentos.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Procedimento criado com sucesso!'
        ]);
    }


    public function edit(Request $request, InstituicaoProcedimentos $procedimento)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_procedimentos');

        $modalidades = ModalidadeExame::all();
        $grupos = GruposProcedimentos::all();

        return view('instituicao.procedimentos_instituicao/editar', \compact('procedimento', 'grupos', 'modalidades'));
    }


    public function update(EditarProcedimentoInstituicaoRequest $request, InstituicaoProcedimentos $procedimento)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_procedimentos');

        $usuario_logado = $request->user('instituicao');
        $instituicaoId = $request->session()->get('instituicao');

        $dados = collect($request->validated());
        // Verifica se a modalidade está correta
        if ($procedimento->procedimento->tipo == 'exame') {
            if (empty($dados->get('modalidades_exame_id'))) {
                return redirect()->back()->withErrors([
                    'modalidades_exame_id' => 'Procedimentos do tipo exame devem pertencer à uma modalidade'
                ]);
            }
        } else {
            // Caso não seja exame, remove a modalidade
            $dados = $dados->except(['modalidades_exame_id']);
        }

        DB::transaction(function () use ($instituicaoId, $usuario_logado, $dados, $procedimento) {
            $procedimento->update($dados->toArray());

            $procedimento->criarLogEdicao(
                $usuario_logado,
                $instituicaoId
            );

            return $procedimento;
        });

        //return redirect()->route('usuarios.edit', [$usuario])->with('mensagem', 'Salvo com sucesso');
        return redirect()->route('instituicao.procedimentos.edit', [$procedimento])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Procedimento atualizado com sucesso!'
        ]);
    }

    public function getprocedimentosByGrupo(Request $request)
    {
        $termo = $request->input('nome');

        $procedimentos = Procedimento::where('descricao', 'like', "%" . $termo . "%")
            ->whereDoesnthave('procedimentoInstituicao', function ($q) use ($request, $termo) {
                $q->where('instituicoes_id', $request->session()->get('instituicao'));
            })
            ->get();
        return $procedimentos->toJson();
    }

    public function getGrupoByProcedimento(Request $request)
    {
        $termo = $request->input('nome');

        $grupos = GruposProcedimentos::where('nome', 'like', "%" . $termo . "%")
            ->whereDoesnthave('procedimentos_instituicoes', function ($q) use ($request) {
                $q->where('id', '!=', $request->input('procedimentoInstituicao'));
                $q->where('procedimentos_id', $request->input('procedimento'));
                $q->where('instituicoes_id', $request->session()->get('instituicao'));
            })
            ->get();
        return $grupos->toJson();
    }

    public function getprocedimentos(Request $request)
    {
        $termo = $request->input('q');

        $procedimentos = Procedimento::whereHas('procedimentoInstituicao', function ($q) use ($request, $termo) {
            $q->where('descricao', 'like', "%" . $termo . "%");
            $q->where('instituicoes_id', $request->session()->get('instituicao'));
        })->get();
        return $procedimentos->toJson();
    }

    public function getprocedimento(Request $request)
    {
        $id = $request->input('id');
        $procedimento = Procedimento::where('id', $id)->get()->toArray();

        echo json_encode($procedimento);
    }


    public function retiraProcedimento(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'retirar_procedimento');
        $id = $request->input('id');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        $usuario_logado = $request->user('instituicao');

        $procedimento = InstituicaoProcedimentos::where([
            ['id', '=', $id],
            ['instituicoes_id', '=', $instituicao->id],
        ])->get();

        foreach ($procedimento as $key => $value) {
            $value->delete();
            $value->criarLogExclusaoProcedimentos(
                $usuario_logado,
                $instituicao->id
            );
        }


        return response()->json($id);
    }

    public function getProcedimentoVinculoConvenio(Request $request, Convenio $convenio)
    {
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $procedimentos = $instituicao->procedimentosInstituicoes()->whereHas('instituicaoProcedimentosConvenios', function ($query) use ($convenio) {
            $query->where('convenios_id', $convenio->id);
        })->with(['procedimento', 'instituicaoProcedimentosConvenios' => function ($query) use ($convenio) {
            $query->where('convenios_id', $convenio->id);
        }])
        ->get();

        return response()->json($procedimentos);
    }
}
