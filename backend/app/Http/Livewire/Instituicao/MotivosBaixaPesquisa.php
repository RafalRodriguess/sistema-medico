<?php

namespace App\Http\Livewire\Instituicao;

use App\Instituicao;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;

class MotivosBaixaPesquisa extends Component
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
        return view('livewire.instituicao.motivos-baixa-pesquisa', [
            'motivos' => $this->motivos
        ]);
    }

    private function performQuery() {
        $instituicao = Instituicao::findOrFail(request()->session()->get('instituicao'));
        // Busca as filas totem que são permitidas a partir da instituição
        $this->motivos = $instituicao->motivoBaixa()
            ->where('descricao', 'like', "%$this->pesquisa%")
            ->orderBy('id', 'desc')
            ->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }
}
