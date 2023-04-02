<?php

namespace App\Http\Controllers\Comercial;

use App\Http\Requests\ComercialFretes\Retirada\UpdateFretesRetiradasRequest;
use App\Http\Requests\ComercialFretes\Entrega\UpdateFretesEntregasRequest;
use App\FretesEntrega;
use App\FretesRetirada;
use App\Comercial;
use App\Fretes;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FretesComercial extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function entregas(Request $request)
    {

        $comercial_id = $request->session()->get('comercial');
        $this->authorize('habilidade_comercial_sessao', 'visualizar_fretes');
        // pega o frete entrega do comercial
        $comercial = $request->session()->get('comercial');

        $configfrete = Fretes::whereHas('comercial', function ($q) use($comercial_id){
            $q->where('tipo_frete', 'entrega');
            $q->where('comercial_id', $comercial_id);
        })->first();


        return view('comercial.fretes/lista_entregas', compact('configfrete'));
    }


    public function retiradas(Request $request)
    {

        $this->authorize('habilidade_comercial_sessao', 'visualizar_fretes');
        $comercial_id = $request->session()->get('comercial');
        // pega o frete Retirada do comercial

        $configfrete = Fretes::whereHas('comercial', function ($q) use($comercial_id){
            $q->where('tipo_frete', 'retirada');
            $q->where('comercial_id', $comercial_id);
        })->first();

        return view('comercial.fretes/lista_retiradas', compact('configfrete'));
    }

    public function update_frete_entrega(UpdateFretesEntregasRequest $request)
    {

        $usuario_logado = $request->user('comercial');

        $comercial_id = $request->session()->get('comercial');

        $dados = $request->validated();
        $this->authorize('habilidade_comercial_sessao', 'editar_fretes');

        //frete entrega atual do comercial
        $frete = Fretes::whereHas('comercial', function ($q) use($comercial_id){
            $q->where('tipo_frete', 'entrega');
            $q->where('comercial_id', $comercial_id);
            $q->whereNull('fretes.deleted_at');
        })->get()->first();

        if ($frete) {
            $update = [
                'ativado' => (isset($dados['ativado'])) ? 1 : 0,
                'tipo_prazo' => $dados['tipo_prazo'],
                'prazo_minimo' => $dados['prazo_minimo'],
                'prazo_maximo' => $dados['prazo_maximo'],
            ];

            $frete->update($update);

            $frete->criarLogEdicao(
                $usuario_logado,
                $comercial_id
            );

            return redirect()->route('comercial.fretes_entregas')->with('mensagem', [
                'icon' => 'success',
                'title' => 'Sucesso.',
                'text' => 'Configuraçao de entregas atualizado com sucesso!'
            ]);
        } else {

            return redirect()->route('comercial.fretes_entregas')->with('mensagem', [
                'icon' => 'error',
                'title' => 'Falha.',
                'text' => 'Falha ao atualizar a configuração de entrega!'
            ]);
        }
    }

    public function update_frete_retirada(UpdateFretesRetiradasRequest $request)
    {

        $usuario_logado = $request->user('comercial');

        $comercial_id = $request->session()->get('comercial');

        $dados = $request->validated();

        $this->authorize('habilidade_comercial_sessao', 'editar_fretes');

        //frete entrega atual do comercial
        $frete = Fretes::whereHas('comercial', function ($q) use($comercial_id){
            $q->where('tipo_frete', 'retirada');
            $q->where('comercial_id', $comercial_id);
            $q->whereNull('fretes.deleted_at');
        })->get()->first();

        if ($frete) {
            $update = [
                'ativado' => (isset($dados['ativado'])) ? 1 : 0
            ];

            $frete->update($update);

            $frete->criarLogEdicao(
                $usuario_logado,
                $comercial_id
            );


            return redirect()->route('comercial.fretes_retiradas')->with('mensagem', [
                'icon' => 'success',
                'title' => 'Sucesso.',
                'text' => 'Configuraçao de retirada atualizado com sucesso!'
            ]);
        } else {

            return redirect()->route('comercial.fretes_retiradas')->with('mensagem', [
                'icon' => 'error',
                'title' => 'Falha.',
                'text' => 'Falha ao atualizar a configuração de entrega!'
            ]);
        }
    }
}
