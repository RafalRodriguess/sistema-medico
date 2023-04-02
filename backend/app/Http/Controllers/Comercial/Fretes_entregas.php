<?php

namespace App\Http\Controllers\Comercial;

use App\Http\Requests\ComercialFretes\Entrega\UpdateFretesEntregasRequest;
use App\Http\Requests\ComercialFretes\Entrega\CriarFiltroEntregaRequest;
use App\FretesEntrega;
use App\FretesRetirada;
use App\Comercial;
use App\Fretes;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Fretes_entregas extends Controller
{

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('habilidade_comercial_sessao', 'cadastrar_fretes');
        $comercial_id = $request->session()->get('comercial');
        $frete = Fretes::whereHas('comercial', function ($q) use ($comercial_id) {
            $q->where('tipo_frete', 'entrega');
            $q->where('comercial_id', $comercial_id);
            $q->whereNull('fretes.deleted_at');
        })->get()->first();


        switch ($frete->tipo_filtro) {
            case 'cidade':
                return view('comercial.fretes/formulario_entregas/cidades/criar', \compact('frete'));
                break;
            case 'cidade_bairro':
                return view('comercial.fretes/formulario_entregas/bairros/criar', \compact('frete'));
            case 'faixa_cep':
                break;
            case 'cep_unico':
                break;
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CriarFiltroEntregaRequest $request, Fretes $frete)
    {
        
        $this->authorize('habilidade_comercial_sessao', 'cadastrar_fretes');

        switch ($request->get('tipo_filtro')) {
            case 'cidade':
                $dados = $this->requestCidade($request);
                break;
            case 'cidade_bairro':
                $dados = $this->requestCidadeBairro($request);
                break;
            case 'faixa_cep':
                $dados = $this->requestFaixaCep();
                break;
            case 'cep_unico':
                $dados = $this->requestCepUnico();
                break;
        }
       
        if ($dados) {

            //caso nao exista, cadastro um novo
            $new = FretesEntrega::create($dados);

            $usuario_logado = $request->user('comercial');
            $comercial_id = $request->session()->get('comercial');
            $new->criarLogCadastro(
                $usuario_logado,
                $comercial_id
            );

            return redirect()->route('comercial.fretes_entregas')->with('mensagem', [
                'icon' => 'success',
                'title' => 'Sucesso.',
                'text' => 'Filtro criado com sucesso!'
            ]);
        } else {

            return redirect()->route('comercial.fretes_entregas')->with('mensagem', [
                'icon' => 'error',
                'title' => 'Falha.',
                'text' => 'Falha ao cadastrar o filtro de entrega!'
            ]);
        }
    }

    public function requestCidade(Request $request)
    {

        $create = [
            'cidade' => $request->get('cidade'),
            'fretes_id' => $request->get('id_frete'),
            'valor' => $request->get('valor'),
            'valor_minimo' => $request->get('valor_minimo'),
            'tipo_prazo' => $request->get('tipo_prazo'),
            'prazo_minimo' => $request->get('prazo_minimo'),
            'prazo_maximo' => $request->get('prazo_maximo'),
        ];

        
        // verifica se ja nao existe  o filtro no banco 
        $comercial_id = $request->session()->get('comercial');
        
        $frete = FretesEntrega::whereHas('frete', function ($q) use ($comercial_id, $create) {
            $q->where('cidade', $create['cidade']);
            $q->where('fretes_id', $create['fretes_id']);
            $q->where('comercial_id', $comercial_id);
            $q->whereNull('fretes.deleted_at');
        })->get()->first();

    

        if ($frete) {
            return null;
        } else {
            return $create;
        }
    }


    public function requestCidadeBairro(Request $request)
    {
        $create = [
            'cidade' => $request->get('cidade'),
            'bairro' => $request->get('bairro'),
            'fretes_id' => $request->get('id_frete'),
            'valor' => $request->get('valor'),
            'valor_minimo' => $request->get('valor_minimo'),
            'tipo_prazo' => $request->get('tipo_prazo'),
            'prazo_minimo' => $request->get('prazo_minimo'),
            'prazo_maximo' => $request->get('prazo_maximo'),
        ];
        $comercial_id = $request->session()->get('comercial');
        // verifica se ja nao existe  o filtro no banco 
        $frete = FretesEntrega::whereHas('frete', function ($q) use ($comercial_id, $create) {
            $q->where('cidade', $create['cidade']);
            $q->where('bairro', $create['bairro']);
            $q->where('fretes_id', $create['fretes_id']);
            $q->where('comercial_id', $comercial_id);
            $q->whereNull('fretes.deleted_at');
        })->get()->first();

        if ($frete) {
            return null;
        } else {
            return $create;
        }
    }

    public function edit(FretesEntrega $filtro)
    {


        $this->authorize('habilidade_comercial_sessao', 'editar_fretes');

        $config = Fretes::find($filtro->fretes_id);

        switch ($config->tipo_filtro) {
            case 'cidade':
                return view('comercial.fretes/formulario_entregas/cidades/editar', \compact('filtro'));
                break;
            case 'cidade_bairro':
                return view('comercial.fretes/formulario_entregas/bairros/editar', \compact('filtro'));
                break;
            case 'faixa_cep':
                //
                break;
            case 'cep_unico':
                //
                break;
        }
    }


    public function update(CriarFiltroEntregaRequest $request, FretesEntrega $filtro)
    {
        $this->authorize('habilidade_comercial_sessao', 'editar_fretes');


        $dados = $request->validated();

        DB::transaction(function () use ($request, $filtro, $dados) {
            $filtro->update($dados);

            $usuario_logado = $request->user('comercial');
            $comercial_id = $request->session()->get('comercial');
            $filtro->criarLogEdicao(
                $usuario_logado,
                $comercial_id
            );

            return $filtro;
        });

        //return redirect()->route('administradores.edit', [$usuario])->with('mensagem', 'Salvo com sucesso');
        return redirect()->route('comercial.fretes_entrega.edit', [$filtro])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Filtro atualizado com sucesso!'
        ]);
    }


    /**
     * Remove the specified resource from storage.
     * 
     * @param  \App\Procedimento  $procedimento
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, FretesEntrega $filtro)
    {

        $this->authorize('habilidade_comercial_sessao', 'excluir_fretes');
        DB::transaction(function () use ($request, $filtro) {
            $filtro->delete();

            $usuario_logado = $request->user('comercial');
            $comercial_id = $request->session()->get('comercial');
            $filtro->criarLogExclusao(
                $usuario_logado,
                $comercial_id
            );

            return $filtro;
        });

        return redirect()->route('comercial.fretes_entregas')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Filtro excluído com sucesso!'
        ]);
    }
}
