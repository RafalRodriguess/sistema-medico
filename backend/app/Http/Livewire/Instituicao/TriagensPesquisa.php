<?php

namespace App\Http\Livewire\Instituicao;

use Livewire\Component;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\WithPagination;
use App\{
    Instituicao,
    SenhaTriagem
};
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class TriagensPesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    public $tipos_ordenacao = [
        'data' => 'Data e hora',
        'prioridade' => 'Prioridade',
        'classificacao' => 'Classificação'
    ];

    public $tipo_ordenacao_selecionado = 'data';
    public $pesquisa = '';
    public $fila_triagem_id = '';
    public $origem_id = '';
    public $data;

    private $resultados;
    private $filas_triagem;

    public $instituicao;

    protected $updatesQueryString = [
        'pesquisa' => ['except' => ''],
        'fila_triagem_id',
        'origens_id',
        'tipos_ordenacao_selecionado' => ['except' => '']
    ];

    public function mount(Request $request)
    {
        $this->instituicao = Instituicao::findOrFail($request->session()->get('instituicao'));
        // Data de retirada da senha
        if ($request->data) {
            $this->data = Carbon::createFromFormat('d/m/Y', $request->data)->format('d/m/Y');
        } else {
            $this->data = Carbon::now()->format('d/m/Y');
        }
    }

    public function render()
    {
        config(["auth.defaults.guard" => "instituicao"]);
        $this->performQuery();

        return view('livewire.instituicao.triagens-pesquisa', [
            'resultados' => $this->resultados,
            'filas_triagem' => $this->filas_triagem,
            'data' => $this->data
        ]);
    }

    private function performQuery()
    {        
        $instituicao = $this->instituicao;
        $data = Carbon::createFromFormat('d/m/Y', $this->data)->format('Y-m-d');
        $query = SenhaTriagem::whereHas('filaTriagem', function (Builder $query) use ($instituicao) {
            $query->where('instituicoes_id', $instituicao->id);
        })->with([
            'filaTriagem',
            'filaTriagem.origem',
            'classificacao',
            'paciente' => function ($query) {
                $query->selectRaw('pessoas.*, FLOOR(DATEDIFF(NOW(), pessoas.nascimento) / 360) as idade');
            }
        ])
            ->orderBy('senhas_triagem.horario_retirada', 'asc')
            ->whereRaw('DATEDIFF(senhas_triagem.horario_retirada, ?) = 0', $data);

        // Ocultando senhas expiradas
        if (Carbon::createFromFormat('d/m/Y', $this->data)->diff(Carbon::now())->days > 0) {
            $query->whereNotNull('senhas_triagem.classificacoes_triagem_id');
        }

        // Filtrando por filas
        if (!empty($this->fila_triagem_id)) {
            $fila_triagem = $this->fila_triagem_id;
            $query->whereHas('filaTriagem', function (Builder $query) use ($fila_triagem) {
                $query->where('filas_triagem.id', $fila_triagem);
            });
        }

        // Filtrando por pacientes
        if (!empty($this->pesquisa)) {
            $pesquisa = $this->pesquisa;
            $query->whereHas('paciente', function (Builder $query) use ($pesquisa) {
                $query->where('nome', 'like', "%$pesquisa%");
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
        
        // Filas de triagem
        $this->filas_triagem = $this->instituicao->filasTriagem()->get();
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }
}
