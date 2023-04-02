<?php

namespace App\Http\Livewire\Admin;

use App\Atendimento;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;

class AtendimentosAdminPesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;
    
    public $pesquisa = '';

    private $atendimentos;

    protected $updatesQueryString = [
        'pesquisa' => ['except' => ''],
    ];

    public function render()
    {
        config(["auth.defaults.guard" => "admin"]);
        $this->authorize('habilidade_admin', 'visualizar_atendimentos');
        $this->performQuery();

        return view('livewire.admin.atendimentos-admin-pesquisa', [
            'atendimentos' => $this->atendimentos
        ]);
    }

    private function performQuery(): void
    {
        $query = Atendimento::query()
                    ->search($this->pesquisa);
        $this->atendimentos = $query->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }
}
