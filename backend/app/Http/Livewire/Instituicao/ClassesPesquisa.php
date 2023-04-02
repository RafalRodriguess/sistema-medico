<?php

namespace App\Http\Livewire\Instituicao;
 
use App\Instituicao;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Illuminate\Http\Request;
use Livewire\WithPagination; 

class ClassesPesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;
    
    public $pesquisa = '';

    public $instituicao;

    private $classes;

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
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_classes');
        $this->performQuery(); 
        return view('livewire.instituicao.classes-pesquisa', [
            'classes' => $this->classes,
        ]);
    }
  
    private function performQuery(): void
    {
        $query = $this->instituicao->classes()->search($this->pesquisa);
        $this->classes = $query->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }

}
