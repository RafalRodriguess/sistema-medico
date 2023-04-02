<?php

namespace App\Http\Controllers\Instituicao;

use App\Http\Controllers\Controller;
use App\Instituicao;
use App\Agendamentos;
use App\Avaliacao;
use App\Http\Requests\Internacao\AvaliacaoRequest;
use App\Pessoa;
use App\Prestador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AvaliacaoInternacao extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Agendamentos $agendamento, Pessoa $paciente)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_avaliacao');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $paciente->instituicao_id, 403);
        abort_unless($agendamento->pessoa_id === $paciente->id, 403);

        $user = $request->user('instituicao');
        $medicos = Prestador::whereHas('prestadoresInstituicoes', function($q) use ($instituicao){
            $q->where('ativo', 1);
            $q->where('instituicoes_id',$instituicao->id);
        })->get();

        $avaliacoes = $paciente->avaliacoes($user->id)->orderBy('created_at', 'DESC')->get();
        
        $especialidades = $instituicao->especialidadesInstituicao()->get();

        
        return view('instituicao.prontuarios.avaliacoes.info', \compact('medicos', 'especialidades', 'avaliacoes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AvaliacaoRequest $request, Agendamentos $agendamento, Pessoa $paciente)
    {
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $paciente->instituicao_id, 403);
        abort_unless($agendamento->pessoa_id === $paciente->id, 403);

        $user = $request->user('instituicao');

        $avaliacao = DB::transaction(function() use($instituicao, $user, $agendamento, $paciente, $request){
            $data = $request->validated();

            $data['agendamento_id'] = $agendamento->id;
            $data['usuario_id'] = $user->id;
            $data['paciente_id'] = $paciente->id;
            
            if(array_key_exists('avaliacao_id', $data) && $data['avaliacao_id'] != null){
                $avaliacao = Avaliacao::find($data['avaliacao_id']);
                $avaliacao->update(['avaliacao' => $data['avaliacao']]);
                $avaliacao->criarLogEdicao($user, $instituicao->id);
            }else{
                $avaliacao = $paciente->avaliacao()->create($data);
                $avaliacao->criarLogCadastro($user, $instituicao->id);
            }


            return $avaliacao;
        });

        // $laudo = $paciente->laudo($user->id)->orderBy('created_at', 'DESC')->get();

        return response()->json($avaliacao);
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
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function deleteAvaliacao(Request $request, Agendamentos $agendamento, Pessoa $paciente, Avaliacao $avaliacao)
    {

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $paciente->instituicao_id, 403);
        abort_unless($agendamento->pessoa_id === $paciente->id, 403);
        abort_unless($avaliacao->paciente_id === $paciente->id, 403);

        $user = $request->user('instituicao');

        DB::transaction(function() use($instituicao, $user, $agendamento, $paciente, $avaliacao){
            $avaliacao->delete();
            $avaliacao->criarLogExclusao($user,$instituicao->id);
        });

        // $avaliacoes = $paciente->avaliacoes($user->id)->orderBy('created_at', 'DESC')->get();

        // $avaliacao = null;
        
        return response()->json(true);
    }

    public function avaliacaoHistorico(Request $request, Agendamentos $agendamento, Pessoa $paciente)
    {

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $paciente->instituicao_id, 403);
        abort_unless($agendamento->pessoa_id === $paciente->id, 403);

        $user = $request->user('instituicao');
        $avaliacoes = $paciente->avaliacoes($user->id)->orderBy('created_at', 'DESC')->get();

        return view('instituicao.prontuarios.avaliacoes.historico', \compact('avaliacoes'));
    }

    public function getAvaliacao(Request $request, Agendamentos $agendamento, Pessoa $paciente, Avaliacao $avaliacao)
    {

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $paciente->instituicao_id, 403);
        abort_unless($agendamento->pessoa_id === $paciente->id, 403);

        return response()->json($avaliacao);
    }

    public function imprimirAvaliacao(Request $request, Avaliacao $avaliacao){
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $agendamento = $avaliacao->agendamento()->first();

        abort_unless($avaliacao->paciente->instituicao_id === $instituicao->id, 403);

        $user = $request->user('instituicao');
        
        return view('instituicao.prontuarios.avaliacoes.imprimir', \compact('avaliacao', 'agendamento'));
    }

}
