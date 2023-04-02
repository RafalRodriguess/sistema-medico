<?php

namespace App\Http\Livewire\Admin;

use App\Usuario;
use App\UsuarioEndereco;
 
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;

class UsuarioEnderecoPesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;
    
    public $pesquisa = '';

    public $usuario;

    private  $enderecos;

    protected $updatesQueryString = [
        'pesquisa' => ['except' => ''],
    ];

    public function mount(Usuario $usuario)
    {
        $this->usuario = $usuario;
    }

    public function render()
    {
        config(["auth.defaults.guard" => "admin"]);
        $this->authorize('habilidade_admin', 'visualizar_endereco_usuario');
        $this->performQuery();

        return view('livewire.admin.usuario-endereco-pesquisa', [
            'enderecos' => $this->enderecos,
            'usuario' => $this->usuario
        ]);
    }

    private function performQuery(): void
    {
        $query = $this->usuario->usuarioEnderecos()
                    ->search($this->pesquisa);
        $this->enderecos = $query->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }
}
