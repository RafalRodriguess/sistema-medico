<?php

namespace App\Http\Controllers\Comercial;

use App\Comercial;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProdutoPerguntas\CriarProdutoPerguntaRequest;
use App\Produto;
use App\ProdutoPergunta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProdutoPerguntas extends Controller
{
    //
    public function index(Produto $produto)
    {
        $this->authorize('habilidade_comercial_sessao', 'visualizar_perguntas');
    
        return view('comercial.perguntas.lista', [
            'produto' => $produto,
        ]);
    }

    public function create(Produto $produto)
    {
        $this->authorize('habilidade_comercial_sessao', 'cadastrar_perguntas');

        return view('comercial.perguntas.criar', \compact('produto'));
    }

    public function store(CriarProdutoPerguntaRequest $request, Produto $produto)
    {
        $this->authorize('habilidade_comercial_sessao', 'cadastrar_perguntas');
        $usuario_logado = $request->user('comercial');
        $comercial = Comercial::find($request->session()->get('comercial'));
        
        $dados = $request->validated();
        
        $dados['obrigatorio'] = $request->boolean('obrigatorio');

        $pergunta_dados = [
            'titulo' => $dados['titulo'],
            'obrigatorio' => $dados['obrigatorio'],
            'tipo' => $dados['tipo'],
            'quantidade_maxima' => $dados['quantidade_maxima'],
            'quantidade_minima' => $dados['quantidade_minima'],
        ];

        $alternativas_dados = [];

        if ($dados['tipo'] != 'Texto') {

            foreach ($dados['alternativa'] as $key => $value) {
                $alternativas_dados[$key]['alternativa'] = $value;
            }
            foreach ($dados['preco'] as $key => $value) {
                $alternativas_dados[$key]['preco'] = $value;
            }
            foreach ($dados['quantidade_maxima_itens'] as $key => $value) {
                $alternativas_dados[$key]['quantidade_maxima_itens'] = $value;
            }
        }

        DB::transaction(function () use ($produto, $request, $pergunta_dados, $comercial, $alternativas_dados, $dados){
            $pergunta = $produto->produto_perguntas()->create($pergunta_dados);

            if ($dados['tipo'] != 'Texto') {
                foreach ($alternativas_dados as $key => $value) {
                    $alternativa = $pergunta->produto_pergunta_alternativas()->create($value);
                }
            }
            
            $usuario_logado = $request->user('comercial');
            $produto->criarLogCadastro(
                $usuario_logado,
                $comercial->id
            );

            return $pergunta;
        });

        return redirect()->route('comercial.produtoPerguntas.index', [$produto])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Pergunta criada com sucesso!'
        ]);
    }

    public function edit(Request $request, Produto $produto, ProdutoPergunta $pergunta)
    {
        $this->authorize('habilidade_comercial_sessao', 'editar_perguntas');
        $comercial = Comercial::find($request->session()->get('comercial'));
        abort_unless($produto->comercial_id === $comercial->id, 403);

        abort_unless($produto->id === $pergunta->produto_id, 403);

        $alternativas = null;
        if ($pergunta->tipo != "Texto") 
        {
            $alternativas = $pergunta->produto_pergunta_alternativas()->where('produto_pergunta_id', $pergunta->id)->get();
        }

        return view('comercial.perguntas.editar', \compact('produto','pergunta','alternativas'));
    }

    public function update(CriarProdutoPerguntaRequest $request, Produto $produto, ProdutoPergunta $pergunta)
    {
        $this->authorize('habilidade_comercial_sessao', 'editar_perguntas');
        abort_unless($produto->id === $pergunta->produto_id, 403);

        $usuario_logado = $request->user('comercial');

        $comercial = Comercial::find($request->session()->get('comercial'));
        abort_unless($produto->comercial_id === $comercial->id, 403);

        $dados = $request->validated();
        
        $dados['obrigatorio'] = $request->boolean('obrigatorio');

        $pergunta_dados = [
            'titulo' => $dados['titulo'],
            'obrigatorio' => $dados['obrigatorio'],
            'tipo' => $dados['tipo'],
            'quantidade_maxima' => $dados['quantidade_maxima'],
            'quantidade_minima' => $dados['quantidade_minima'],
        ];

        $alternativas_dados = [];

        if ($dados['tipo'] != 'Texto') {

            foreach ($dados['alternativa'] as $key => $value) {
                $alternativas_dados[$key]['alternativa'] = $value;
            }
            foreach ($dados['preco'] as $key => $value) {
                $alternativas_dados[$key]['preco'] = $value;
            }
            foreach ($dados['quantidade_maxima_itens'] as $key => $value) {
                $alternativas_dados[$key]['quantidade_maxima_itens'] = $value;
            }
        }

        DB::transaction(function () use ($pergunta, $request, $pergunta_dados, $comercial, $alternativas_dados, $dados, $usuario_logado){
            $pergunta->update($pergunta_dados);  

            if ($dados['tipo'] != 'Texto') {
                $pergunta->produto_pergunta_alternativas()->delete();
                foreach ($alternativas_dados as $key => $value) {
                    $alternativa = $pergunta->produto_pergunta_alternativas()->create($value);
                }
            }
            
            $usuario_logado = $request->user('comercial');
            $pergunta->criarLogEdicao($usuario_logado, $comercial->id);

            return $pergunta;
        });
        
        //return redirect()->route('administradores.edit', [$usuario])->with('mensagem', 'Salvo com sucesso');
        return redirect()->route('comercial.produtoPerguntas.edit', [$produto, $pergunta])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Pergunta atualizada com sucesso!'
        ]);
    }

    public function destroy(Request $request, Produto $produto, ProdutoPergunta $pergunta)
    {
        $this->authorize('habilidade_comercial_sessao', 'excluir_perguntas');
        $usuario_logado = $request->user('comercial');
        $comercialId = $request->session()->get('comercial');
        abort_unless($produto->comercial_id === $comercialId, 403);
        abort_unless($produto->id === $pergunta->produto_id, 403);

        DB::transaction(function () use ($request, $pergunta, $comercialId, $usuario_logado){
            $pergunta->produto_pergunta_alternativas()->delete();
            $pergunta->delete();

            $pergunta->criarLogExclusao(
                $usuario_logado,
                $comercialId
            );

            return $pergunta;
        });
        
    
        return redirect()->route('comercial.produtoPerguntas.index',[$produto])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Pergunta excluída com sucesso!'
        ]);
    }

}
