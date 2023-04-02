<?php

namespace App\Http\Livewire\Comercial;

use App\Comercial;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;

class ComerciaisPesquisa extends Component
{

    use AuthorizesRequests;
    use WithPagination;

    public $pesquisa = '';

    public $usuario;
    private $comerciais;

    protected $updatesQueryString = [
        'pesquisa' => ['except' => ''],
    ];

    public function mount(Request $request)
    {
        $this->usuario = $request->user('comercial');
        $this->comerciais = $this->usuario->comercial;
    }

    public function render()
    {
        $this->performQuery();
        return view('livewire.comercial.comerciais-pesquisa', [
            'usuario' => $this->usuario, 'comerciais' => $this->comerciais
        ]);
    }

    public function performQuery(): void
    {

        $this->comerciais = $this->usuario->comercial()
        ->when($this->pesquisa,function($q){
            $q->where('nome_fantasia','like', "%{$this->pesquisa}%");
        })
        ->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }


}
