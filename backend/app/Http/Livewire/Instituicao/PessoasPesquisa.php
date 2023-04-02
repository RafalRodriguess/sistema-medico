<?php

namespace App\Http\Livewire\Instituicao;

use App\Instituicao;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;

class PessoasPesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;
    
    public $pesquisa = '';

    public $instituicao;
    public $medico = false;

    private  $pessoas;

    protected $updatesQueryString = [
        'pesquisa' => ['except' => ''],
    ];

    public function mount(Request $request)
    {
        $this->instituicao = Instituicao::find($request->session()->get('instituicao'));
        $usuario = $request->user('instituicao');
        if($usuario->prestadorMedico()->first()){
            $this->medico = true;
        }
    }

    public function render()
    {
        config(["auth.defaults.guard" => "instituicao"]);
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_pessoas');
        
        $this->performQuery();

        return view('livewire.instituicao.pessoas-pesquisa', [
            'pessoas' => $this->pessoas,
            'medico' => $this->medico,
            'instituicao' => $this->instituicao,
        ]);
    }

    private function performQuery(): void
    {
        $query = $this->instituicao->instituicaoPessoas()->notFornecedores()->searchByNome($this->pesquisa);

        $this->pessoas = $query->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }

}
