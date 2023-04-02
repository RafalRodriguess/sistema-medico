<?php

namespace App\Http\Livewire\Instituicao;

use App\Instituicao;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;

class UnidadesPesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;
    
    public $pesquisa = '';

    public $instituicao;

    private $unidades;

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
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_unidade');
        $this->performQuery();

        return view('livewire.instituicao.unidades-pesquisa', [
            'unidades' => $this->unidades,
        ]);
    }
 
    private function performQuery(): void
    {
        $query = $this->instituicao->unidades()->search($this->pesquisa);
        $this->unidades = $query->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }

}
