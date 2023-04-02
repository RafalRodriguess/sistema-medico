<?php

namespace App\Http\Controllers\Admin;

use App\Habilidade;
use App\HabilidadeGrupo;
use App\Http\Requests\Administradores\CriarAdministradorRequest;
use App\Http\Requests\Administradores\EditarAdministradorRequest;
use App\Administrador;
use App\PerfilUsuario;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Administradores\UpdateAdministradorHabilidadesRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Administradores extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('habilidade_admin', 'visualizar_administrador');

        return view('admin.administradores/lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('habilidade_admin', 'cadastrar_administrador');
        $perfis = PerfilUsuario::all();
        return view('admin.administradores/criar', \compact('perfis'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CriarAdministradorRequest $request)
    {
        $this->authorize('habilidade_admin', 'cadastrar_administrador');
        $dados = $request->validated();

        if($request->hasFile('foto')){
            $cpf = preg_replace('/[^0-9]/', '', $dados['cpf']);
            $caminho = "/administradores/{$cpf}";
            $caminhoCloud = $request->foto->storePublicly($caminho, "cloud");
            $dados['foto'] = $caminhoCloud;
        }

        DB::transaction(function () use ($request, $dados) {
            $administrador = Administrador::create($dados);

            $administrador_logado = $request->user('admin');
            $administrador->criarLogCadastro(
                $administrador_logado
            );

            return $administrador;
          });



        // return redirect()->route('administradores.index')->with('mensagem', 'SAlvo com sucesso');

        // Exemplo customizando
        return response()->json([
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Usuário criado com sucesso!'
        ]);
        // return redirect()->route('administradores.index')->with('mensagem', [
        //     'icon' => 'success',
        //     'title' => 'Sucesso.',
        //     'text' => 'Usuário criado com sucesso!'
        // ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\administrador  $administrador
     * @return \Illuminate\Http\Response
     */
    public function show(Administrador $administrador)
    {
        // Carregar relacoes
        // $model->load()

        $administrador->load('perfil');
        dd($administrador->toArray());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\administrador  $administrador
     * @return \Illuminate\Http\Response
     */
    public function edit(Administrador $administrador)
    {
        $this->authorize('habilidade_admin', 'editar_administrador');
        $perfis = PerfilUsuario::all();
        return view('admin.administradores/editar', \compact('perfis', 'administrador'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\administrador  $administrador
     * @return \Illuminate\Http\Response
     */
    public function update(EditarAdministradorRequest $request, Administrador $administrador)
    {
        $this->authorize('habilidade_admin', 'editar_administrador');
        $dados = $request->validated();
        if (!$dados['password']) {
            unset($dados['password']);
        }

        if($request->hasFile('foto')){
            Storage::cloud()->delete($administrador->foto);

            $cpf = preg_replace('/[^0-9]/', '', $dados['cpf']);
            $caminho = "/administradores/{$cpf}";
            $caminhoCloud = $request->foto->storePublicly($caminho, "cloud");
            $dados['foto'] = $caminhoCloud;
        }

        DB::transaction(function () use ($request, $administrador, $dados) {
            $administrador->update($dados);

            $administrador_logado = $request->user('admin');
            $administrador->criarLogEdicao(
              $administrador_logado
            );

            return $administrador;
          });

        return response()->json([
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Usuário atualizado com sucesso!'
        ]);
        //return redirect()->route('administradores.edit', [$administrador])->with('mensagem', 'Salvo com sucesso');
        // return redirect()->route('administradores.edit', [$administrador])->with('mensagem', [
        //     'icon' => 'success',
        //     'title' => 'Sucesso.',
        //     'text' => 'Usuário atualizado com sucesso!'
        // ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\administrador  $administrador
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,Administrador $administrador)
    {
        $this->authorize('habilidade_admin', 'excluir_administrador');
        DB::transaction(function () use ($request, $administrador) {
            $administrador->delete();

            $administrador_logado = $request->user('admin');
            $administrador->criarLogExclusao(
              $administrador_logado
            );

            return $administrador;
        });


        //return redirect()->route('administradores.index')->with('mensagem', 'Excluído com sucesso');
        return redirect()->route('administradores.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Usuário excluído com sucesso!'
        ]);
    }

    public function editHabilidades(Request $request, Administrador $administrador)
    {
        $this->authorize('habilidade_admin', 'habilidades_administrador');
        $habilidades = HabilidadeGrupo::with("habilidades")->get();
        $administrador->load([
            "habilidades",
            "perfil",
            "perfil.habilidades",
        ]);

        return view('admin.administradores.habilidades', \compact('administrador', 'habilidades'));
    }

    public function updateHabilidades(UpdateAdministradorHabilidadesRequest $request, Administrador $administrador)
    {
        $this->authorize('habilidade_admin', 'habilidades_administrador');
        $habilidades = collect($request->validated()['habilidades'])
            ->filter(function ($habilidade) {
                return !is_null($habilidade);
            })
            ->map(function ($habilitado) {
                return [
                    'habilitado' => $habilitado,
                ];
            });

        DB::transaction(function () use ($administrador, $habilidades, $request){
            $administrador->habilidades()->sync($habilidades);

            $administrador_logado = $request->user('admin');

            $administrador->criarLog(
                $administrador_logado,
                'Alteração habilidades do usuário',
                $habilidades
            );

            return $administrador;
        });


        return redirect()->route('administradores.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Habilidades do usuário editada com sucesso!'
        ]);
    }
}
