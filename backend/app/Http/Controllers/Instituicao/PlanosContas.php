<?php

namespace App\Http\Controllers\Instituicao;

use App\Http\Controllers\Controller;
use App\Http\Requests\PlanosContas\CriarPlanoContasRequest;
use App\Http\Requests\PlanosContas\EditarPlanoContasRequest;
use App\Instituicao;
use App\PlanoConta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlanosContas extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_plano_contas');
        return view('instituicao.planos_contas.lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_plano_contas');
        $instituicaoId = $request->session()->get('instituicao');
        $instituicao = Instituicao::find($instituicaoId);
        $planosContas = $instituicao->planosContas()->orderBy('codigo', 'asc')->get();

        $totalContaspai = PlanoConta::getTotalPai($instituicaoId)->count() + 1;
        $centroCusto = $instituicao->centrosCustos()->get();

        return view('instituicao.planos_contas.criar', \compact('planosContas', 'totalContaspai', 'centroCusto'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CriarPlanoContasRequest $request)
    {

        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_plano_contas');
        $instituicao = Instituicao::find($request->session()->get("instituicao"));
        $dados = $request->validated();

        // dd($dados);
        unset($dados['cc']);

        $percentual_total = 0;
        $cc = collect($request->validated()['cc'])
            ->filter(function ($cc) {
                return !is_null($cc['centro_custos_id']);
            })
            ->map(function($cc) use (&$percentual_total) {
                $percentual = str_replace(',', '.', $cc['percentual']);
                $percentual_total += $percentual;
                return [
                    'centro_custos_id' => $cc['centro_custos_id'],
                    'percentual' => $percentual
                ];
            });

        // Verifica se o percentual totalizou 100%
        if($percentual_total != 100 && $dados['rateio_auto'] == 1) {
            return redirect()->back()
                             ->withInput($request->validated())
                             ->withErrors(['porcentagem' => 'A soma das porcentagens deve totalizar 100%!']);
        }

        if ($dados['rateio_auto']) {
            DB::transaction(function() use ($request, $instituicao, $dados, $cc){

                $usuario_logado = $request->user('instituicao');

                $planoContas = $instituicao->planosContas()->create($dados);
                $planoContas->centroCusto()->attach($cc);
                $planoContas->criarLogCadastro($usuario_logado, $instituicao->id);
            });
        }else{
            DB::transaction(function() use ($request, $instituicao, $dados){

                $usuario_logado = $request->user('instituicao');

                $planoContas = $instituicao->planosContas()->create($dados);
                $planoContas->criarLogCadastro($usuario_logado, $instituicao->id);
            });

        }


        return redirect()->route('instituicao.planosContas.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'plano de Conta criado com sucesso!'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, PlanoConta $planoConta)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_plano_contas');
        $instituicao = Instituicao::find($request->session()->get("instituicao"));

        $pai = null;

        if($planoConta->plano_conta_id){
            $pai = PlanoConta::getPai($planoConta->plano_conta_id)->first();
        }

        // $rateioAutoCC = PlanoConta::with('rateio_auto_plano_conta')->get();
        // $planoConta->load([
        //     'rateio_auto_plano_conta',
        // ]);

        //dd($rateioAutoCC);

        $centroCusto = $instituicao->centrosCustos()->get();

        return view('instituicao.planos_contas.editar', \compact('planoConta', 'pai', 'centroCusto'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EditarPlanoContasRequest $request, PlanoConta $planoConta)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_plano_contas');

        $instituicao = $request->session()->get("instituicao");
        abort_unless($instituicao===$planoConta->instituicao_id, 403);

        $dados = $request->validated();

        unset($dados['cc']);

        $percentual_total = 0;
        if ($dados['rateio_auto']) {
            $cc = collect($request->validated()['cc'])
                ->filter(function ($cc) {
                    return !is_null($cc['centro_custos_id']);
                })
                ->map(function($cc) use (&$percentual_total){
                    $percentual = str_replace(',', '.', $cc['percentual']);
                    $percentual_total += $percentual;
                    return [
                        'centro_custos_id' => $cc['centro_custos_id'],
                        'percentual' => $percentual
                    ];
                });
            // Verifica se o percentual totalizou 100%

            if($percentual_total != 100) {
                return redirect()->back()
                                    ->withInput($request->validated())
                                    ->withErrors(['porcentagem' => 'A soma das porcentagens deve totalizar 100%!']);
            }
        }

        if ($dados['rateio_auto']) {
            DB::transaction(function() use ($request, $instituicao, $planoConta, $dados, $cc){

                $usuario_logado = $request->user('instituicao');

                $planoConta->update($dados);
                $planoConta->centroCusto()->detach();
                $planoConta->centroCusto()->attach($cc);
                $planoConta->criarLogCadastro($usuario_logado, $instituicao);
            });
        }else{
            DB::transaction(function() use ($request, $instituicao, $planoConta, $dados){
                $usuario_logado = $request->user('instituicao');

                $planoConta->update($dados);
                $planoConta->criarLogEdicao($usuario_logado, $instituicao);
            });
        }

        return redirect()->route('instituicao.planosContas.edit', [$planoConta])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Plano de contas alterado com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, PlanoConta $planoConta)
    {
        $this->authorize('habilidade_instituicao_sessao', 'excluir_plano_contas');

        $instituicao = $request->session()->get("instituicao");
        abort_unless($instituicao===$planoConta->instituicao_id, 403);

        if(PlanoConta::getTotalFilhos($planoConta->id)->count() == 0){

            DB::transaction(function () use ($planoConta, $request, $instituicao) {
                $usuario_logado = $request->user('instituicao');
                $planoConta->centroCusto()->detach();
                $planoConta->delete();
                $planoConta->criarLogExclusao($usuario_logado, $instituicao);


                return $planoConta;
            });

            return redirect()->route('instituicao.planosContas.index')->with('mensagem', [
                'icon' => 'success',
                'title' => 'Sucesso.',
                'text' => 'Plano de contas excluído com sucesso!'
            ]);
        }else{
            return redirect()->route('instituicao.planosContas.index')->with('mensagem', [
                'icon' => 'error',
                'title' => 'Erro.',
                'text' => 'Existe um ou mais planos de contas filhas deste item. impossivel excluir!'
            ]);
        }
    }

    public function getCodigopai(Request $request, PlanoConta $planoConta)
    {
        $instituicao = $request->session()->get("instituicao");
        abort_unless($instituicao===$planoConta->instituicao_id, 403);

        $totalContasFilhas = PlanoConta::getTotalFilhos($planoConta->id)->count() + 1;

        return response()->json(['teste' => PlanoConta::getTotalFilhos($planoConta->id)->count(), 'totalFilhos' => $totalContasFilhas, 'codPai' => $planoConta->codigo, 'padrao' => $planoConta->padrao]);

    }
}
