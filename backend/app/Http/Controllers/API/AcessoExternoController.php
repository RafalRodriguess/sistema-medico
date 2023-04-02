<?php

namespace App\Http\Controllers\Api;

use App\Agendamentos;
use App\AuditoriaAgendamento;
use App\ConveniosProcedimentos;
use App\Http\Controllers\Controller;
use App\Http\Requests\AcessoExterno\AcessoExternoRequest;
use App\Http\Requests\AcessoExterno\SalvarAgendaExternaRequest;
use App\Instituicao;
use App\InstituicaoUsuario;
use App\InstituicoesAgenda;
use App\InstituicoesPrestadores;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AcessoExternoController extends Controller
{
    public function getEspecialidadesExterno(AcessoExternoRequest $request)
    {
        $dados = $request->validated();
        $instituicao = Instituicao::where('id', $dados['instituicao'])->first();
        if(!empty($instituicao) && Hash::check($dados['codigo_acesso_terceiros'], $instituicao->codigo_acesso_terceiros)){
            $especialidades = $instituicao->especialidadesInstituicao()->orderBy('descricao','ASC')->get();
            return response()->json($especialidades);
        }

        return response()->json(null);
    }

    public function getEspecialidadeUnica(AcessoExternoRequest $request)
    {
        $dados = $request->validated();
        $instituicao = Instituicao::where('id', $dados['instituicao'])->first();
        if(!empty($instituicao) && Hash::check($dados['codigo_acesso_terceiros'], $instituicao->codigo_acesso_terceiros)){
            $especialidade_id = $dados['especialidade'];
            $especialidade = $instituicao->especialidadesInstituicao()->where('id', $especialidade_id)->first();
            return response()->json($especialidade);
        }

        return response()->json(null);
    }
    
    public function getPrestadoresExterno(AcessoExternoRequest $request)
    {
        $dados = $request->validated();
        $instituicao = Instituicao::where('id', $dados['instituicao'])->first();
        if(!empty($instituicao) && Hash::check($dados['codigo_acesso_terceiros'], $instituicao->codigo_acesso_terceiros)){
            $dia_semana = $dados['dia_semana'];
            $dia_mes = $dados['dia_mes'];
            $especialidade = $dados['especialidade'];
            $data = $dados['data'];
            
            $instituicaoPrestadores = InstituicoesPrestadores::where('especialidade_id', $especialidade)->where('instituicoes_id', $instituicao->id)->with(['agenda' => function ($query) use($dia_semana, $dia_mes, $data) {
                $query->where('referente', 'prestador');
                $query->where(function($q) use($dia_semana, $dia_mes) {
                    $q->orWhere('dias_continuos', $dia_semana);
                    $q->orWhere(function($query) use($dia_mes){
                        $query->whereJsonContains("dias_unicos", ['date' => $dia_mes]);
                    });
                });
                $query->with(['agendamentos' => function ($queryAgenda) use($data) {
                    $queryAgenda->whereDate('data', $data);
                    $queryAgenda->where('status', '!=', 'cancelado');
                }]);
            }, 'prestador'])->get();
            
            return response()->json($instituicaoPrestadores);
        }

        return response()->json(null);
    }
    
    public function getPrestadorExterno(AcessoExternoRequest $request)
    {
        $dados = $request->validated();
        // dd('$dados');
        $instituicao = Instituicao::where('id', $dados['instituicao'])->first();
        if(!empty($instituicao) && Hash::check($dados['codigo_acesso_terceiros'], $instituicao->codigo_acesso_terceiros)){
            $dia_semana = $dados['dia_semana'];
            $dia_mes = $dados['dia_mes'];
            $especialidade = $dados['especialidade'];
            $data = $dados['data'];
            $prestador = $dados['prestador'];
            
            $instituicaoPrestadores = InstituicoesPrestadores::where('especialidade_id', $especialidade)->where('id', $prestador)->where('instituicoes_id', $instituicao->id)->with(['agenda' => function ($query) use($dia_semana, $dia_mes, $data) {
                $query->where('referente', 'prestador');
                $query->where(function($q) use($dia_semana, $dia_mes) {
                    $q->orWhere('dias_continuos', $dia_semana);
                    $q->orWhere(function($query) use($dia_mes){
                        $query->whereJsonContains("dias_unicos", ['date' => $dia_mes]);
                    });
                });
                $query->with(['agendamentos' => function ($queryAgenda) use($data) {
                    $queryAgenda->whereDate('data', $data);
                    $queryAgenda->where('status', '!=', 'cancelado');
                }, 'convenios' => function($query){
                    $query->where('possui_terceiros', 1);
                }]);
            }, 'prestador'])->first();

            
            return response()->json($instituicaoPrestadores);
        }

        return response()->json(null);
    }

    public function getConveniosProcedimentoPrestadorExterno(AcessoExternoRequest $request)
    {
        $dados = $request->validated();
        // dd('$dados');
        $instituicao = Instituicao::where('id', $dados['instituicao'])->first();
        $convenio = $dados['procedimento'];
        if(!empty($instituicao) && Hash::check($dados['codigo_acesso_terceiros'], $instituicao->codigo_acesso_terceiros)){
            $procedimentos = $instituicao->procedimentosInstituicoes()->whereHas('instituicaoProcedimentosConvenios', function ($query) use ($convenio) {
                $query->where('convenios_id', $convenio);
            })->with(['procedimento', 'instituicaoProcedimentosConvenios' => function ($query) use ($convenio) {
                $query->where('convenios_id', $convenio);
            }])
            ->get();

            return response()->json($procedimentos);
        }

        return response()->json(null);
    }

    public function salvarAgenda(SalvarAgendaExternaRequest $request)
    {
        $dados = $request->validated();
        $instituicao = Instituicao::where('id', $dados['instituicao'])->first();
        
        if(!empty($instituicao) && Hash::check($dados['codigo_acesso_terceiros'], $instituicao->codigo_acesso_terceiros)){
            $usuario = InstituicaoUsuario::find(1);
            //VERIFICA SE EXISTE AGENDA
            $agenda = InstituicoesAgenda::where('id', $dados['instituicoes_agenda'])->where('instituicoes_prestadores_id', $dados['instituicao_prestador_id'])->first();

            if(empty($agenda)){
                return response()->json(['tipo' => 'error', 'msg' => 'Agenda não existe']);
            }
            $data = $dados['data'];

            //PEGA A DATA FINAL DE ACORDO COM A AGENDA
            if($agenda->dias_unicos){
                $hora = date('H:i', strtotime($dados['data']));
                $data_pesquisa = date('d/m/Y', strtotime($dados['data']));

                foreach (json_decode($agenda->dias_unicos) as $valueJson) {
                    if (isset($valueJson->convenio_id_unico) & $valueJson->date == date('d/m/Y', strtotime($data_pesquisa)) & $valueJson->hora_inicio <= $hora & $valueJson->hora_fim >= $hora) {
                        $duracao = explode(':', $valueJson->duracao_atendimento);
                        $duracao_tempo = $duracao[0]*60+$duracao[1];
                        $data_final = date('Y-m-d H:i', strtotime(" +".$duracao_tempo." minutes", strtotime($dados['data'])));
                    }
                }

            }else{
                $duracao = explode(':', $agenda->duracao_atendimento);
                $duracao_tempo = $duracao[0]*60+$duracao[1];
                $data_final = date('Y-m-d H:i', strtotime(" +".$duracao_tempo." minutes", strtotime($dados['data'])));
            }
            
            //VERIFICA SE EXISTE PACIENTE
            if(array_key_exists('id_externo', $dados)){
                //PEGA PACIENTE EXISTENTE
                $paciente = $instituicao->pessoa()->where('id', $dados['id_externo'])->first();
            }else{
                //CRIA NOVO PACIENTE
                $dadosPaciente = [
                    'nome' => $dados['paciente'],
                    'telefone1' => $dados['telefone'],
                    'nascimento' => $dados['data_nascimento'],
                    'tipo' => 2,
                    'personalidade' => 1,
                ];

                $paciente = $instituicao->pessoa()->create($dadosPaciente);

                $paciente->criarLog($usuario, 'Novo paciente via acesso externo', $data, $instituicao->id);
            }

            

            if(empty($paciente)){
                return response()->json(['tipo' => 'error', 'msg' => 'Paciente não existe']);
            }
            
            //VERIFICA CONVENIO PROCEDIMENTO E PEGA VALOR
            $procedimentoInstituicaoConvenio = ConveniosProcedimentos::where('id', $dados['procedimentos_instituicoes_convenios'])->where('convenios_id', $dados['convenio_id'])->first();

            if(empty($procedimentoInstituicaoConvenio)){
                return response()->json(['tipo' => 'error', 'msg' => 'Convenio não existe']);
            }
            

            $agendamento_id = DB::transaction(function() use($usuario, $dados, $procedimentoInstituicaoConvenio, $agenda, $data_final, $paciente, $instituicao){
                $agendamento_paciente = array(
                    'tipo' => 'agendamento',
                    'data' => $dados['data'],
                    'data_final' => $data_final,
                    'status' => 'pendente',
                    'valor_total' => $procedimentoInstituicaoConvenio->valor,
                    'instituicoes_agenda_id' => $agenda->id,
                    'pessoa_id' => $paciente->id, //criar paciente_id == pessoas
                    'forma_pagamento' => 'dinheiro',
                    'acompanhante' => 0,
                    'acompanhante_relacao' => null,
                    'acompanhante_nome' => null,
                    'acompanhante_telefone' => null,
                    'cpf_acompanhante' => null,
                    'obs' => '',
                    'carteirinha_id' => null,
                    'compromisso_id' => null,
                    'tipo_agenda' => 'normal',
                    'teleatendimento' => null,
                );

                $agendamento = Agendamentos::create($agendamento_paciente);

                $agendamento->criarLogCadastro($usuario, $instituicao->id);
                AuditoriaAgendamento::logAgendamento($agendamento->id, $agendamento_paciente['status'], '1', 'salvarProcedimentoPaciente', 'Paciente adicionado externo');
                
                return $agendamento->id;
            });

            return response()->json([
                'tipo' => 'success', 
                'msg' => 'Agendamento criado com sucesso',
                'paciente_id' => $paciente->id,
                'agenda_externa' => $agendamento_id,
                'valor' => $procedimentoInstituicaoConvenio->valor,
            ]);

        }else{
            return response()->json([
                'tipo' => 'error', 
                'msg' => 'Sem acesso ao servidor',
            ]);
        }
    }
}
