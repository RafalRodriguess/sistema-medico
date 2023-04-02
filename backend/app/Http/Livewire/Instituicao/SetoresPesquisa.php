<?php

namespace App\Http\Livewire\Instituicao;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;
use App\Instituicao;

class SetoresPesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;
    
    public $pesquisa = '';

    public $instituicao;

    private $setores;

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
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_setores');
        $this->performQuery();

        return view('livewire.instituicao.setores-pesquisa', [
            'setores' => $this->setores,
        ]);
    }

    private function performQuery(): void
    {
        $query = $this->instituicao->setores()->search($this->pesquisa);
        $this->setores = $query->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }
}
