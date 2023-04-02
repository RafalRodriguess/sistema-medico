<?php

namespace App\Http\Livewire\Instituicao;

use App\Instituicao;
use App\ModeloRecibo;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;

class ModeloReciboPesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;
    
    public $pesquisa = '';

    private $modelos;

    public $instituicao;

    protected $updatesQueryString = [
        'pesquisa' => ['except' => ''],
    ];

    public function mount(Request $request)
    {
        $this->instituicao = Instituicao::find($request->session()->get('instituicao'));
        $this->usuario = $request->user('instituicao');
    }

    
    public function render()
    {
        config(["auth.defaults.guard" => "instituicao"]);
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_modelo_recibo');
        $this->performQuery();  
        return view('livewire.instituicao.modelo-recibo-pesquisa', ['modelos' => $this->modelos]);
    }
 
    private function performQuery(): void
    {
        $query = $this->instituicao->modelosRecibo()->search($this->pesquisa);
        $this->modelos = $query->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }
}
