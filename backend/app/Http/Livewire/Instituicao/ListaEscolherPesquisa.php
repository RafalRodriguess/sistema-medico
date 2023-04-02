<?php

namespace App\Http\Livewire\Instituicao;

use App\Instituicao;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;

class ListaEscolherPesquisa extends Component
{

    use AuthorizesRequests;
    use WithPagination;

    public $filtro = '';

    public $usuario;

    private $instituicoes;

    protected $updatesQueryString = [
        'filtro' => ['except' => ''],
    ];

    public function mount(Request $request)
    {
        $this->usuario = $request->user('instituicao');
    }
    
    public function render()
    {
        $this->performQuery();

        return view('livewire.instituicao.lista-escolher-pesquisa', [
            'instituicoes' => $this->instituicoes,
        ]);
    }

    private function performQuery(): void
    {
        // $this->instituicoes = $this->usuario->instituicao->map(function (Instituicao $instituicao) {
        //     if($this->filtro != ""){
        //         if(str_contains($instituicao->nome, $this->filtro) ){
        //             $instituicao = $instituicao;
        //         }
        //     }else{
        //         $instituicao = $instituicao;
        //     }
        //     // dump(route('instituicao.eu.escolher_instituicao', [$instituicao]));
        // });

        $this->instituicoes = $this->usuario->instituicao()->orderBy('id', 'DESC')->when($this->filtro, function($q){
            $q->where('nome', 'like', "%{$this->filtro}%");
        })->paginate(15);

    }

    public function updatingFiltro(): void
    {
        $this->resetPage();
    }
}
