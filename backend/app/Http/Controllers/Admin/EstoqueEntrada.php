<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\EstoqueEntrada\CriarEstoqueEntradaRequest;
use App\Http\Requests\EstoqueEntrada\EditarEstoqueEntradaRequest;
use App\EstoqueEntradas;
use App\TipoDocumento;
use Illuminate\Support\Facades\DB;


class EstoqueEntrada extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('habilidade_admin', 'visualizar_estoque_entrada');

        return view('admin.estoque_entrada/lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('habilidade_admin', 'cadastrar_estoque_entrada');

        $estoqueEntradas = EstoqueEntradas::all();
        $tiposDocumentos =  DB::table('tipos_documentos')->get();
        $estoques =  DB::table('estoques')->get();
        $pessoas =  DB::table('pessoas')->get();


        return view('admin.estoque_entrada/criar', \compact('estoqueEntradas','tiposDocumentos','estoques','pessoas'));
    }

    public function store(CriarEstoqueEntradaRequest $request)
    {
        $this->authorize('habilidade_admin', 'cadastrar_estoque_entrada');

        $dados = $request->validated();

        $dados['id_tipo_documento'] = $request->id_tipo_documento;
        $dados['id_estoque'] = $request->id_tipo_documento;

        $dados['consignado'] = $request->consignado;
        $dados['contabiliza'] = $request->contabiliza;
        $dados['numero_documento'] = $request->numero_documento;
        $dados['serie'] = $request->serie;
        $dados['id_fornecedor'] = $request->id_fornecedor;
        $dados['data_emissao'] = $request->data_emissao;
        $dados['data_hora_entrada'] = $request->data_hora_entrada;

        DB::transaction(function () use ($request, $dados){
            $EstoreEntradas = EstoqueEntradas::create($dados);

            $usuario_logado = $request->user('admin');
            $EstoreEntradas->criarLogCadastro($usuario_logado);

            return $EstoreEntradas;
        });

        return redirect()->route('estoque_entrada.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Inserido com sucesso!'
        ]);
    }

    public function edit(Request $estoqueEntradaRequest)
    {
        $this->authorize('habilidade_admin', 'editar_estoque_entrada');

        $estoqueEntrada = EstoqueEntradas::where('id',$estoqueEntradaRequest->id)->first();
        $tiposDocumentos =  DB::table('tipos_documentos')->get();
        $estoques =  DB::table('estoques')->get();
        $pessoas =  DB::table('pessoas')->get();

        return view('admin.estoque_entrada/editar', \compact('tiposDocumentos','estoqueEntrada','estoques','pessoas'));
    }

    public function update(EditarEstoqueEntradaRequest $request)
    {
        $this->authorize('habilidade_admin', 'editar_estoque_entrada');

        $estoqueEntradas = EstoqueEntradas::find($request->id);

        $dados = $request->validated();
        $estoqueEntradas->id = $request['id'];
        $estoqueEntradas->id_tipo_documento = $request['id_tipo_documento'];
        $estoqueEntradas->id_estoque = $request['id_estoque'];
        $estoqueEntradas->consignado = $request['consignado'];
        $estoqueEntradas->contabiliza = $request['contabiliza'];
        $estoqueEntradas->numero_documento = $request['numero_documento'];
        $estoqueEntradas->serie = $request['serie'];
        $estoqueEntradas->id_fornecedor = $request['id_fornecedor'];
        $estoqueEntradas->data_emissao = $request['data_emissao'];
        $estoqueEntradas->data_hora_entrada = $request['data_hora_entrada'];

        DB::transaction(function () use ($estoqueEntradas,$dados){
            $estoqueEntradas->update($dados);

            return $estoqueEntradas;
        });

        return redirect()->route('estoque_entrada.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Editado com sucesso!'
        ]);

    }


    public function destroy(Request $request)
    {
        $this->authorize('habilidade_admin', 'excluir_estoque_entrada');

        $estoqueEntradas = EstoqueEntradas::find($request->id);
        DB::transaction(function () use ($estoqueEntradas,$request){
            $estoqueEntradas->delete();

            $usuario_logado = $request->user('admin');
            $estoqueEntradas->criarLogExclusao(
              $usuario_logado
            );

            return $estoqueEntradas;
        });

        return redirect()->route('estoque_entrada.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Excluído com sucesso!'
        ]);
    }

}
