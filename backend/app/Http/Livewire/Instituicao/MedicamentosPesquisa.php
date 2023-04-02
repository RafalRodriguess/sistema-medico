<?php

namespace App\Http\Livewire\Instituicao;

use App\Instituicao;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;

class MedicamentosPesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;
    
    public $pesquisa = '';

    private $medicamentos;
    public $instituicao;

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
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_medicamentos');
        $this->performQuery();
        
        return view('livewire.instituicao.medicamentos-pesquisa', [
            'medicamentos' => $this->medicamentos
        ]);
    }

    private function performQuery(): void
    {
        $query = $this->instituicao->medicamentosPesquisa()->search($this->pesquisa);
        $this->medicamentos = $query->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }
}
