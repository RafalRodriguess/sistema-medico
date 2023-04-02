<?php

namespace App\Http\Livewire\Instituicao\Financeiro\CentroCusto;

use App\Instituicao;
use App\CentroCusto;
use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class FormEditCentroCusto extends Component
{
    use AuthorizesRequests;

    private $centro_custo;

    public $instituicao;

    public function mount(Request $request, $centro_custo)
    {
        $this->instituicao = Instituicao::find($request->session()->get('instituicao'));

        $this->centro_custo = $centro_custo;
    }


    public function render()
    {
        config(["auth.defaults.guard" => "instituicao"]);

        $this->authorize('habilidade_instituicao_sessao', 'editar_centro_de_custo');

        return view('livewire.instituicao.financeiro.centro-custo.form-edit-centro-custo', [
            'grupos' => CentroCusto::getGrupos(),
            'centro_custo' => $this->centro_custo,
            'setores_exame' => $this->instituicao->setoresExame()->get()
        ]);
    }
}
