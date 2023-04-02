<?php

namespace App\Http\Livewire\Instituicao;

use App\Instituicao;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;

class EquipesCirurgicasPesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    public $pesquisa = '';

    public $instituicao;

    private $equipes_cirurgicas;

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

        $this->authorize('habilidade_instituicao_sessao', 'visualizar_equipes_cirurgicas');

        $this->performQuery();
                                                           
        return view('livewire.instituicao.equipes-cirurgicas-pesquisa', [
            'equipes_cirurgicas' => $this->equipes_cirurgicas
        ]);
    }

    private function performQuery(): void
    {
        $query = $this->instituicao->equipesCirurgicas()->searchByDescricao($this->pesquisa);

        $this->equipes_cirurgicas = $query->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }
}
