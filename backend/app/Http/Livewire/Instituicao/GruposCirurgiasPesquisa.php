<?php

namespace App\Http\Livewire\Instituicao;

use App\Instituicao;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;

class GruposCirurgiasPesquisa extends Component
{

    use AuthorizesRequests;
    use WithPagination;
    
    public $pesquisa = '';

    public $instituicao;

    private $gruposCirurgias;

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
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_grupos_cirurgias');
        $this->performQuery();

        return view('livewire.instituicao.grupos-cirurgias-pesquisa', [
            'gruposCirurgias' => $this->gruposCirurgias,
        ]);
    }

    private function performQuery(): void
    {
        $query = $this->instituicao->gruposCirurgias()->search($this->pesquisa);
        $this->gruposCirurgias = $query->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }
}
