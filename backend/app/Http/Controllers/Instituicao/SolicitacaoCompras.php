<?php

namespace App\Http\Controllers\Instituicao;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\InstituicaoSolicitacaoCompra;
use App\Http\Requests\SolicitacaoCompras\CreateSolicitacaoComprasRequest;
use App\Http\Requests\SolicitacaoCompras\UpdateSolicitacaoComprasRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\{
    Instituicao, Produto
};

class SolicitacaoCompras extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    { 
        
        return view('instituicao.solicitacao_compras/lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        $setores = $instituicao->setoresExame()->get();
        $motivoPedidos = $instituicao->motivo_pedidos()->get();
        $compradores = $instituicao->compradores()->get();
        $estoques = $instituicao->estoques()->get();
              
        return view('instituicao.solicitacao_compras/criar', compact('setores', 'motivoPedidos', 'compradores', 'estoques'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateSolicitacaoComprasRequest $request)
    {
        // $this->authorize('...', '...');
        
        
        $instituicao = Instituicao::findOrFail($request->session()->get('instituicao'));
    
      
        DB::transaction(function() use ($request, $instituicao){
            $usuario_logado = $request->user('instituicao');

            $request['data_solicitacao'] = Carbon::parse($request->data_solicitacao)->format('Y-m-d');
            $request['data_maxima'] = Carbon::parse($request->data_maxima)->format('Y-m-d');
            $request['data_impressao'] = Carbon::parse($request->data_impressao)->format('Y-m-d');
            $request['data_maxima_apoio_cotacao'] = Carbon::parse($request->data_maxima_apoio_cotacao)->format('Y-m-d');
         
            $dados = collect($request->validated());

          //  dd($dados);
           
            $solicitacaoCompras = $instituicao->solicitacaoCompras()->create($dados->except('produtos')->toArray());
            $solicitacaoCompras->overwrite($solicitacaoCompras->solicitacaoComprasProdutos(), $dados->get('produtos'));

            //  $solicitacaoCompras->criarLogCadastro($usuario_logado, $instituicao->id);
        });

        return redirect()->route('instituicao.solicitacaoCompras.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Solicitação de Compras criado com sucesso!'
        ]);
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, InstituicaoSolicitacaoCompra $solicitacao_compras)
    {  
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        $setores = $instituicao->setoresExame()->get();
        $motivoPedidos = $instituicao->motivo_pedidos()->get();
        $compradores = $instituicao->compradores()->get();
        $estoques = $instituicao->estoques()->get();
        $produtos = [];
        if(!empty(old('produtos'))) {
            foreach(old('produtos') as $produto_old) {
                $produto = Produto::find($produto_old['id'] ?? 0);
                if(!empty($produto)) {
                    array_push($produtos, [
                        'id' => $produto_old['id'],
                        'classe' => $produto->classe()->first()->descricao,
                        'unidade' => $produto->unidade()->first()->descricao,
                        'descricao' => $produto->descricao,
                        'quantidade' => $produto_old['quantidade'] ?? 0
                    ]);
                }
            }
        }else {
          //  dd($solicitacao_compras->solicitacaoComprasProdutos()->get());
            $produtos = collect($solicitacao_compras->solicitacaoComprasProdutos()->get())
                ->map(function($item) {
                    $produto = Produto::find($item->produto_id);
                    return [
                        'id' => $item->produtos_id,
                    //    'produto' => $item->produto->descricao,
                   //     'fornecedor' => $item->pessoa()->firt()->nome,
                   //     'qtd_solicitada' => $item->qtd_solicitada,
                   //    'oferta_max' => $item->oferta_max
                    ];
                });
             //   dd($produtos);    
        }  
       
        return view('instituicao.solicitacao_compras/editar', compact('solicitacao_compras', 'setores', 'motivoPedidos', 'compradores', 'estoques', 'produtos'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSolicitacaoComprasRequest $request, InstituicaoSolicitacaoCompra $solicitacao_compras)
    { 
        $instituicaoId = $request->session()->get('instituicao');
        $id = $solicitacao_compras->id;

        $dados = $request->validated();
        if($request->urgente == null){
            $dados['urgente'] = 0;
        }
        $dados['data_solicitacao'] = Carbon::parse($request->data_solicitacao)->format('Y-m-d');
        $dados['data_maxima'] = Carbon::parse($request->data_maxima)->format('Y-m-d');
        $dados['data_impressao'] = Carbon::parse($request->data_impressao)->format('Y-m-d');
        $dados['data_maxima_apoio_cotacao'] = Carbon::parse($request->data_maxima_apoio_cotacao)->format('Y-m-d');

        DB::transaction(function () use ($request, $solicitacao_compras, $dados, $instituicaoId){
            $solicitacao_compras->update($dados);

            $usuario_logado = $request->user('instituicao');

          //  $solicitacao_compras->criarLogEdicao($usuario_logado);
        });

        return redirect()->route('instituicao.solicitacaoCompras.edit', [$solicitacao_compras])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Solicitação de compras atualizado com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy(Request $request, InstituicaoSolicitacaoCompra $solicitacao_compras)
    {
        DB::transaction(function () use ($request, $solicitacao_compras){
            $solicitacao_compras->delete();

            return $solicitacao_compras;
        });

        return redirect()->route('instituicao.solicitacaoCompras.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Solicitação de compras excluído com sucesso!'
        ]);
    }
}
