<?php

namespace App\Http\Livewire\Instituicao;

use App\Instituicao;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;

class CompromissoPesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;
    
    public $pesquisa = '';

    public $instituicao;

    private $compromissos;

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
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_compromissos');
        $this->performQuery(); 
        
        return view('livewire.instituicao.compromisso-pesquisa', [
            'compromissos' => $this->compromissos
        ]);
    }
 
    private function performQuery(): void
    {
        $query = $this->instituicao->compromissos()->search($this->pesquisa);
        $this->compromissos = $query->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }
}
