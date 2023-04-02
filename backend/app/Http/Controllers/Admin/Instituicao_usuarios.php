<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\InstituicaoUsuario\CriarUsuarioInstituicaoRequest;
use App\Http\Requests\InstituicaoUsuario\EditarUsuarioInstituicaoRequest;
use App\Http\Requests\InstituicaoUsuario\HabilidadeUsuarioInstituicaoRequest;
use App\Instituicao;
use App\InstituicaoHabilidade;
use App\InstituicaoHabilidadeGrupo;
use App\InstituicaoUsuario;
use App\PerfilInstituicaoHabilidade;
use App\PerfilUsuarioInstituicao;
use App\RamoHabilidade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Image;

class Instituicao_usuarios extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Instituicao $instituicao)
    {
        $this->authorize('habilidade_admin', 'visualizar_usuario_instituicao');
        return view('admin.instituicao_usuarios/lista', [
            'instituicao' => $instituicao,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Instituicao $instituicao)
    {
        $this->authorize('habilidade_admin', 'cadastrar_usuario_instituicao');
        $perfil = PerfilUsuarioInstituicao::get();

        return view('admin.instituicao_usuarios/criar', \compact('instituicao', 'perfil'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CriarUsuarioInstituicaoRequest $request, Instituicao $instituicao)
    {
        $this->authorize('habilidade_admin', 'cadastrar_usuario_instituicao');

        $dados = $request->validated();

        $habilidadesPerfil = PerfilInstituicaoHabilidade::where('perfil_id', $dados['perfil_id'] )->get();
        $habilidadesPerfil = array_column($habilidadesPerfil->toArray(), 'habilidade_id');

        $habilidades = InstituicaoHabilidade::get();

        $habSelect = array();

        // $instituicao_cloud = Instituicao::where('id', $dados['instituicao_id'])->get();

        if ($request->hasFile('imagem')) {
            // $cpf = preg_replace('/[^0-9]/', '', $dados['cpf']);
            // $cnpj = preg_replace('/[^0-9]/', '', $instituicao_cloud[0]->cnpj);

            $random = Str::random(20);
            $imageName = $random. '.' . $request->imagem->extension();
            $imagem_original = $request->imagem->storeAs("/usuario_instituicoes/{$random}", $imageName, config('filesystems.cloud'));
            $dados['foto'] = $imagem_original;


            $ImageResize = Image::make($request->imagem);

            $image300pxName = "/usuario_instituicoes/{$random}/300px-{$imageName}";
            $image200pxName = "/usuario_instituicoes/{$random}/200px-{$imageName}";
            $image100pxName = "/usuario_instituicoes/{$random}/100px-{$imageName}";

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

            // $caminho = "/usuario_instituicoes";
            // $caminhoCloud = $request->imagem->storePublicly($caminho, "cloud");
            // $dados['foto'] = $caminhoCloud;
        }

        if ($dados['usuario_id']) {

            foreach($habilidades as $k => $v){
                $habSelect[] = [
                    'usuario_id' => $dados['usuario_id'],
                    'habilidade_id' => $v->id,
                    'habilitado' => (in_array($v->id, $habilidadesPerfil)) ? 1 : 0,
                    'instituicao_id' => $instituicao->id,
                ];
    
            }

            DB::transaction(function () use ($request, $instituicao, $dados, $habSelect) {
                $dadosUsuario = [
                    "usuario_id" => $request->input('usuario_id'),
                    "perfil_id" => $request->input('perfil_id')
                ];
                $instituicao->instituicaoUsuarios()->attach([$dadosUsuario]);
                $instituicao_usuarios = $instituicao->instituicaoUsuarios()->find($dados['usuario_id']);
                // dd($instituicao_usuarios->toArray());
                $instituicao_usuarios->instituicaoHabilidades()->attach($habSelect);

                $usuario_logado = $request->user('admin');
                $instituicao->criarLog(
                    $usuario_logado,
                    "Associação com o instituição - #{$instituicao->id} {$instituicao->nome}",
                    $dados
                );

                return $dados;
            });

        }else{
            DB::transaction(function () use ($request, $instituicao, $dados, $habilidades, $habilidadesPerfil) {
                //$instituicaoUsuario = $instituicao->instituicaoUsuarios()->create($dados);
                $usuario_instituicao = InstituicaoUsuario::create($dados);
                $instituicaoUsuario = $usuario_instituicao;

                $dadosUsuario = [
                    "instituicao_id" => $instituicao->id,
                    "usuario_id" => $instituicaoUsuario->id,
                    "perfil_id" => $request->input('perfil_id')
                ];

                $instituicaoUsuario->instituicao()->attach([$dadosUsuario]);
                // $instituicao->instituicaoUsuarios()->attach($dadosUsuario);
                
                foreach($habilidades as $k => $v){
                    $habSelect[] = [
                        'usuario_id' => $instituicaoUsuario->id,
                        'habilidade_id' => $v->id,
                        'habilitado' => (in_array($v->id, $habilidadesPerfil)) ? 1 : 0,
                        'instituicao_id' => $instituicao->id,
                    ];
                }

                $instituicaoUsuario->instituicaoHabilidades()->attach($habSelect);

                $usuario_logado = $request->user('admin');
                $instituicaoUsuario->criarLogCadastro(
                    $usuario_logado
                );

                $instituicaoUsuario->criarLog(
                    $usuario_logado,
                    "Associação com o instituição - #{$instituicao->id} {$instituicao->nome}",
                    $instituicaoUsuario
                );

                return $instituicaoUsuario;
            });

        }

        return response()->json([
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Usuário criado com sucesso!'
        ]);

        // return redirect()->route('usuarios.index')->with('mensagem', 'SAlvo com sucesso');

        // Exemplo customizando
        // return redirect()->route('instituicao_usuarios.index', [$instituicao])->with('mensagem', [
        //     'icon' => 'success',
        //     'title' => 'Sucesso.',
        //     'text' => 'Usuário criado com sucesso!'
        // ]);
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
    public function edit(Instituicao $instituicao, InstituicaoUsuario $instituicaoUsuario)
    {
        $this->authorize('habilidade_admin', 'editar_usuario_instituicao');
        $perfil = PerfilUsuarioInstituicao::get();

        $usuario_perfil = $instituicaoUsuario->instituicao()->where('instituicao_id', $instituicao->id)->first();
        $usuario_perfil = $usuario_perfil->pivot->perfil_id;

        return view('admin.instituicao_usuarios/editar', \compact('instituicao', 'instituicaoUsuario', 'perfil', 'usuario_perfil'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EditarUsuarioInstituicaoRequest $request, Instituicao $instituicao, InstituicaoUsuario $instituicaoUsuario)
    {
        $this->authorize('habilidade_admin', 'editar_usuario_instituicao');

        $dados = $request->validated();
        $habilidadesPerfil = PerfilInstituicaoHabilidade::where('perfil_id', $dados['perfil_id'])->get();
        $habilidadesPerfil = array_column($habilidadesPerfil->toArray(), 'habilidade_id');

        $habilidades = InstituicaoHabilidade::get();

        $habSelect = array();

        if ($request->hasFile('imagem')) {
            // Storage::cloud()->delete($instituicaoUsuario->foto);
            if($instituicaoUsuario->foto){
                $pasta = Str::of($instituicaoUsuario->foto)->explode('/');
                Storage::cloud()->deleteDirectory($pasta[0].'/'.$pasta[1]);
            }
            $random = Str::random(20);
            $imageName = $random. '.' . $request->imagem->extension();
            $imagem_original = $request->imagem->storeAs("/usuario_instituicoes/{$random}", $imageName, config('filesystems.cloud'));
            $dados['foto'] = $imagem_original;


            $ImageResize = Image::make($request->imagem);

            $image300pxName = "/usuario_instituicoes/{$random}/300px-{$imageName}";
            $image200pxName = "/usuario_instituicoes/{$random}/200px-{$imageName}";
            $image100pxName = "/usuario_instituicoes/{$random}/100px-{$imageName}";

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


            // $caminho = "/usuario_instituicoes";
            // $caminhoCloud = $request->imagem->storePublicly($caminho, "cloud");
            // $dados['foto'] = $caminhoCloud;
        }

        if (!$dados['password']) {
            unset($dados['password']);
        }

        DB::transaction(function () use ($request, $instituicaoUsuario, $dados, $habilidadesPerfil, $instituicao, $habilidades) {
            $instituicaoUsuario->update($dados);
            $perfil_id = $dados['perfil_id'];

            $usuario_logado = $request->user('admin');
            $instituicaoUsuario->criarLogEdicao(
                $usuario_logado
            );

            foreach($habilidades as $k => $v){
                $habSelect[] = [
                    'usuario_id' => $instituicaoUsuario->id,
                    'habilidade_id' => $v->id,
                    'habilitado' => (in_array($v->id, $habilidadesPerfil)) ? 1 : 0,
                    'instituicao_id' => $instituicao->id,
                ];
            }

            // $instituicaoUsuario->instituicaoHabilidades()->detach();
            DB::table("instituicao_has_usuarios")->where([
                "instituicao_id" => $instituicao->id,
                "usuario_id" => $instituicaoUsuario->id
            ])->delete();

            $dadosUsuario = [
                "instituicao_id" => $instituicao->id,
                "usuario_id" => $instituicaoUsuario->id,
                "perfil_id" => $request->input('perfil_id')
            ];

            $instituicaoUsuario->instituicao()->attach([$dadosUsuario]);

            DB::table("instituicao_usuario_has_habilidades")->where([
                "instituicao_id" => $instituicao->id,
                "usuario_id" => $instituicaoUsuario->id
            ])->delete();

            $instituicaoUsuario->instituicaoHabilidades()->attach($habSelect);

            return $instituicaoUsuario;
        });

        return response()->json([
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Usuário atualizado com sucesso!'
        ]);

        // return redirect()->route('instituicao_usuarios.edit', [$instituicao, $instituicaoUsuario])->with('mensagem', [
        //     'icon' => 'success',
        //     'title' => 'Sucesso.',
        //     'text' => 'Usuário atualizado com sucesso!'
        // ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Instituicao $instituicao, InstituicaoUsuario $instituicaoUsuario)
    {
        $this->authorize('habilidade_admin', 'excluir_usuario_instituicao');

        // abort_unless($instituicao->id === $instituicaoUsuario->instituicao_id, 403);

        DB::transaction(function () use ($request, $instituicaoUsuario, $instituicao) {

            DB::table('instituicao_usuario_has_habilidades')->where([
                ['instituicao_id', $instituicao->id],
                ['usuario_id', $instituicaoUsuario->id]
            ])->delete();

            $instituicaoUsuario->instituicao()->detach($instituicao->id);

            $usuario_logado = $request->user('admin');
            // $instituicaoUsuario->criarLogExclusao(
            //     $usuario_logado
            // );

            $instituicaoUsuario->criarLog(
                $usuario_logado,
                "Desassociação com a instituição - #{$instituicao->id} {$instituicao->nome}",
                $instituicaoUsuario
            );

            return $instituicaoUsuario;
        });
        //return redirect()->route('usuarios.index')->with('mensagem', 'Excluído com sucesso');
        return redirect()->route('instituicao_usuarios.index', [$instituicao])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Usuário excluído com sucesso!'
        ]);
    }

    public function editHabilidadeInstituicao(Request $request, Instituicao $instituicao, InstituicaoUsuario $instituicaoUsuario)
    {
        $this->authorize('habilidade_admin', 'habilidades_usuario_instituicao');
        // // abort_unless($instituicao->id === $instituicaoUsuario->instituicao_id, 403);

        // $usuario_logado = $request->user('admin');

        // $habilidades = InstituicaoHabilidadeGrupo::with('instituicaoHabilidades')->get();


        // $habilidadesRamos = RamoHabilidade::where('ramo_id', $instituicao->ramo_id)->get();
        // $habilidadesRamos = array_column($habilidadesRamos->toArray(), 'habilidade_id');

        // // dd($habilidadesRamos);

        // // dd($habilidadesRamos, $habilidades->toArray());
        // foreach($habilidades as $k => $v){
        //     foreach($v['instituicaoHabilidades']->toArray() as $key => $value){
        //         if(!in_array($value['id'],  $habilidadesRamos)){
        //             unset($habilidades[$k]['instituicaoHabilidades'][$key]);
        //         }
        //     }
        // }

        // foreach($habilidades as $k => $v){
        //     if(count($v->instituicaoHabilidades) == 0){
        //         unset($habilidades[$k]);
        //     }
        // }

        // // dd(count($habilidades[10]->instituicaoHabilidades));

        // $instituicaoUsuario->load([
        //     'instituicaoHabilidades',
        // ]);

        // return view('admin.instituicao_usuarios.habilidades', \compact('habilidades', 'instituicaoUsuario', 'instituicao', 'habilidadesRamos'));

        // $this->authorize('habilidade_instituicao_sessao', 'habilidade_usuario');
        $usuario_logado = $request->user('admin');
        
        // abort_unless($instituicaoUsuario->instituicao->where('id', $request->session()->get('instituicao'))->isNotEmpty(), 403);

        $habilidades = InstituicaoHabilidadeGrupo::with('instituicaoHabilidades')->get();

        $habilidadesRamos = RamoHabilidade::where('ramo_id', $instituicao->ramo_id)->get();
        $habilidadesRamos = array_column($habilidadesRamos->toArray(), 'habilidade_id');

        // dd($habilidadesRamos);

        // dd($habilidadesRamos, $habilidades->toArray());
        foreach($habilidades as $k => $v){
            foreach($v['instituicaoHabilidades']->toArray() as $key => $value){
                if(!in_array($value['id'],  $habilidadesRamos)){
                    unset($habilidades[$k]['instituicaoHabilidades'][$key]);
                }
            }
        }

        foreach($habilidades as $k => $v){
            if(count($v->instituicaoHabilidades) == 0){
                unset($habilidades[$k]);
            }
        }
        
        $instituicaoUsuario->load([
            'instituicaoHabilidades' => function ($query) use ($instituicao) {
                return $query->wherePivot('instituicao_id', $instituicao->id);
            },
        ]);



        return view('admin.instituicao_usuarios.habilidades', \compact('habilidades', 'instituicaoUsuario', 'instituicao'));
    }

    public function updateHabilidadeInstituicao(HabilidadeUsuarioInstituicaoRequest $request, Instituicao $instituicao ,InstituicaoUsuario $instituicaoUsuario)
    {
        $this->authorize('habilidade_admin', 'habilidades_usuario_instituicao');
        // abort_unless($instituicao->id === $instituicaoUsuario->instituicao_id, 403);

        $usuario_logado = $request->user('admin');

        $habilidades = collect($request->validated()['habilidades'])
            ->filter(function ($habilidade) {
                return !is_null($habilidade);
            })
            ->map(function($habilitado) use ($instituicao){
                return [
                    'habilitado' => $habilitado,
                    'instituicao_id' => $instituicao->id
                ];
            });

        DB::transaction(function () use ($instituicaoUsuario, $habilidades, $usuario_logado, $instituicao){
            DB::table('instituicao_usuario_has_habilidades')->where([
                    ['instituicao_id', $instituicao->id],
                    ['usuario_id', $instituicaoUsuario->id]
                ])->delete();
            $instituicaoUsuario->instituicaoHabilidades()->attach($habilidades);

            $instituicaoUsuario->criarLog(
                $usuario_logado,
                "Alteração habilidades do usuario da instituição #{$instituicao->id} {$instituicao->nome}",
                $habilidades
            );

            return $instituicaoUsuario;
        });

        return redirect()->route('instituicao_usuarios.index', [$instituicao])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Habilidades do usuário da instituição editada com sucesso!'
        ]);

    }

    public function verificaCpfExistenteInstituicao(Request $request)
    {
        $cpf = $request->input('cpf');
        $instituicao_usuario = InstituicaoUsuario::where('cpf', $cpf)->get();
        // $instituicao_usuario[0]->foto = Storage::cloud()->url($instituicao_usuario[0]->foto);
        return $instituicao_usuario->toJson();
    }

    public function status(Request $request, Instituicao $instituicao)
    {
        $instituicao_usuario = InstituicaoUsuario::find($request->input('id'));
        $usuario_logado = $request->user('admin');
        
        
        $statusUsuarioInst = DB::table('instituicao_has_usuarios')->where([
            ['instituicao_id', $instituicao->id],
            ['usuario_id', $instituicao_usuario->id]
        ]);
        

            $dados['status'] = ($statusUsuarioInst->first()->status) ? 0 : 1;
            $tipo = ($statusUsuarioInst->first()->status) ? 'Desativado' : "Ativado";  
            
            $instituicao_usuario->instituicao()->where('instituicao_id', $instituicao->id)->update($dados);
            // DB::table('instituicao_has_usuarios')->where([
            //     ['instituicao_id', $instituicao->id],
            //     ['usuario_id', $instituicao_usuario->id]
            // ])->update($dados);

            $statusUsuarioInst->update($dados); 
            $instituicao_usuario->criarLog($usuario_logado, 'Mudança de status para '.$tipo, $dados);
            
            return response()->json([
                'icon' => 'success',
                'title' => 'Sucesso.',
                'text' => 'Status de usuário atualizado com sucesso!'
            ]);
        
        
        // DB::transaction(function () use ($instituicao_usuario, $request, $usuario_logado, $instituicao){
        //     $dados['status'] = ($instituicao_usuario->status) ? 0 : 1;
            
        //     // dd($instituicao_usuario->instituicao());
            
        //     $instituicao_usuario->instituicao()->where('instituicao_id', $instituicao->id)->update($dados);

        //     $instituicao_usuario->criarLogInstituicaoEdicao($usuario_logado, $instituicao->id);

        //     return $instituicao_usuario;
        // });

        return response()->json([
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Status de usuário atualizado com sucesso!'
        ]);
    }
}
