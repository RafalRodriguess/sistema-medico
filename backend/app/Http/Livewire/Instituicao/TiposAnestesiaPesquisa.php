<?php
namespace App\Http\Livewire\Instituicao;

use App\Instituicao;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;

class TiposAnestesiaPesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;
    
    public $pesquisa = '';

    public $instituicao;

    private $tiposAnestesia;

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
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_tipos_anestesia');
        $this->performQuery();

        return view('livewire.instituicao.tipos-anestesia-pesquisa', [
            'tiposAnestesia' => $this->tiposAnestesia,
        ]);
    }

    private function performQuery(): void
    {
        $query = $this->instituicao->tipoAnestesia()->search($this->pesquisa);
        $this->tiposAnestesia = $query->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }
}
