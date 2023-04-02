<?php

namespace App\Http\Livewire\Instituicao;

use App\Instituicao;
use App\ModeloImpressao;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;

class ModeloImpressaoPesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;
    
    public $pesquisa = '';

    public $instituicao;
    public $usuario;

    private $modelos;

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
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_modelo_impressao');
        $this->performQuery();  
        return view('livewire.instituicao.modelo-impressao-pesquisa', [
            'modelos' => $this->modelos,
        ]);
    }
 
    private function performQuery(): void
    {
        $query = ModeloImpressao::search($this->pesquisa, $this->instituicao->id, $this->usuario->id);
        $this->modelos = $query->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }
}
