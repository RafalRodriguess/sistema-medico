<?php

namespace App\Http\Controllers\Instituicao;

use App\AgendaAusente;
use App\Http\Controllers\Controller;
use App\Prestador;
use App\Instituicao;
use App\Procedimento;
use App\InstituicoesPrestadores;
use App\InstituicaoProcedimentos;
use App\GruposProcedimentos;
use App\GruposInstituicoes;
use App\InstituicoesAgenda;
use Illuminate\Http\Request;
use App\Convenio;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\InstituicaoAgenda\EditarInstituicaoAgendaRequest;

class Instituicoes_agenda extends Controller
{
    public function editAgendaProcedimento(Request $request, InstituicaoProcedimentos $InstituicaoProcedimentos){

        $this->authorize('habilidade_instituicao_sessao', 'editar_agenda_procedimento');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));



        $InstituicaoProcedimentos = InstituicaoProcedimentos::where('instituicoes_id',$instituicao->id )
        ->where('id',$InstituicaoProcedimentos->id)
        ->with(['agenda'])
        ->first();
        $procedimento = $InstituicaoProcedimentos->procedimento;
        return view('instituicao.procedimentos_instituicao/agenda', \compact('InstituicaoProcedimentos','procedimento'));
    }

    public function editAgendaPrestador(Request $request, Prestador $prestador){

        $this->authorize('habilidade_instituicao_sessao', 'editar_agenda_prestador');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $prestador = $prestador->load([
            'especialidadeInstituicao' => function($q) use ($instituicao) {
                $q->whereNotNull('especialidade_id');
                $q->where('instituicoes_id',$instituicao->id)
                ->with('especialidade','agenda', 'agenda.convenios');
            }
        ]);

        $setores = $instituicao->setoresExame()->where('ativo', 1)->get();
        $convenios = $instituicao->convenios()->where('ativo', 1)->get();
        return view('instituicao.prestadores/agenda', \compact('prestador', 'setores','convenios'));

    }

    public function updateAgendaPrestador(EditarInstituicaoAgendaRequest $request, Prestador $prestador){

        $this->authorize('habilidade_instituicao_sessao', 'editar_agenda_prestador');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        
        $continue = (!empty($request->input('continue'))) ? true: false;
        
        $agendamento = DB::transaction(function () use ($prestador, $request, $instituicao, $continue){
            $inclusos = [];
            $inclusosId = [];

            foreach ($prestador->especialidadeInstituicao()->where('instituicoes_id',$instituicao->id)->get()->pluck('especialidade_id') as  $value) {
                $inclusos[$value]=[];
                $inclusosId[$value]=[];
            }

            $usuario_logado = $request->user('instituicao');
            $dadosLogAgenda = null;

            if($request->input('checkbox')){

                foreach ($request->input('checkbox') as $key => $valueCheckbox) {
                    
                    foreach ($valueCheckbox as $dia_semana => $agendas) {

                        foreach ($agendas as $especialidade) {

                            array_push($inclusos[$especialidade], $dia_semana);
                            $InstituicoesPrestadores = $prestador->especialidadeInstituicao()->where('instituicoes_id',$instituicao->id)->where('especialidade_id',$especialidade)->first();
                            if(count($inclusosId[$especialidade]) > 0){

                                $agenda = $InstituicoesPrestadores->agenda()->where('dias_continuos',$dia_semana)->whereNotIn('id', $inclusosId[$especialidade])->first();

                            }else{
                                $agenda = $InstituicoesPrestadores->agenda()->where('dias_continuos',$dia_semana)->first();
                            }

                            $dados = [
                                'hora_inicio' => $request->input('inicio')[$key][$dia_semana][$especialidade],
                                'hora_fim' => $request->input('termino')[$key][$dia_semana][$especialidade],
                                'hora_intervalo' => $request->input('intervalo')[$key][$dia_semana][$especialidade],
                                'duracao_intervalo' => $request->input('duracao')[$key][$dia_semana][$especialidade],
                                'duracao_atendimento' => $request->input('atendimento')[$key][$dia_semana][$especialidade],
                                'setor_id' => $request->input('setor_id_agenda')[$key][$dia_semana][$especialidade],
                                'faixa_etaria' => $request->input('faixa_etaria_agenda')[$key][$dia_semana][$especialidade],
                                'obs' => $request->input('obs')[$key][$dia_semana][$especialidade],
                            ];
                            
                            if(!empty($request->input('convenio_id')[$key])){
                                
                                if(count($request->input('convenio_id')[$key]) != count($request->input('checkbox')[$key])){
                                    return response()->json([
                                        'icon' => 'error',
                                        'title' => 'Erro.',
                                        'text' => 'Gentileza selecionar convenios para todos os dias!'
                                    ]);
                                }else{
                                $dataConvenio = collect($request->input('convenio_id')[$key][$dia_semana][$especialidade])
                                    ->filter(function ($convenio){
                                        return !is_null($convenio);
                                    })
                                    ->map(function ($convenio){
                                        return [
                                            'convenio_id' => $convenio,
                                        ];
                                    });
                                }
                            }else{
                                return response()->json([
                                    'icon' => 'error',
                                    'title' => 'Erro.',
                                    'text' => 'Gentileza selecionar convenios para todos os dias!'
                                ]);
                            }

                            if($agenda){
                                array_push($inclusosId[$especialidade], $agenda->id);
                                $agenda->fill($dados);
                                $agenda->update();
                            }else{
                                $agenda = new InstituicoesAgenda;
                                $agenda->referente='prestador';
                                $agenda->tipo='continuo';
                                $agenda->dias_continuos=$dia_semana;
                                $agenda->fill($dados);
                                $agenda->instituicoes_prestadores_id = $InstituicoesPrestadores->id;
                                $agenda->save();
                                array_push($inclusosId[$especialidade], $agenda->id);
                            }

                            $agenda->convenios()->detach();

                            $agenda->convenios()->attach($dataConvenio);

                            // $dados['convenio'] = $dataConvenio;
                            $dadosLogAgenda[$key][$dia_semana] = $dados;
                            $dadosLogAgenda[$key][$dia_semana]['convenio'] = $dataConvenio;
                            
                        }
                    }
                }
            }
            if($request->input('unicos')){

                foreach ($request->input('unicos') as $especialidade => $json) {

                    $InstituicoesPrestadores = $prestador->especialidadeInstituicao()->where('instituicoes_id',$instituicao->id)->where('especialidade_id',$especialidade)->first();
                    if($InstituicoesPrestadores){
                        $agenda = $InstituicoesPrestadores->agenda()->whereNotNull('dias_unicos')->first();
                        
                        if($agenda){
                            $agenda->dias_unicos=$json;
                            $agenda->update();
                            $agenda->criarLogEdicao($usuario_logado, $instituicao->id);
                        }else{
                            $agenda = new InstituicoesAgenda;
                            $agenda->referente='prestador';
                            $agenda->tipo='unico';
                            $agenda->dias_unicos=$json;
                            $agenda->instituicoes_prestadores_id = $InstituicoesPrestadores->id;
                            $agenda->save();
                            $agenda->criarLogCadastro($usuario_logado, $instituicao->id);
                        }
                        $dadosLogAgenda[]['unicos'] = $json;
                    }
                }
            }

            // $dados = $request->validated();
            $prestador->criarLog($usuario_logado, "Alteração agenda prestador", $dadosLogAgenda, $instituicao->id);
            
            // foreach ($inclusos as $key => $value) {
            foreach ($inclusosId as $key => $value) {
                InstituicoesAgenda::whereHas('prestadores',function($q) use ($instituicao, $prestador, $key){
                    $q->where('instituicoes_id',$instituicao->id)
                    ->where('prestadores_id',$prestador->id)
                    ->where('especialidade_id',$key);
                })
                // ->whereNotIn('dias_continuos',$value)
                ->whereNotIn('id',$value)
                ->where('tipo','continuo')
                ->delete();
            }

            if($continue){
                return response()->json([
                    'icon' => 'success',
                    'title' => 'Sucesso.',
                    'text' => 'Agenda atualizada com sucesso!',
                    'continue' => true,
                    'route' => route('instituicao.convenios.index')
                ]);
            }else{
               
                return response()->json([
                    'icon' => 'success',
                    'title' => 'Sucesso.',
                    'text' => 'Agenda atualizada com sucesso!'
                ]);
            }
        });

       return $agendamento;

    }

    public function updateAgendaProcedimento(EditarInstituicaoAgendaRequest $request, InstituicaoProcedimentos $InstituicaoProcedimentos){

        $this->authorize('habilidade_instituicao_sessao', 'editar_agenda_procedimento');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));


        DB::transaction(function () use ($InstituicaoProcedimentos, $request, $instituicao){
            $inclusos = [];
            if($request->input('checkbox')){
                foreach ($request->input('checkbox') as $dia_semana => $agendas) {
                        array_push($inclusos, $dia_semana);
                        $InstituicaoProcedimentos = InstituicaoProcedimentos::where('instituicoes_id',$instituicao->id )->where('id',$InstituicaoProcedimentos->id)->first();
                        $agenda = $InstituicaoProcedimentos->agenda()->where('dias_continuos',$dia_semana)->first();

                        $dados = [
                            'hora_inicio' => $request->input('inicio')[$dia_semana],
                            'hora_fim' => $request->input('termino')[$dia_semana],
                            'hora_intervalo' => $request->input('intervalo')[$dia_semana],
                            'duracao_intervalo' => $request->input('duracao')[$dia_semana],
                            'duracao_atendimento' => $request->input('atendimento')[$dia_semana]
                        ];

                        if($agenda){
                            $agenda->fill($dados);
                            $agenda->update();
                        }else{
                            $agenda = new InstituicoesAgenda;
                            $agenda->referente='procedimento';
                            $agenda->tipo='continuo';
                            $agenda->dias_continuos=$dia_semana;
                            $agenda->fill($dados);
                            $agenda->procedimentos_instituicoes_id = $InstituicaoProcedimentos->id;
                            $agenda->save();
                        }

                }
            }
            if($request->input('unicos')){

                    $InstituicaoProcedimentos = InstituicaoProcedimentos::where('instituicoes_id',$instituicao->id )->where('id',$InstituicaoProcedimentos->id)->first();

                    $agenda = $InstituicaoProcedimentos->agenda()->whereNotNull('dias_unicos')->first();

                    if($agenda){
                        $agenda->dias_unicos=$request->input('unicos');
                        $agenda->update();
                    }else{
                        $agenda = new InstituicoesAgenda;
                        $agenda->referente='procedimento';
                        $agenda->tipo='unico';
                        $agenda->dias_unicos=$request->input('unicos');
                        $agenda->procedimentos_instituicoes_id = $InstituicaoProcedimentos->id;
                        $agenda->save();
                    }

            }


            InstituicoesAgenda::whereHas('procedimentos',function($q) use ($InstituicaoProcedimentos, $inclusos){
                $q->where('id',$InstituicaoProcedimentos->id);
            })
            ->whereNotIn('dias_continuos',$inclusos)
            ->where('tipo','continuo')
            ->delete();



        });

        return response()->json([
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Agenda atualizada com sucesso!'
        ]);

    }

    public function editAgendaGrupo(Request $request, GruposProcedimentos $grupo){

        $this->authorize('habilidade_instituicao_sessao', 'editar_agenda_grupo');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $grupo_instituicao = GruposInstituicoes::where('grupo_id',$grupo->id)->where('instituicao_id',$instituicao->id)->first();

        if(!$grupo_instituicao){
            $grupo_instituicao = new GruposInstituicoes;
            $grupo_instituicao->instituicao_id = $instituicao->id;
            $grupo_instituicao->grupo_id = $grupo->id;
            $grupo_instituicao->save();
        }

        return view('instituicao.procedimentos_instituicao/agenda_grupo', \compact('grupo_instituicao','instituicao'));

    }

    public function updateAgendaGrupo(EditarInstituicaoAgendaRequest $request, GruposInstituicoes $grupo){

        $this->authorize('habilidade_instituicao_sessao', 'editar_agenda_procedimento');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));


        DB::transaction(function () use ($grupo, $request, $instituicao){
            $inclusos = [];
            if($request->input('checkbox')){
                foreach ($request->input('checkbox') as $dia_semana => $agendas) {
                        array_push($inclusos, $dia_semana);
                        $agenda = $grupo->agenda()->where('dias_continuos',$dia_semana)->first();

                        $dados = [
                            'hora_inicio' => $request->input('inicio')[$dia_semana],
                            'hora_fim' => $request->input('termino')[$dia_semana],
                            'hora_intervalo' => $request->input('intervalo')[$dia_semana],
                            'duracao_intervalo' => $request->input('duracao')[$dia_semana],
                            'duracao_atendimento' => $request->input('atendimento')[$dia_semana]
                        ];

                        if($agenda){
                            $agenda->fill($dados);
                            $agenda->update();
                        }else{
                            $agenda = new InstituicoesAgenda;
                            $agenda->referente='grupo';
                            $agenda->tipo='continuo';
                            $agenda->dias_continuos=$dia_semana;
                            $agenda->fill($dados);
                            $agenda->grupos_instituicoes_id = $grupo->id;
                            $agenda->save();
                        }

                }
            }
            if($request->input('unicos')){

                    $agenda = $grupo->agenda()->whereNotNull('dias_unicos')->first();

                    if($agenda){
                        $agenda->dias_unicos=$request->input('unicos');
                        $agenda->update();
                    }else{
                        $agenda = new InstituicoesAgenda;
                        $agenda->referente='grupo';
                        $agenda->tipo='unico';
                        $agenda->dias_unicos=$request->input('unicos');
                        $agenda->fill($dados);
                        $agenda->grupos_instituicoes_id = $grupo->id;
                        $agenda->save();
                    }

            }

            InstituicoesAgenda::
            where('grupos_instituicoes_id',$grupo->id)
            ->whereNotIn('dias_continuos',$inclusos)
            ->where('tipo','continuo')
            ->delete();




        });
        return response()->json([
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Agenda atualizada com sucesso!'
        ]);

    }

}
