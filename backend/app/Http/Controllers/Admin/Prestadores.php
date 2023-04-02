<?php

namespace App\Http\Controllers\Admin;

use App\Convenio;
use App\Especialidade;
use App\ConveniosProcedimentos;
use App\InstituicaoProcedimentos;
use App\ProcedimentosConveniosInstituicoesPrestadores;
use App\Procedimento;
use App\PrestadorVinculo;
use Carbon\Carbon;
use App\DocumentoPrestador;
use App\Http\Controllers\Controller;
use App\Http\Requests\Prestadores\AdminCriarPrestadorRequest;
use App\Http\Requests\Prestadores\AdminEditarPrestadorRequest;
use App\Instituicao;
use App\InstituicoesPrestadores;
use App\Prestador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class Prestadores extends Controller
{
    //
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('habilidade_admin', 'visualizar_prestador');

        return view('admin.prestadores/lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('habilidade_admin', 'cadastrar_prestador');

        $especialidades = Especialidade::all();

        return view('admin.prestadores/criar', [
            'especialidades' => $especialidades,
            'opcoes_sexo' => Prestador::opcoes_sexo
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AdminCriarPrestadorRequest $request)
    {
        $this->authorize('habilidade_admin', 'cadastrar_prestador');
        $dados = $request->all();
        DB::transaction(function () use ($request, $dados) {
            $prestador = Prestador::create(collect($dados)->except('personalidade')->toArray());
            if ($dados['personalidade'] == 1)
                $prestador->prestadorVinculos()->create(['vinculo_id' => 5]);
            else
                $prestador->prestadorVinculos()->create(['vinculo_id' => 6]);
            if (isset($dados['documentos'])) {
                for ($i = 0; $i < count($dados['documentos']); $i++) {
                    $documento = $dados['documentos'][$i];
                    $arquivo = $documento['arquivo'];
                    $arquivo_path = "documentos/prestadores/prestador" . "_" . "$prestador->id" . "/";
                    $current_time = Carbon::now()->timestamp;
                    $arquivo_nome = $arquivo->getClientOriginalName();
                    $arquivo_new_nome = "$current_time" . "_" . "$arquivo_nome";
                    $upload = $arquivo->storeAs($arquivo_path, $arquivo_new_nome, 'public');
                    if (!$upload) {
                        return redirect()->back()->with('message', [
                            'icon' => 'error',
                            'title' => 'Falha.',
                            'text' => 'Falha ao fazer upload de arquivo'
                        ]);
                    }
                    DocumentoPrestador::create([
                        'file_path_name' => "$arquivo_path" . "$arquivo_new_nome",
                        'tipo' => $documento['tipo'],
                        'descricao' => $documento['descricao'],
                        'prestador_id' => $prestador->id,
                    ]);
                }
            }
            $prestador->criarLogCadastro($request->user('admin'));
        });
        // Exemplo customizando
        return redirect()->route('prestadores.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Prestador criado com sucesso!'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ComercialUsuario  $comercialusuario
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Prestador $prestadore)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ComercialUsuario  $comercialusuario
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Prestador $prestadore)
    {
        $this->authorize('habilidade_admin', 'editar_prestador');
        return view('admin/prestadores/editar', [
            'prestadore' => $prestadore,
            'opcoes_sexo' => Prestador::opcoes_sexo
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ComercialUsuario  $comercialusuario
     * @return \Illuminate\Http\Response
     */
    public function update(AdminEditarPrestadorRequest $request, Prestador $prestadore)
    {
        $this->authorize('habilidade_admin', 'editar_prestador');

        $dados = $request->validated();

        DB::transaction(function () use ($prestadore, $request, $dados) {

            $prestadore->fill(collect($dados)->except('personalidade')->toArray());

            $prestadore->prestadorVinculos()->whereIn('vinculo_id', [5, 6])->delete();
            if ($dados['personalidade'] == 1) {
                $prestadore->prestadorVinculos()->create(['vinculo_id' => 5]);
            } else {
                $prestadore->prestadorVinculos()->create(['vinculo_id' => 6]);
            }

            $prestadore->update();

            $prestadore->criarLogEdicao($request->user('admin'));

            return $prestadore;
        });

        return redirect()->route('prestadores.edit', [$prestadore])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Prestador atualizado com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ComercialUsuario  $comercialusuario
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Prestador $prestadore)
    {
        $this->authorize('habilidade_admin', 'excluir_prestador');

        if ($prestadore->prestadoresInstituicoes()->count() > 0) {
            return redirect()->route('prestadores.index')->with('mensagem', [
                'icon' => 'error',
                'title' => 'Falha.',
                'text' => 'Prestador possui instiuição vinculada!'
            ]);
        }

        $usuario_logado = $request->user('admin');

        DB::transaction(function () use ($prestadore, $usuario_logado) {

            $prestadore->delete();

            $prestadore->criarLogExclusao($usuario_logado);

            foreach ($prestadore->documentos()->get() as $documento) {

                Storage::disk('public')->delete($documento->file_path_name);

                $documento->delete();

                $documento->criarLogExclusao($usuario_logado);
            }
        });
        //return redirect()->route('usuarios.index')->with('mensagem', 'Excluído com sucesso');
        return redirect()->route('prestadores.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Prestador excluído com sucesso!'
        ]);
    }

    public function getPrestador(Request $request)
    {
        $prestador = null;
        if ($request->documento == 'cpf') $prestador = Prestador::where('cpf', $request->valor)->first();
        if ($request->documento == 'cnpj') $prestador = Prestador::where('cnpj', $request->valor)->first();
        $response = null;
        ($prestador) ? $response = ['status' => 0, 'data' => $prestador] : $response = ['status' => 1];
        return response()->json($response);
    }
}
