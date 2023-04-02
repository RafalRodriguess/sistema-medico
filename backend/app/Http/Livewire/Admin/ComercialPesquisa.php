<?php

namespace App\Http\Livewire\Admin;

use App\Comercial;
 
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;

class ComercialPesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;
    
    public $pesquisa = '';

    private  $comerciais;

    protected $updatesQueryString = [
        'pesquisa' => ['except' => ''],
    ];

    public function render()
    {
        config(["auth.defaults.guard" => "admin"]);
        $this->authorize('habilidade_admin', 'visualizar_comercial');
        $this->performQuery();

        return view('livewire.admin.comercial-pesquisa', [
            'comerciais' => $this->comerciais
        ]);
    }

    private function performQuery(): void
    {
        $query = Comercial::query()
                    ->search($this->pesquisa);
        $this->comerciais = $query->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }
}
