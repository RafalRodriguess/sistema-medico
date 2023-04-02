<?php

namespace App\Http\Livewire\Instituicao;

use App\Instituicao;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;

class SangueDerivadoPesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;
    
    public $pesquisa = '';

    public $instituicao;

    private $sanguesDerivados;

    protected $updatesQueryString = [
        'pesquisa' => ['except' => ''],
    ];

    public function mount(Request $request)
    {
        $this->instituicao = Instituicao::find($request->session()->get('instituicao'));
    }

    public function render()
    {
        config(["auth.defaults.guard" => "instituicao"]);
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_sangues_derivados');
        $this->performQuery();

        return view('livewire.instituicao.sangue-derivado-pesquisa', [
            'sanguesDerivados' => $this->sanguesDerivados,
        ]);
    }

    private function performQuery(): void
    {
        $query = $this->instituicao->sanguesDerivados()->search($this->pesquisa);
        $this->sanguesDerivados = $query->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }
}
