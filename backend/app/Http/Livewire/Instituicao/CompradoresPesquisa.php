<?php

namespace App\Http\Livewire\Instituicao;

use App\Instituicao;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Illuminate\Http\Request;
use Livewire\WithPagination; 

class CompradoresPesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;
    
    public $pesquisa = '';

    public $instituicao;

    private $compradores;

    public $usuarios = 0;

    protected $updatesQueryString = [
        'pesquisa' => ['except' => ''],
        'usuarios' => ['except' => 0], 
    ];

    public function mount(Request $request)
    {
        $this->instituicao = Instituicao::find($request->session()->get('instituicao'));  
        
    }

    public function render()
    {
        config(["auth.defaults.guard" => "instituicao"]);
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_comprador');
        $this->performQuery(); 

        $listaUsuarios = $this->instituicao->instituicaoUsuarios()->get();  
        
        return view('livewire.instituicao.compradores-pesquisa', [
            'compradores' => $this->compradores,
            'listaUsuarios' => $listaUsuarios
        ]);
    }
 
    private function performQuery(): void
    {
        $query = $this->instituicao->compradores()->search($this->pesquisa,$this->usuarios);
        $this->compradores = $query->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }

    public function updatingUsuarios(): void
    {
        $this->resetPage();
    } 
}
