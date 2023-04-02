<?php

namespace App\Http\Controllers\Comercial;

use App\Comercial;
use App\ComercialHabilidadeGrupo;
use App\ComercialUsuario;
use App\Http\Controllers\Controller;
use App\Http\Requests\ComercialUsuarios\UpdateUsuarioComercialHabilidadesRequest;
use App\Http\Requests\UsuariosComercial\CriarUsuariosEstabelecimentoRequest;
use App\Http\Requests\UsuariosComercial\EditarUsuariosEstabelecimentoRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Image;

class Usuarios_comercial extends Controller
{
    //
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('habilidade_comercial_sessao', 'visualizar_usuario');

        return view('comercial.usuarios_comercial/lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('habilidade_comercial_sessao', 'cadastrar_usuario');
        $comercial = Comercial::find($request->session()->get('comercial'));

        return view('comercial.usuarios_comercial/criar', \compact('comercial'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CriarUsuariosEstabelecimentoRequest $request)
    {
        $this->authorize('habilidade_comercial_sessao', 'cadastrar_usuario');
        $usuario_logado = $request->user('comercial');
        $comercial = Comercial::find($request->session()->get('comercial'));

        $dados = $request->validated();

        // $cpf = preg_replace('/[^0-9]/', '', $request->input('cpf'));
        // $cnpj = preg_replace('/[^0-9]/', '', $comercial->cnpj);
        if ($request->hasFile('imagem')) {



            $random = Str::random(20);
            $imageName = $random. '.' . $request->imagem->extension();
            $imagem_original = $request->imagem->storeAs("/usuario_comerciais/{$random}", $imageName, config('filesystems.cloud'));
            $dados['foto'] = $imagem_original;


            $ImageResize = Image::make($request->imagem);

            $image300pxName = "/usuario_comerciais/{$random}/300px-{$imageName}";
            $image200pxName = "/usuario_comerciais/{$random}/200px-{$imageName}";
            $image100pxName = "/usuario_comerciais/{$random}/100px-{$imageName}";

            $ImageResize->resize(300, 300, function($constraint) {
                $constraint->aspectRatio();
            });
            Storage::cloud()->put($image300pxName, (string) $ImageResize->encode());

            $ImageResize->resize(200, 200, function($constraint) {
                $constraint->aspectRatio();
            });
            Storage::cloud()->put($image200pxName, (string) $ImageResize->encode());

            $ImageResize->resize(100, 100, function($constraint) {
                $constraint->aspectRatio();
            });
            Storage::cloud()->put($image100pxName, (string) $ImageResize->encode());

            // $caminho = "/usuario_comerciais";
            // $caminhoCloud = $request->imagem->storePublicly($caminho, "public");
            // $dados['foto'] = $caminhoCloud;
        }

        if ($dados['usuario_id']) {

            DB::transaction(function () use ($request, $comercial, $dados) {
                $comercial->comercialUsuarios()->attach($request->input('usuario_id'));

                $usuario_logado = $request->user('comercial');
                $comercial->criarLog(
                    $usuario_logado,
                    "Associação com o comercial - #{$comercial->id} {$comercial->nome_fantasia}",
                    $dados
                );

                return $dados;
            });

        }else{
            DB::transaction(function () use ($comercial, $usuario_logado, $dados){
                $usuario_comercial = $comercial->comercialUsuarios()->create($dados);

                $usuario_comercial->criarLogCadastro(
                    $usuario_logado,
                    $comercial->id
                );

                $usuario_comercial->criarLog(
                    $usuario_logado,
                    "Associação com o comercial - #{$comercial->id} {$comercial->nome_fantasia}",
                    $usuario_comercial
                );

                return $usuario_comercial;
            });
        }

        return response()->json([
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Usuário criado com sucesso!'
        ]);

        // Exemplo customizando
        // return redirect()->route('comercial.comerciais_usuarios.index')->with('mensagem', [
        //     'icon' => 'success',
        //     'title' => 'Sucesso.',
        //     'text' => 'Usuário criado com sucesso!'
        // ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ComercialUsuario  $comercialusuario
     * @return \Illuminate\Http\Response
     */
    public function show(ComercialUsuario $comercialUsuario)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ComercialUsuario  $comercialusuario
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, ComercialUsuario $comercialUsuario)
    {
        $this->authorize('habilidade_comercial_sessao', 'editar_usuario');
        $usuario_logado = $request->user('comercial');
        $comercial = Comercial::find($request->session()->get('comercial'));

        abort_unless($comercialUsuario->comercial->where('id', $request->session()->get('comercial'))->isNotEmpty(), 403);

        return view('comercial.usuarios_comercial/editar', \compact('comercialUsuario'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ComercialUsuario  $comercialusuario
     * @return \Illuminate\Http\Response
     */
    public function update(EditarUsuariosEstabelecimentoRequest $request, ComercialUsuario $comercialUsuario)
    {
        $this->authorize('habilidade_comercial_sessao', 'editar_usuario');
        $usuario_logado = $request->user('comercial');
        $comercial = Comercial::find($request->session()->get('comercial'));

        abort_unless($comercialUsuario->comercial->where('id', $request->session()->get('comercial'))->isNotEmpty(), 403);

        $dados = $request->validated();
        if (!$dados['password']) {
            unset($dados['password']);
        }

        if ($request->hasFile('imagem')) {

            if($comercialUsuario->foto){
                $pasta = Str::of($comercialUsuario->foto)->explode('/');
                Storage::cloud()->deleteDirectory($pasta[0].'/'.$pasta[1]);
            }

            $random = Str::random(20);
            $imageName = $random. '.' . $request->imagem->extension();
            $imagem_original = $request->imagem->storeAs("/usuario_comerciais/{$random}", $imageName, config('filesystems.cloud'));
            $dados['foto'] = $imagem_original;


            $ImageResize = Image::make($request->imagem);

            $image300pxName = "/usuario_comerciais/{$random}/300px-{$imageName}";
            $image200pxName = "/usuario_comerciais/{$random}/200px-{$imageName}";
            $image100pxName = "/usuario_comerciais/{$random}/100px-{$imageName}";

            $ImageResize->resize(300, 300, function($constraint) {
                $constraint->aspectRatio();
            });
            Storage::cloud()->put($image300pxName, (string) $ImageResize->encode());

            $ImageResize->resize(200, 200, function($constraint) {
                $constraint->aspectRatio();
            });
            Storage::cloud()->put($image200pxName, (string) $ImageResize->encode());

            $ImageResize->resize(100, 100, function($constraint) {
                $constraint->aspectRatio();
            });
            Storage::cloud()->put($image100pxName, (string) $ImageResize->encode());


            // Storage::disk('public')->delete($comercialUsuario->foto);

            // $caminho = "/usuario_comerciais";
            // $caminhoCloud = $request->imagem->storePublicly($caminho, "public");
            // $dados['foto'] = $caminhoCloud;
        }

        DB::transaction(function () use ($comercialUsuario, $dados, $usuario_logado, $comercial){
            $comercialUsuario->update($dados);

            $comercialUsuario->criarLogEdicao(
                $usuario_logado,
                $comercial->id
            );

            return $comercialUsuario;
        });

        return response()->json([
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Usuário atualizado com sucesso!'
        ]);

        // return redirect()->route('comercial.comerciais_usuarios.edit', [$comercialUsuario])->with('mensagem', [
        //     'icon' => 'success',
        //     'title' => 'Sucesso.',
        //     'text' => 'Usuário atualizado com sucesso!'
        // ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ComercialUsuario  $comercialusuario
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, ComercialUsuario $comercialUsuario)
    {
        $this->authorize('habilidade_comercial_sessao', 'excluir_usuario');
        $usuario_logado = $request->user('comercial');
        $comercial = Comercial::find($request->session()->get('comercial'));

        abort_unless($comercialUsuario->comercial->where('id', $request->session()->get('comercial'))->isNotEmpty(), 403);

        DB::transaction(function () use ($comercialUsuario, $usuario_logado, $comercial){

            DB::table('comercial_usuario_has_habilidades')->where([
                ['comercial_id', $comercial->id],
                ['usuario_id', $comercialUsuario->id]
            ])->delete();

            $comercialUsuario->comercial()->detach($comercial->id);
            // $comercialUsuario->delete();

            $comercialUsuario->criarLog(
                $usuario_logado,
                "Desassociação com o comercial - #{$comercial->id} {$comercial->nome_fantasia}",
                $comercialUsuario
            );

            return $comercialUsuario;
        });


        //return redirect()->route('usuarios.index')->with('mensagem', 'Excluído com sucesso');
        return redirect()->route('comercial.comerciais_usuarios.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Usuário excluído com sucesso!'
        ]);
    }

    public function editHabilidade(Request $request, ComercialUsuario $comercialUsuario)
    {
        $this->authorize('habilidade_comercial_sessao', 'habilidades_usuario');
        $usuario_logado = $request->user('comercial');
        $comercial = Comercial::find($request->session()->get('comercial'));

        abort_unless($comercialUsuario->comercial->where('id', $request->session()->get('comercial'))->isNotEmpty(), 403);

        $habilidades = ComercialHabilidadeGrupo::with('comercialHabilidades')->get();
        $comercialUsuario->load([
            'comercialHabilidades',
        ]);

        return view('comercial.usuarios_comercial.habilidades', \compact('habilidades', 'comercialUsuario', 'comercial'));
    }

    public function updateHabilidade(UpdateUsuarioComercialHabilidadesRequest $request, ComercialUsuario $comercialUsuario)
    {
        $this->authorize('habilidade_comercial_sessao', 'habilidades_usuario');
        $usuario_logado = $request->user('comercial');
        $comercial = Comercial::find($request->session()->get('comercial'));

        abort_unless($comercialUsuario->comercial->where('id', $request->session()->get('comercial'))->isNotEmpty(), 403);

        $habilidades = collect($request->validated()['habilidades'])
            ->filter(function ($habilidade) {
                return !is_null($habilidade);
            })
            ->map(function($habilitado) use ($comercial){
                return [
                    'habilitado' => $habilitado,
                    'comercial_id' => $comercial->id
                ];
            });

        DB::transaction(function () use ($comercialUsuario, $habilidades, $usuario_logado, $comercial){
            DB::table('comercial_usuario_has_habilidades')->where([
                ['comercial_id', $comercial->id],
                ['usuario_id', $comercialUsuario->id]
            ])->delete();

            $comercialUsuario->comercialHabilidades()->attach($habilidades);

            $comercialUsuario->criarLog(
                $usuario_logado,
                "Alteração habilidades do usuario de comercial #{$comercial->id} {$comercial->nome_fantasia}",
                $habilidades,
                $comercial->id
            );

            return $comercialUsuario;
        });

        return redirect()->route('comercial.comerciais_usuarios.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Habilidades do usuário editada com sucesso!'
        ]);

    }

    public function verificaCpfExistente(Request $request)
    {
        $cpf = $request->input('cpf');
        $comercial_usuario = ComercialUsuario::where('cpf', $cpf)->get();
        // $comercial_usuario[0]->foto = Storage::cloud()->url($comercial_usuario[0]->foto);
        return $comercial_usuario->toJson();
    }

}
