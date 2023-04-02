<?php

namespace App\Http\Livewire\Instituicao;

use Livewire\Component;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\WithPagination;
use App\MotivoCancelamentoExame;

class MotivosCancelamentoExamePesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    public $pesquisa = '';

    private $motivos;

    protected $updatesQueryString = [
        'pesquisa' => ['except' => ''],
    ];


    public function render()
    {
        config(["auth.defaults.guard" => "instituicao"]);
        $this->performQuery();

        return view('livewire.instituicao.motivos-cancelamento-exame-pesquisa', [
            'motivos' => $this->motivos
        ]);
    }

    private function performQuery() {
        $instituicoes = request()->user('instituicao')->instituicao->pluck('id');
        // Buscando somente os registros das instituições cujo usuario está inserido
        $this->motivos = MotivoCancelamentoExame::query()->select('motivos_cancelamento_exame.*')->search($this->pesquisa)
                                                    ->join('procedimentos_instituicoes', 'procedimentos_id', '=', 'procedimentos_id')
                                                    ->whereIn('procedimentos_instituicoes.instituicoes_id', $instituicoes)
                                                    ->whereNull('motivos_cancelamento_exame.deleted_at')
                                                    ->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }

}
