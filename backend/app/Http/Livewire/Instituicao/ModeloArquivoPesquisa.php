<?php

namespace App\Http\Livewire\Instituicao;

use App\Instituicao;
use App\ModeloArquivo;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;

class ModeloArquivoPesquisa extends Component
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
    }

    
    public function render()
    {
        config(["auth.defaults.guard" => "instituicao"]);
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_modelo_arquivo');
        $this->performQuery();  
        return view('livewire.instituicao.modelo-arquivo-pesquisa', [
            'modelos' => $this->modelos,
        ]);
    }
 
    private function performQuery(): void
    {
        $query = $this->instituicao->modeloArquivos()->search($this->pesquisa);
        $this->modelos = $query->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }
}
