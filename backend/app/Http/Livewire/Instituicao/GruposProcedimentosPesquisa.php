<?php

namespace App\Http\Livewire\Instituicao;

use App\Instituicao;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;

class GruposProcedimentosPesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;
    
    public $pesquisa = '';
    public $instituicao;
    private  $grupos;

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
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_grupos');
        $this->performQuery();

        return view('livewire.instituicao.grupos-procedimentos-pesquisa', [
            'grupos' => $this->grupos
        ]);
    }

    private function performQuery(): void
    {
        $query = $this->instituicao->grupoProcedimentos()->search($this->pesquisa);
        $this->grupos = $query->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }
    
    
}
