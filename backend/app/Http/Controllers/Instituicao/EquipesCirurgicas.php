<?php

namespace App\Http\Controllers\Instituicao;

use App\EquipeCirurgica;
use App\Http\Controllers\Controller;
use App\Instituicao;
use App\Prestador;
use Illuminate\Http\Request;

use App\Http\Requests\EquipeCirurgica\EquipeCirurgicaRequest;
use Illuminate\Support\Facades\DB;

class EquipesCirurgicas extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_equipes_cirurgicas');

        return view('instituicao.equipes-cirurgicas.lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_equipes_cirurgicas');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        return view('instituicao.equipes-cirurgicas.criar', [
            'tipos' => EquipeCirurgica::getTipos(),
            'prestadores' => Prestador::getByInstituicao($instituicao->id)->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EquipeCirurgicaRequest $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_equipes_cirurgicas');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $dados = $request->validated();

        $equipe_cirurgica_prestadores = collect($dados['prestadores'])
            ->filter(function ($prestador) {
                return !is_null($prestador);
            })
            ->map(function($prestador) {
                return [
                    'tipo' => $prestador['tipo'],
                    'prestador_id' => $prestador['prestador_id'],
                ];
            });

        DB::transaction(function() use ($dados, $instituicao, $request, $equipe_cirurgica_prestadores) {

            $equipe_cirurgica = $instituicao->equipesCirurgicas()->create($dados);

            $equipe_cirurgica->equipeCirurgicaPrestadores()->attach($equipe_cirurgica_prestadores);
            
            $equipe_cirurgica->criarLogCadastro($request->user('instituicao'), $instituicao->id);
        });

        return redirect()->route('instituicao.centros.equipes.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Equipe Cirúrgica registrado com sucesso!'
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
    public function edit(Request $request, EquipeCirurgica $equipe_cirurgica)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_equipes_cirurgicas');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $equipe_cirurgica->instituicao_id, 403);

        return view('instituicao.equipes-cirurgicas.editar', [
            'tipos' => EquipeCirurgica::getTipos(),
            'prestadores' => Prestador::getByInstituicao($instituicao->id)->get(),
            'equipe_cirurgica' => $equipe_cirurgica
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EquipeCirurgicaRequest $request, EquipeCirurgica $equipe_cirurgica)
    {

        $this->authorize('habilidade_instituicao_sessao', 'editar_equipes_cirurgicas');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $equipe_cirurgica->instituicao_id, 403);

        $dados = $request->validated();

        $equipe_cirurgica_prestadores = collect($dados['prestadores'])
            ->filter(function ($prestador) {
                return !is_null($prestador);
            })
            ->map(function($prestador) {
                return [
                    'tipo' => $prestador['tipo'],
                    'prestador_id' => $prestador['prestador_id'],
                ];
            });

        $equipe_cirurgica = DB::transaction(function() use ($dados, $instituicao, $request,     $equipe_cirurgica, $equipe_cirurgica_prestadores) {

            $equipe_cirurgica->update($dados);

            $equipe_cirurgica->equipeCirurgicaPrestadores()->detach();

            $equipe_cirurgica->equipeCirurgicaPrestadores()->attach($equipe_cirurgica_prestadores);
            
            $equipe_cirurgica->criarLogEdicao($request->user('instituicao'), $instituicao->id);

            return $equipe_cirurgica;
        });

        return redirect()->route('instituicao.centros.equipes.edit', [$equipe_cirurgica])
        ->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Equipe Cirúrgica editada com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, EquipeCirurgica $equipe_cirurgica)
    {
        $this->authorize('habilidade_instituicao_sessao', 'excluir_equipes_cirurgicas');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $equipe_cirurgica->instituicao_id, 403);

        DB::transaction(function() use ($instituicao, $request, $equipe_cirurgica) {

            $equipe_cirurgica->equipeCirurgicaPrestadores()->detach();

            $equipe_cirurgica->delete();
            
            $equipe_cirurgica->criarLogExclusao($request->user('instituicao'), $instituicao->id);

        });

        return redirect()->route('instituicao.centros.equipes.index')
        ->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Equipe Cirúrgica excluída com sucesso!'
        ]);
    }
}
