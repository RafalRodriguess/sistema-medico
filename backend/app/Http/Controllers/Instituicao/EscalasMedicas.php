<?php

namespace App\Http\Controllers\Instituicao;

use App\ {
    EscalaMedica,
    EscalasPrestadores,
    Especialidade,
    Prestador
};
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\EscalasMedicas\{
    CreateEscalaMedica,
};
use App\Instituicao;
use Illuminate\Support\Facades\DB;



class EscalasMedicas extends Controller
{



    /**
     * Lista todas as escalas médicas registradas para uma determinada instituicao
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_escalas_medicas');

        return view('instituicao.escalas-medicas.lista');
    }

    public function duplicarEscalasMedicas(CreateEscalaMedica $request, EscalaMedica $escala_medica){


        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $escala_medica->get()->toArray()[0]['instituicao_id'], 403);

        $dados = $request->validated();

        $escalasPrestadoresInsert = EscalasPrestadores::where('escala_medica_id',$request->id);

        $escala_medica = DB::transaction(function() use (
            $dados, $instituicao, $request, $escala_medica, $escalasPrestadoresInsert) {

             $escala_medica = $instituicao->escalasMedicas()->create($dados);
             $dadosPrestadores = new EscalasPrestadores();

            foreach($escalasPrestadoresInsert->get() as $prestador)
            {

                $dadosPrestadores->entrada = $prestador->toArray()['entrada'];
                $dadosPrestadores->saida = $prestador->toArray()['saida'];
                $dadosPrestadores->observacao = $prestador->toArray()['observacao'];
                $dadosPrestadores->prestador_id = $prestador->toArray()['prestador_id'];
                $dadosPrestadores->escala_medica_id = $escala_medica->id;
                $escalasPrestadoresInsert->create($dadosPrestadores->toArray());
            }

             return $escala_medica;
        });

        return redirect()->route('instituicao.escalas-medicas.index')
            ->with('mensagem', [
                'icon' => 'success',
                'title' => 'Sucesso.',
                'text' => 'Escala Médica duplicada com sucesso!'
            ]);

    }

    /**
     * Mostra o formulário de cadastro de escala médica
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_escalas_medicas');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        return view('instituicao.escalas-medicas.criar', [
            'especialidades' => Especialidade::all(),
            'origens' => $instituicao->origens()->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateEscalaMedica $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_escalas_medicas');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $dados = $request->validated();

        $prestadores = collect($dados['prestadores'])
            ->filter(function ($prestador) {
                return !is_null($prestador);
            })
            ->map(function($prestador) {
                return [
                    'prestador_id' => $prestador['prestador_id'],
                    'entrada' => $prestador['entrada'],
                    'saida' => $prestador['saida'],
                    'observacao' => $prestador['observacao'],
                ];
            });

        DB::transaction(function() use ($dados, $instituicao, $request, $prestadores) {

            $escala_medica = $instituicao->escalasMedicas()->create($dados);

            $escala_medica->escalaPrestadores()->attach($prestadores);

            $escala_medica->criarLogCadastro($request->user('instituicao'), $instituicao->id);
        });

        return redirect()->route('instituicao.escalas-medicas.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Escala Médica registrado com sucesso!'
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, EscalaMedica $escala_medica)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_escalas_medicas');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $escala_medica->instituicao_id, 403);
        return view('instituicao.escalas-medicas.editar', [
            'especialidades' => Especialidade::all(),
            'origens' => $instituicao->origens()->get(),
            'escala_medica' => $escala_medica,
            'instituicao' => $instituicao
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CreateEscalaMedica $request, EscalaMedica $escala_medica)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_escalas_medicas');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $escala_medica->instituicao_id, 403);

        $dados = $request->validated();

        $prestadores = collect($dados['prestadores'] ?? [])
            ->filter(function ($prestador) {
                return !is_null($prestador);
            })
            ->map(function($prestador) {
                return [
                    'prestador_id' => $prestador['prestador_id'],
                    'entrada' => $prestador['entrada'],
                    'saida' => $prestador['saida'],
                    'observacao' => $prestador['observacao'],
                ];
            });

        $escala_medica = DB::transaction(function() use (
            $dados, $instituicao, $request, $escala_medica, $prestadores) {

            $escala_medica->update($dados);

            $escala_medica->escalaPrestadores()->detach();

            $escala_medica->escalaPrestadores()->attach($prestadores);

            $escala_medica->criarLogEdicao($request->user('instituicao'), $instituicao->id);

            return $escala_medica;
        });

        return redirect()->route('instituicao.escalas-medicas.edit', [$escala_medica])
            ->with('mensagem', [
                'icon' => 'success',
                'title' => 'Sucesso.',
                'text' => 'Escala Médica editada com sucesso!'
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, EscalaMedica $escala_medica)
    {
        $this->authorize('habilidade_instituicao_sessao', 'excluir_escalas_medicas');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $escala_medica->instituicao_id, 403);

        DB::transaction(function() use ($instituicao, $request, $escala_medica) {

            $escala_medica->escalaPrestadores()->detach();

            $escala_medica->delete();

            $escala_medica->criarLogExclusao($request->user('instituicao'), $instituicao->id);

        });

        return redirect()->route('instituicao.escalas-medicas.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Escala Médica excluída com sucesso!'
        ]);
    }

}
