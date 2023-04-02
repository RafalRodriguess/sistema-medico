<?php

namespace App\Http\Livewire\Instituicao;

use App\ModeloEncaminhamento;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;

class ModeloEncaminhamentoPesquisa extends Component
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
        $this->instituicao = $request->session()->get('instituicao');
        $this->usuario = $request->user('instituicao');
    }

    
    public function render()
    {
        config(["auth.defaults.guard" => "instituicao"]);
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_modelo_encaminhamento');
        $this->performQuery();  
        return view('livewire.instituicao.modelo-encaminhamento-pesquisa', [
            'modelos' => $this->modelos,
        ]);
    }
 
    private function performQuery(): void
    {
        $query = ModeloEncaminhamento::search($this->pesquisa, $this->instituicao, $this->usuario->id);
        $this->modelos = $query->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }
}
