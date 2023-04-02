<?php

namespace App\Http\Livewire\Instituicao;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;
use App\{
    Especialidade,
    FilaTotem,
    Instituicao,
    FilaTriagem,
    Pessoa,
    SenhaTriagem
};
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class AtendimentosUrgenciaPesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    // Valores iniciais
    public $status_chamado = [
        '0' => 'Chamados ou não',
        '1' => 'Não chamados',
        '2' => 'Chamados'
    ];
    public $status_triagem = [
        '0' => 'Triados ou não',
        '1' => 'Não triados',
        '2' => 'Triados'
    ];
    public $faixas_idade = [
        '0'         => 'Todas as idades',
        'menor_12'  => 'Até 12',
        'menor_18'  => 'Até 18',
        'menor_60'  => 'Até 60',
        'maior_12'  => 'Maior de 12',
        'maior_18'  => 'Maior de 18',
        'maior_60'  => 'Maior de 60',
    ];
    public $tipos_ordenacao = [
        'data_hora' => 'Data e hora',
        'prioridade' => 'Prioridade de filas',
        'classificacao' => 'Classificação'
    ];

    public $tipo_ordenacao_selecionado = 'classificacao';
    public $filas_triagem;
    public $setores;
    public $especialidades;
    public $paciente_selecionado;
    public $resultados = [];
    public $instituicao;
    public $dia_semana;
    public $busca = '';

    // Inputs
    public $faixas_idade_selecionada = '';
    public $status_triagem_id = 0;
    public $filas_triagem_id = '';
    public $setores_id = '';
    public $especialidades_id = '';
    public $data = '';
    public $busca_paciente = '';
    public $status_chamado_id = 0;


    protected $updatesQueryString = [
        'busca' => ['except' => ''],
        'faixas_idade_selecionada',
        'status_triagem_id' => ['except' => 0],
        'status_chamado_id' => ['except' => 0],
        'filas_triagem_id' => ['except' => ''],
        'setores_id' => ['except' => ''],
        'especialidades_id' => ['except' => ''],
        'data' => ['except' => ''],
        'busca_paciente' => ['except' => ''],
    ];

    public function mount(Request $request)
    {
        // Instituicao
        $instituicao = $this->instituicao = Instituicao::find($request->session()->get('instituicao'));
        // Todas as filas
        $this->filas_triagem =  $this->instituicao->filasTriagem()->get();
        // Todos os setores
        $this->setores = $this->instituicao->setores()->get();
        // Todas as especialidades
        $this->especialidades = Especialidade::whereHas('prestadoresInstituicao', function ($q) use ($instituicao) {
                $q->where('instituicoes_id', $instituicao->id);
            })
            ->with([
                'prestadoresInstituicao' => function ($q) use ($instituicao) {
                    $q->where('instituicoes_id', $instituicao->id);
                },
                'prestadoresInstituicao.prestador' => function ($q) {
                    $q->select('id', 'nome');
                }
            ])->get();

        $this->tipo_ordenacao_selecionado = $request->get('tipo_ordenacao_selecionado', 'classificacao');
        // Id da fila selecionada
        $this->filas_triagem_id = $request->get('filas_triagem_id', '');
        // Se está ou não triado 0 = Todos, 1 = Não triado, 2 = Triado
        $this->status_triagem_id = $request->get('status_triagem_id', 0);
        // Identificador da especialidade buscada
        $this->especialidades_id = $request->get('especialidades_id', '');
        // Identificador do setor buscado
        $this->setores_id = $request->get('setores_id', '');

        // Data de retirada da senha
        if ($request->data) {
            $this->data = \Carbon\Carbon::createFromFormat('d/m/Y', $request->data)->format('d/m/Y');
            $this->dia_semana = explode("-", \Carbon\Carbon::createFromFormat('d/m/Y', $request->data)->dayName)[0];
        } else {
            $this->data = \Carbon\Carbon::now()->format('d/m/Y');
            $this->dia_semana = explode("-", \Carbon\Carbon::now()->dayName)[0];
        }
    }

    public function render()
    {
        config(["auth.defaults.guard" => "instituicao"]);

        $this->authorize('habilidade_instituicao_sessao', 'visualizar_atendimentos_urgencia');

        $this->performQuery();

        $this->emit('reset_icheck');

        return view('livewire.instituicao.atendimentos-urgencia-pesquisa');
    }

    private function performQuery(): void
    {
        $instituicao = $this->instituicao;
        $data = Carbon::createFromFormat('d/m/Y', $this->data)->format('Y-m-d');
        $query = SenhaTriagem::whereHas('filaTriagem', function (Builder $query) use ($instituicao) {
            $query->where('instituicoes_id', $instituicao->id);
        })->with([
            'filaTriagem',
            'classificacao',
            'paciente' => function ($query) {
                $query->selectRaw('pessoas.*, FLOOR(DATEDIFF(NOW(), pessoas.nascimento) / 360) as idade');
            },
            'atendimentoUrgencia',
            'atendimentoUrgencia.paciente' => function ($query) {
                $query->selectRaw('pessoas.*, FLOOR(DATEDIFF(NOW(), pessoas.nascimento) / 360) as idade');
            },
        ])
            ->orderBy('senhas_triagem.horario_retirada', 'asc')
            ->whereRaw('DATEDIFF(senhas_triagem.horario_retirada, ?) = 0', $data);

        // Ocultando senhas expiradas
        if (Carbon::createFromFormat('d/m/Y', $this->data)->diff(Carbon::now())->days > 0) {
            $query->whereNotNull('senhas_triagem.classificacoes_triagem_id');
        }

        // Filtrando por triados ou não
        if ($this->status_triagem_id == 1) {
            $query->whereNull('senhas_triagem.classificacoes_triagem_id');
        } else if ($this->status_triagem_id == 2) {
            $query->whereNotNull('senhas_triagem.classificacoes_triagem_id');
        }

        // Filtrando por chamados ou não
        if ($this->status_chamado_id == 1) {
            $query->where('senhas_triagem.chamado', '=', 0);
        } else if ($this->status_chamado_id == 2) {
            $query->where('senhas_triagem.chamado', '=', 1);
        }

        // Filtrando por filas
        if (!empty($this->filas_triagem_id)) {
            $fila_triagem = $this->filas_triagem_id;
            $query->whereHas('filaTriagem', function (Builder $query) use ($fila_triagem) {
                $query->where('filas_triagem.id', $fila_triagem);
            });
        }
        // Filtrando por pacientes
        if (!empty($this->busca)) {
            $busca = $this->busca;
            $query->where(function ($query) use ($busca) {
                $query->orWhereHas('atendimentoUrgencia.paciente', function (Builder $query) use ($busca) {
                    $query->where('nome', 'like', "%$busca%");
                })->orWhereHas('paciente', function (Builder $query) use ($busca) {
                    $query->where('nome', 'like', "%$busca%");
                });
            });
        }

        // Filtrando por faixa etária
        if (!empty($this->faixas_idade_selecionada) && $this->faixas_idade_selecionada != '0') {
            $faixa = explode('_', $this->faixas_idade_selecionada);
            $idade = $faixa[1] ?? 40;
            $comparacao = $faixa[0] == 'menor' ? '<=' : '>=';
            $query->where(function ($query) use ($idade, $comparacao) {
                $query->orWhereHas('paciente', function ($query) use ($idade, $comparacao) {
                    $query->where(DB::raw('(DATEDIFF(NOW(), pessoas.nascimento) / 360)'), $comparacao, $idade);
                })->orWhereHas('atendimentoUrgencia.paciente', function ($query) use ($idade, $comparacao) {
                    $query->where(DB::raw('(DATEDIFF(NOW(), pessoas.nascimento) / 360)'), $comparacao, $idade);
                });
            });
        }

        $this->resultados = $query->get();
        switch ($this->tipo_ordenacao_selecionado) {
            case 'prioridade':
                $this->resultados = $this->resultados->sortBy(function ($item) {
                    return 1 - $item->filaTriagem->prioridade;
                });
                break;
            case 'classificacao':
                $this->resultados = $this->resultados->sortBy(function ($item) {
                    if ($item->classificacao) {
                        return 100 - $item->classificacao->prioridade;
                    } else {
                        return 100;
                    }
                });
                break;
        }
    }

    public function updatingData($value): void
    {
        $this->data = \Carbon\Carbon::createFromFormat('d/m/Y', $value)->format('d/m/Y');
        $this->dia_semana = explode("-", \Carbon\Carbon::createFromFormat('d/m/Y', $value)->dayName)[0];
        $this->resetPage();
    }

    public function refresh($value): void
    {
        $this->resetPage();
    }
}
