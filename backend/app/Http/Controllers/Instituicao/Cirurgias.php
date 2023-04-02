<?php

namespace App\Http\Controllers\Instituicao;

use App\Cirurgia;
use App\Especialidade;
use App\Http\Controllers\Controller;
use App\Http\Requests\Cirurgias\CriarCirurgiasRequest;
use App\Instituicao;
use App\SalaCirurgica;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Cirurgias extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_cirurgias');
        return view('instituicao.cirurgias.lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_cirurgias');
        $instituicao = Instituicao::find($request->session()->get("instituicao"));
        
        $partos = $instituicao->tipoPartos()->get();
        $gruposCirurgias = $instituicao->gruposCirurgias()->get();
        $tipoAnestesias = $instituicao->tipoAnestesia()->get();
        $viasAcesso = $instituicao->viasAcesso()->get();
        // $procedimentos = $instituicao->procedimentos()->get();
        $equipamentos = $instituicao->equipamentos()->get();
        $especialidades = $instituicao->especialidadesInstituicao()->orderBy('descricao','ASC')->get();;
        $equipes = $instituicao->equipesCirurgicas()->get();
        $salas = SalaCirurgica::whereHas('centroCirurgico', function($q) use($instituicao){
            $q->where('instituicao_id', $instituicao->id);
        })->get();
        $convenios = $instituicao->convenios()->get();
        
        // $proc_conveinos = $instituicao->procedimentos()->with('procedimentoConvenioInstuicao')->get();


        // dd($equipes->toArray());

        return view('instituicao.cirurgias.criar',\compact('partos', 'gruposCirurgias', 'tipoAnestesias','viasAcesso', 'convenios', 'equipamentos', 'especialidades', 'equipes', 'salas'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CriarCirurgiasRequest $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_cirurgias');

        $instituicao = Instituicao::find($request->session()->get("instituicao"));
        $dados = $request->validated();
        $dados['obstetricia'] = (!empty($dados['obstetricia'])) ? $dados['obstetricia'] : 0;
        
        unset($dados['equipamentos'], $dados['especialidades'], $dados['salas'], $dados['equipes']);

        $equipamentos = collect($request->validated()['equipamentos'])
            ->filter(function ($equipamentos) {
                return !is_null($equipamentos['equipamento_id']);
            })
            ->map(function($equipamentos){
                return [
                    'equipamento_id' => $equipamentos['equipamento_id'],
                    'quantidade' => $equipamentos['quantidade']
                ];
            });
        
        $especialidades = collect($request->validated()['especialidades'])
            ->filter(function ($especialidades) {
                return !is_null($especialidades['especialidade_id']);
            })
            ->map(function($especialidades){
                return [
                    'especialidade_id' => $especialidades['especialidade_id'],
                ];
            });

        $salas = collect($request->validated()['salas'])
            ->filter(function ($salas) {
                return !is_null($salas['sala_id']);
            })
            ->map(function($salas){
                return [
                    'sala_id' => $salas['sala_id'],
                ];
            });

        $equipes = collect($request->validated()['equipes'])
            ->filter(function ($equipes) {
                return !is_null($equipes['equipe_id']);
            })
            ->map(function($equipes){
                return [
                    'equipe_id' => $equipes['equipe_id'],
                ];
            });

        DB::transaction(function() use ($request, $instituicao, $dados, $equipamentos, $equipes, $salas, $especialidades){
            $usuario_logado = $request->user('instituicao');
            
            $cirurgias = $instituicao->cirurgias()->create($dados);
            if(!empty($equipamentos)){
                $cirurgias->cirurgiasEquipamentos()->attach($equipamentos);
            }

            if(!empty($equipes)){
                $cirurgias->cirurgiasEquipes()->attach($equipes);
            }

            if(!empty($salas)){
                $cirurgias->cirurgiasSalas()->attach($salas);
            }

            if(!empty($especialidades)){
                $cirurgias->cirurgiasEspecialidades()->attach($especialidades);
            }

            $cirurgias->criarLogCadastro($usuario_logado, $instituicao->id);
        });

        return redirect()->route('instituicao.cirurgias.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'cirurgia criada com sucesso!'
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
    public function edit(Request $request, Cirurgia $cirurgia)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_cirurgias');
        $instituicao = Instituicao::find($request->session()->get("instituicao"));
        
        $partos = $instituicao->tipoPartos()->get();
        $gruposCirurgias = $instituicao->gruposCirurgias()->get();
        $tipoAnestesias = $instituicao->tipoAnestesia()->get();
        $viasAcesso = $instituicao->viasAcesso()->get();
        // $procedimentos = $instituicao->procedimentos()->get();
        $equipamentos = $instituicao->equipamentos()->get();
        $especialidades = $instituicao->especialidadesInstituicao()->orderBy('descricao','ASC')->get();
        $equipes = $instituicao->equipesCirurgicas()->get();
        $salas = SalaCirurgica::whereHas('centroCirurgico', function($q) use($instituicao){
            $q->where('instituicao_id', $instituicao->id);
        })->get();

        $convenios = $instituicao->convenios()->get();
        // $cirurgia->load('cirurgiasEquipamentos');
        // dd($cirurgia->toArray());

        return view('instituicao.cirurgias.editar',\compact('cirurgia', 'partos', 'gruposCirurgias', 'tipoAnestesias','viasAcesso', 'convenios', 'equipamentos', 'especialidades', 'equipes', 'salas'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CriarCirurgiasRequest $request, Cirurgia  $cirurgia)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_cirurgias');

        $instituicao = $request->session()->get("instituicao");
        abort_unless($instituicao===$cirurgia->instituicao_id, 403);

        $dados = $request->validated();
        $dados['obstetricia'] = (!empty($dados['obstetricia'])) ? $dados['obstetricia'] : 0;
        
        unset($dados['equipamentos'], $dados['especialidades'], $dados['salas'], $dados['equipes']);

        $equipamentos = collect($request->validated()['equipamentos'])
            ->filter(function ($equipamentos) {
                return !is_null($equipamentos['equipamento_id']);
            })
            ->map(function($equipamentos){
                return [
                    'equipamento_id' => $equipamentos['equipamento_id'],
                    'quantidade' => $equipamentos['quantidade']
                ];
            });
        
        $especialidades = collect($request->validated()['especialidades'])
            ->filter(function ($especialidades) {
                return !is_null($especialidades['especialidade_id']);
            })
            ->map(function($especialidades){
                return [
                    'especialidade_id' => $especialidades['especialidade_id'],
                ];
            });

        $salas = collect($request->validated()['salas'])
            ->filter(function ($salas) {
                return !is_null($salas['sala_id']);
            })
            ->map(function($salas){
                return [
                    'sala_id' => $salas['sala_id'],
                ];
            });

        $equipes = collect($request->validated()['equipes'])
            ->filter(function ($equipes) {
                return !is_null($equipes['equipe_id']);
            })
            ->map(function($equipes){
                return [
                    'equipe_id' => $equipes['equipe_id'],
                ];
            });
        DB::transaction(function() use ($request, $instituicao, $cirurgia, $dados, $equipamentos, $especialidades, $equipes, $salas){
            $usuario_logado = $request->user('instituicao');
            
            $cirurgia->update($dados);

            $cirurgia->cirurgiasEquipamentos()->detach();
            $cirurgia->cirurgiasEquipes()->detach();
            $cirurgia->cirurgiasSalas()->detach();
            $cirurgia->cirurgiasEspecialidades()->detach();

            if(!empty($equipamentos)){
                $cirurgia->cirurgiasEquipamentos()->attach($equipamentos);
            }

            if(!empty($equipes)){
                $cirurgia->cirurgiasEquipes()->attach($equipes);
            }

            if(!empty($salas)){
                $cirurgia->cirurgiasSalas()->attach($salas);
            }

            if(!empty($especialidades)){
                $cirurgia->cirurgiasEspecialidades()->attach($especialidades);
            }

            $cirurgia->criarLogEdicao($usuario_logado, $instituicao);
        });

        return redirect()->route('instituicao.cirurgias.index', [$cirurgia])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Cirurgia alterada com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Cirurgia $cirurgia)
    {
        $this->authorize('habilidade_instituicao_sessao', 'excluir_cirurgias');
        
        $instituicao = $request->session()->get("instituicao");
        abort_unless($instituicao===$cirurgia->instituicao_id, 403);
        DB::transaction(function () use ($cirurgia, $request, $instituicao) {
            $usuario_logado = $request->user('instituicao');
            $cirurgia->cirurgiasEquipamentos()->detach();
            $cirurgia->cirurgiasEquipes()->detach();
            $cirurgia->cirurgiasSalas()->detach();
            $cirurgia->cirurgiasEspecialidades()->detach();

            $cirurgia->delete();
            $cirurgia->criarLogExclusao($usuario_logado, $instituicao);
           
            return $cirurgia;
        });
        
        return redirect()->route('instituicao.cirurgias.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Cirurgia excluídos com sucesso!'
        ]);
    }
}
