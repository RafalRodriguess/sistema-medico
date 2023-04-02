<?php

namespace App\Http\Controllers\Instituicao;

use App\AgendamentoAtendimento;
use App\AgendamentoAtendimentoUrgencia;
use App\Carteirinha;
use App\ChamadaTotem;
use App\Especialidade;
use App\Http\Controllers\Controller;
use App\Http\Requests\AtendimentosUrgencia\FinalizarAtendimentoRequest;
use App\Http\Requests\AtendimentosUrgencia\IniciarAtendimentoRequest;
use App\Instituicao;
use App\InstPrestEspecialidade;
use App\Pessoa;
use App\SenhaTriagem;
use App\Usuario;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AtendimentosUrgencia extends Controller
{
    public function index()
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_atendimentos_urgencia');

        return view('instituicao.atendimentos-urgencia/lista');
    }

    public function chamarPaciente(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'chamar_pacientes_atendimentos_urgencia');
        try {
            $usuario = $request->user('instituicao');
            $instituicao = Instituicao::findOrFail($request->session()->get('instituicao'));
            $paciente = SenhaTriagem::findOrFail($request->get('id'));
            $totem = $paciente->totem()->first();

            abort_if(empty($totem) || $totem->instituicoes_id != $instituicao->id, 403);

            ChamadaTotem::chamarSenha($paciente, 'consultorio');
            $paciente->criarLogEdicao($usuario);

            return response()->json([
                'icon' => 'success',
                'title' => 'Sucesso',
                'text' => 'Paciente chamado!'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'icon' => 'error',
                'title' => 'Erro',
                'text' => 'Houve um erro ao tentar chamar o paciente!'
            ]);
        }
    }

    public function modalAtendimento(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'iniciar_atendimentos_urgencia');
        $instituicao = Instituicao::findOrFail($request->session()->get('instituicao'));
        $senha = SenhaTriagem::whereHas('totem', function ($builder) use ($instituicao) {
            $builder->where('instituicoes_id', $instituicao->id);
        })->with([
            'totem'
        ])->findOrFail($request->get('senha'));

        abort_unless($senha->totem->instituicoes_id == $instituicao->id, 403);
        $origens = $instituicao->origens()->get();
        $carateres_atendimento = $instituicao->atendimentos()->get();
        $atendimento_urgencia =  $senha->atendimentoUrgencia()->with([
            'procedimentosAtendimentoUrgencia',
            'procedimentosAtendimentoUrgencia.convenio',
            'procedimentosAtendimentoUrgencia.procedimento'
        ])->first();

        ChamadaTotem::chamarSenha($senha, 'consultorio');

        return view('instituicao.atendimentos-urgencia/modal-finalizar-atendimento', \compact(
            'senha',
            'origens',
            'atendimento_urgencia',
            'carateres_atendimento'
        ));
    }

    public function finalizarAtendimento(FinalizarAtendimentoRequest $request, SenhaTriagem $senha)
    {
        $this->authorize('habilidade_instituicao_sessao', 'Finalizar_atendimentos_urgencia');
        $instituicao = Instituicao::findOrFail($request->session()->get('instituicao'));
        abort_unless($senha->totem->instituicoes_id == $instituicao->id, 403);

        DB::transaction(function () use ($request, $instituicao, $senha) {
            $dados = collect($request->validated());
            $usuario = $request->user('instituicao');
            // Buscando ou criando novo paciente
            $paciente = null;
            if (!empty($dados->get('cadastro-manual-paciente'))) {
                $paciente = $instituicao->pacientes()->create(collect($dados->get('paciente'))->merge([
                    'personalidade' => 1,
                    'tipo' => 2
                ])->toArray());
            } else {
                $paciente = Pessoa::findOrFail($dados->get('paciente_id'));
                $paciente->criarLogCadastro($usuario, $instituicao->id);
            }

            // Buscando ou criando nova carteirinha
            $carteirinha = null;
            if (!empty($dados->get('cadastro-manual-carteirinha'))) {
                $carteirinha = $paciente->carteirinha()->create($dados->get('carteirinha'));
                $carteirinha->criarLogCadastro($usuario, $instituicao->id);
            } elseif (!empty($dados->get('carteirinha_id'))) {
                $carteirinha = Carteirinha::whereHas('convenio', function (Builder $query) use ($instituicao) {
                    $query->where('instituicao_id', $instituicao->id);
                })->where('id', $dados->get('carteirinha_id'))->first();
            }

            // Registrando o atendimento
            $agendamento_atendimento = AgendamentoAtendimento::create([
                'data_hora' => date('Y-M-d ') . ((new \DateTime($dados->get('hora')))->format('H:i:s')),
                'tipo' => 2,
                'pessoa_id' => $paciente->id
            ]);
            $agendamento_atendimento->criarLogCadastro($usuario, $instituicao->id);

            // Criando atendimento e vinculando atendimento criado
            $atendimento_urgencia = $senha->overwrite($senha->atendimentoUrgencia(), $dados->merge([
                'agendamento_id' => $agendamento_atendimento->id,
                'carteirinha_id' => !empty($carteirinha) ? $carteirinha->id : null,
                'instituicao_id' => $instituicao->id
            ])->toArray(), function ($new) use ($usuario, $instituicao) {
                $new->criarLogInstituicaoCadastro($usuario, $instituicao->id);
            });

            $atendimento_urgencia->overwrite($atendimento_urgencia->procedimentosAtendimentoUrgencia(), $dados->get('procedimentos', []));
            ChamadaTotem::completarChamada($senha);
        });

        return response()->json([
            'result' => true,
            'title' => 'Sucesso',
            'text' => 'Atendimento registrado com sucesso!'
        ]);
    }

    public function visualizarAtendimento(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_atendimentos_urgencia');
        $instituicao = Instituicao::findOrFail($request->session()->get('instituicao'));
        $senha = SenhaTriagem::whereHas('totem', function ($builder) use ($instituicao) {
            $builder->where('instituicoes_id', $instituicao->id);
        })->with([
            'totem'
        ])->findOrFail($request->get('senha'));

        abort_unless($senha->totem->instituicoes_id == $instituicao->id, 403);

        $atendimento_urgencia =  $senha->atendimentoUrgencia()->with([
            'procedimentosAtendimentoUrgencia',
            'procedimentosAtendimentoUrgencia.convenio',
            'procedimentosAtendimentoUrgencia.procedimento'
        ])->first();

        return view('instituicao.atendimentos-urgencia/modal-visualizar-atendimento', \compact(
            'senha',
            'atendimento_urgencia'
        ));
    }
}
