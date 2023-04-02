<?php

namespace App\Http\Livewire\Instituicao;

use App\Instituicao;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;

class FornecedoresPesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;
    
    public $pesquisa = '';

    public $instituicao;

    private  $fornecedores;

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
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_fornecedores');
        
        $this->performQuery();

        return view('livewire.instituicao.fornecedores-pesquisa', [
            'fornecedores' => $this->fornecedores,
        ]);
    }

    private function performQuery(): void
    {
        $query = $this->instituicao->instituicaoPessoas()
            ->fornecedores()->searchByNome($this->pesquisa);

        $this->fornecedores = $query->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }
}
