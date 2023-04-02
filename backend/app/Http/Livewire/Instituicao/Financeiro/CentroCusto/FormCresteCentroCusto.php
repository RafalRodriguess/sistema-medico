<?php

namespace App\Http\Livewire\Instituicao\Financeiro\CentroCusto;

use App\CentroCusto;
use App\Instituicao;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;

use function GuzzleHttp\Promise\all;

class FormCresteCentroCusto extends Component
{

    use AuthorizesRequests;
    use WithPagination;


    public $descricao;

    public $email;

    public $gestor;

    public $codigo;

    public $pai_grupo_id = null;

    public $instituicao;

    protected $listeners = [
        'getCurrentlyCode',
    ];


    public function mount(Request $request)
    {
        $this->instituicao = Instituicao::find($request->session()->get('instituicao'));
    }


    public function getCurrentlyCode($pai_id = null)
    {
        if ($pai_id && $pai_id!==0) {
            $pai = CentroCusto::query()->where('id', $pai_id)->first();
            $pai_codigo = $pai->codigo;
            $pai_filhos = $pai->filhos()->count() + 1;
            $codigo_filho = "$pai_codigo"."."."$pai_filhos";
            $this->codigo = $codigo_filho;
            $this->pai_grupo_id = $pai->grupo_id;
        }
        if ($pai_id==null || $pai_id==0) {
            $this->codigo = $this->instituicao->centrosCustos()->orfaos()->count() + 1;
            $this->pai_grupo_id = null;
        }
    }

    public function render()
    {
        config(["auth.defaults.guard" => "instituicao"]);
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_centro_de_custo');
        // dd($this->instituicao);
        return view('livewire.instituicao.financeiro.centro-custo.form-creste-centro-custo', [
            'centros_custos' => $this->instituicao->centrosCustos()->get(),
            'grupos' => CentroCusto::getGrupos(),
            'setores_exame' => $this->instituicao->setoresExame()->get()
        ]);
    }
}
