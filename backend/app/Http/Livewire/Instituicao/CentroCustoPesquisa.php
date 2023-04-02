<?php

namespace App\Http\Livewire\Instituicao;

use App\Instituicao;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;

class CentroCustoPesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;
    
    public $pesquisa = '';

    public $instituicao;

    private $centros_custos;

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
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_centro_de_custo');
        $this->performQuery();
        return view('livewire.instituicao.centro-custo-pesquisa', [
            'centros_custos' => $this->centros_custos,
        ]);
    }

    private function performQuery(): void
    {
        $query = $this->instituicao->centrosCustos()->search($this->pesquisa);
        $this->centros_custos = $query->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }
}
