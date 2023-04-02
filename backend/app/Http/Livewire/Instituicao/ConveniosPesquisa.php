<?php

namespace App\Http\Livewire\Instituicao;

use App\Instituicao;
use App\Convenio;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;

class ConveniosPesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    public $pesquisa = '';
    
    private $procedimentos;

    public $instituicao;

    private $convenios;
   

    protected $updatesQueryString = [
        'pesquisa' => ['except' => ''],
        // 'convenio' => ['except' => 0],
    ];

    public function mount(Request $request)
    { 
      
        $this->instituicao = Instituicao::find($request->session()->get('instituicao'));

    }

    public function render()
    {
        config(["auth.defaults.guard" => "instituicao"]);
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_convenios');

        $this->performQuery();  

        return view('livewire.instituicao.convenios-pesquisa', [
            'convenios' => $this->convenios,
        ]);
    }

    private function performQuery(): void
    {

        $query = Convenio::SearchConveniosInstituicao($this->pesquisa, $this->instituicao->id);
        $this->convenios = $query->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }

    public function updatingEspecialidade(): void
    {
        $this->resetPage();
    }

}
