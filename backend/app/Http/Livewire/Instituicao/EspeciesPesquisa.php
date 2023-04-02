<?php

namespace App\Http\Livewire\Instituicao; 

use App\Instituicao;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Illuminate\Http\Request;
use Livewire\WithPagination; 
 
class EspeciesPesquisa extends Component
{

    use AuthorizesRequests;
    use WithPagination;
    
    public $pesquisa = '';

    public $instituicao;

    private $especies;

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
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_especies');
        $this->performQuery(); 
        return view('livewire.instituicao.especies-pesquisa', [
            'especies' => $this->especies,
        ]);
    }
    
    private function performQuery(): void
    {
        $query = $this->instituicao->especies()->search($this->pesquisa);
        $this->especies = $query->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }

}
