<?php

namespace App\Http\Livewire\Instituicao;

use App\Instituicao;
 
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;

class InstituicaoUsuarioPesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;
    
    public $pesquisa = '';

    public $instituicao;

    private  $instituicaousuario;

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
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_usuario');
        
        $this->performQuery();

        return view('livewire.instituicao.instituicao-usuario-pesquisa', [
            'instituicaousuario' => $this->instituicaousuario,
        ]);
    }

    private function performQuery(): void
    {
        $query = $this->instituicao->instituicaoUsuarios()->withPivot('status')
                    ->search($this->pesquisa);
        $this->instituicaousuario = $query->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }

}
