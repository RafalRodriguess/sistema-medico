<?php

namespace App\Http\Livewire\Admin;

use App\Instituicao;
 
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;

class UsuarioInstituicaoPesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;
    
    public $pesquisa = '';

    public $instituicao;

    private  $instituicaousuarios;

    protected $updatesQueryString = [
        'pesquisa' => ['except' => ''],
    ];

    public function mount(Instituicao $instituicao)
    {
        $this->instituicao = $instituicao;
    }

    public function render()
    {
        config(["auth.defaults.guard" => "admin"]);
        $this->authorize('habilidade_admin', 'visualizar_usuario_instituicao');
        $this->performQuery();

        return view('livewire.admin.usuario-instituicao-pesquisa', [
            'instituicaousuarios' => $this->instituicaousuarios,
            'instituicao' => $this->instituicao
        ]);
    }

    private function performQuery(): void
    {
        $query = $this->instituicao->instituicaoUsuarios()
                    ->search($this->pesquisa);
        $this->instituicaousuarios = $query->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }

}
