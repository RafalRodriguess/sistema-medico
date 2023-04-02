<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ComercialUsuarios\CriarComercialUsuarioRequest;
use App\Http\Requests\ComercialUsuarios\EditarComercialUsuarioRequest;
use App\Comercial;
use App\ComercialHabilidadeGrupo;
use App\ComercialUsuario;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ComercialUsuarios\UpdateUsuarioComercialHabilidadesRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Image;

class Comercial_usuarios extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Comercial $comercial)
    {
        $this->authorize('habilidade_admin', 'visualizar_usuario_comercial');

        return view('admin.comercial_usuarios/lista', [
            'comercial' => $comercial,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Comercial $comercial)
    {
        $this->authorize('habilidade_admin', 'cadastrar_usuario_comercial');
        // $comercial = Comercial::all();
        return view('admin.comercial_usuarios/criar', \compact('comercial'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CriarComercialUsuarioRequest $request, Comercial $comercial)
    {
        $this->authorize('habilidade_admin', 'cadastrar_usuario_comercial');

        $dados = $request->validated();

        // $comercial_cloud = Comercial::where('id', $dados['comercial_id'])->get();

        if ($request->hasFile('imagem')) {
            // $cpf = preg_replace('/[^0-9]/', '', $dados['cpf']);
            // $cnpj = preg_replace('/[^0-9]/', '', $comercial_cloud[0]->cnpj);

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
            // $caminhoCloud = $request->imagem->storePublicly($caminho, "cloud");
            // $dados['foto'] = $caminhoCloud;
        }
        // dd($dados);
        if ($dados['usuario_id']) {

            DB::transaction(function () use ($request, $comercial, $dados) {
                $comercial->comercialUsuarios()->attach($request->input('usuario_id'));

                $usuario_logado = $request->user('admin');
                $comercial->criarLog(
                    $usuario_logado,
                    "Associação com o comercial - #{$comercial->id} {$comercial->nome_fantasia}",
                    $dados
                );

                return $dados;
            });

        }else{

            DB::transaction(function () use ($request, $comercial, $dados) {
                $comercialUsuario = $comercial->comercialUsuarios()->create($dados);

                $usuario_logado = $request->user('admin');
                $comercialUsuario->criarLogCadastro(
                    $usuario_logado
                );

                $comercialUsuario->criarLog(
                    $usuario_logado,
                    "Associação com o comercial - #{$comercial->id} {$comercial->nome_fantasia}",
                    $comercialUsuario
                );

                return $comercialUsuario;
            });

        }


        return response()->json([
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Usuário criado com sucesso!'
        ]);
        // return redirect()->route('usuarios.index')->with('mensagem', 'SAlvo com sucesso');

        // Exemplo customizando
        // return redirect()->route('comercial_usuarios.index', [$comercial])->with('mensagem', [
        //     'icon' => 'success',
        //     'title' => 'Sucesso.',
        //     'text' => 'Usuário criado com sucesso!'
        // ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\comercialUsuario  $comercialusuario
     * @return \Illuminate\Http\Response
     */
    public function show(ComercialUsuario $comercialUsuario)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\comercialUsuario  $comercialusuario
     * @return \Illuminate\Http\Response
     */
    public function edit(Comercial $comercial, ComercialUsuario $comercialUsuario)
    {
        $this->authorize('habilidade_admin', 'editar_usuario_comercial');
        // abort_unless($comercial->id === $comercialUsuario->comercial_id, 403);
        // $comercial = Comercial::all();
        return view('admin.comercial_usuarios/editar', \compact('comercial', 'comercialUsuario'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\comercialUsuario  $comercialusuario
     * @return \Illuminate\Http\Response
     */
    public function update(EditarComercialUsuarioRequest $request, Comercial $comercial, ComercialUsuario $comercialUsuario)
    {
        $this->authorize('habilidade_admin', 'editar_usuario_comercial');
        // abort_unless($comercial->id === $comercialUsuario->comercial_id, 403);

        $dados = $request->validated();

        // $comercial_cloud = Comercial::where('id', $dados['comercial_id'])->get();

        // dd($comercialUsuario->foto);

        if ($request->hasFile('imagem')) {
            // Storage::cloud()->delete($comercialUsuario->foto);
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

            // $cpf = preg_replace('/[^0-9]/', '', $comercialUsuario->cpf);
            // $cnpj = preg_replace('/[^0-9]/', '', $comercial_cloud[0]->cnpj);
            // $caminho = "/usuario_comerciais";
            // $caminhoCloud = $request->imagem->storePublicly($caminho, "cloud");
            // $dados['foto'] = $caminhoCloud;
        }

        if (!$dados['password']) {
            unset($dados['password']);
        }

        DB::transaction(function () use ($request, $comercialUsuario, $dados) {
            $comercialUsuario->update($dados);

            $usuario_logado = $request->user('admin');
            $comercialUsuario->criarLogEdicao(
                $usuario_logado
            );

            return $comercialUsuario;
        });

        return response()->json([
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Usuário atualizado com sucesso!'
        ]);

        // return redirect()->route('comercial_usuarios.edit', [$comercial, $comercialUsuario])->with('mensagem', [
        //     'icon' => 'success',
        //     'title' => 'Sucesso.',
        //     'text' => 'Usuário atualizado com sucesso!'
        // ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\comercialUsuario  $comercialusuario
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Comercial $comercial, ComercialUsuario $comercialUsuario)
    {
        $this->authorize('habilidade_admin', 'excluir_usuario_comercial');
        // abort_unless($comercial->id === $comercialUsuario->comercial_id, 403);

        DB::transaction(function () use ($request, $comercialUsuario, $comercial) {

            DB::table('comercial_usuario_has_habilidades')->where([
                ['comercial_id', $comercial->id],
                ['usuario_id', $comercialUsuario->id]
            ])->delete();

            $comercialUsuario->comercial()->detach($comercial->id);

            $usuario_logado = $request->user('admin');
            // $comercialUsuario->criarLogExclusao(
            //     $usuario_logado
            // );

            $comercialUsuario->criarLog(
                $usuario_logado,
                "Desassociação com o comercial - #{$comercial->id} {$comercial->nome_fantasia}",
                $comercialUsuario
            );

            return $comercialUsuario;
        });
        //return redirect()->route('usuarios.index')->with('mensagem', 'Excluído com sucesso');
        return redirect()->route('comercial_usuarios.index', [$comercial])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Usuário excluído com sucesso!'
        ]);
    }

    public function editHabilidade(Request $request, Comercial $comercial, ComercialUsuario $comercialUsuario)
    {
        $this->authorize('habilidade_admin', 'habilidades_usuario_comercial');
        // abort_unless($comercial->id === $comercialUsuario->comercial_id, 403);

        $usuario_logado = $request->user('admin');

        $habilidades = ComercialHabilidadeGrupo::with('comercialHabilidades')->get();
        $comercialUsuario->load([
            'comercialHabilidades',
        ]);

        return view('admin.comercial_usuarios.habilidades', \compact('habilidades', 'comercialUsuario', 'comercial'));
    }

    public function updateHabilidade(UpdateUsuarioComercialHabilidadesRequest $request, Comercial $comercial ,comercialUsuario $comercialUsuario)
    {
        $this->authorize('habilidade_admin', 'habilidades_usuario_comercial');
        // abort_unless($comercial->id === $comercialUsuario->comercial_id, 403);

        $usuario_logado = $request->user('admin');

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
                $habilidades
            );

            return $comercialUsuario;
        });

        return redirect()->route('comercial_usuarios.index', [$comercial])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Habilidades do usuário de comercial editada com sucesso!'
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
