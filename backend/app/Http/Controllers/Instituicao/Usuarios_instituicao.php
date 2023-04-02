<?php

namespace App\Http\Controllers\Instituicao;

use App\Especialidade;
use App\Http\Controllers\Controller;
use App\Http\Requests\InstituicaoUsuario\HabilidadeUsuarioInstituicaoRequest;
use App\Http\Requests\UsuariosInstituicao\CriarUsuarioInstituicaoRequest;
use App\Http\Requests\UsuariosInstituicao\EditarUsuarioInstituicaoRequest;
use App\Instituicao;
use App\InstituicaoHabilidade;
use App\InstituicaoHabilidadeGrupo;
use App\InstituicaoUsuario;
use App\PerfilInstituicaoHabilidade;
use App\PerfilUsuarioInstituicao;
use App\RamoHabilidade;
use App\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Image;

class Usuarios_instituicao extends Controller
{
    //
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_usuario');

        return view('instituicao.usuarios_instituicao/lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_usuario');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        $perfil = PerfilUsuarioInstituicao::get();

        return view('instituicao.usuarios_instituicao/criar', \compact('instituicao', 'perfil'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CriarUsuarioInstituicaoRequest $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_usuario');
        $usuario_logado = $request->user('instituicao');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $dados = $request->validated();

        $habilidadesPerfil = PerfilInstituicaoHabilidade::where('perfil_id', $dados['perfil_id'] )->get();
        $habilidadesPerfil = array_column($habilidadesPerfil->toArray(), 'habilidade_id');

        $habilidades = InstituicaoHabilidade::get();

        $habSelect = array();

        // $cpf = preg_replace('/[^0-9]/', '', $request->input('cpf'));
        // $cnpj = preg_replace('/[^0-9]/', '', $instituicao->cnpj);
        if ($request->hasFile('imagem')) {

            $random = Str::random(20);
            $imageName = $random. '.' . $request->imagem->extension();
            $imagem_original = $request->imagem->storeAs('/usuario_instituicoes/'.$random, $imageName, config('filesystems.cloud'));
            $dados['foto'] = $imagem_original;


            $ImageResize = Image::make($request->imagem);

            $image300pxName = '/usuario_instituicoes/'.$random.'/'.'300px-'. $imageName;
            $image200pxName = '/usuario_instituicoes/'.$random.'/'.'200px-'. $imageName;
            $image100pxName = '/usuario_instituicoes/'.$random.'/'.'100px-'. $imageName;

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
            // $caminhoCloud = $request->imagem->storePublicly($caminho, "public");
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
                    "perfil_id" => $request->input('perfil_id'),
                    "desconto_maximo" => $request->input('desconto_maximo')
                ];
                $instituicao->instituicaoUsuarios()->attach([$dadosUsuario]);

                $instituicao_usuarios = $instituicao->instituicaoUsuarios()->find($dados['usuario_id']);
                // dd($instituicao_usuarios->toArray());
                $instituicao_usuarios->instituicaoHabilidades()->attach($habSelect);

                $usuario_logado = $request->user('instituicao');
                // $instituicao->criarLogInstituicao(
                //     $usuario_logado,
                //     "Associação com o instituicao - #{$instituicao->id} {$instituicao->nome}",
                //     $dados
                // );

                return $dados;
            });

        }else{

            DB::transaction(function () use ($request, $instituicao, $usuario_logado, $dados, $habilidades, $habilidadesPerfil){
                // $usuario_instituicao = $instituicao->instituicaoUsuarios()->create($dados);

                $usuario_instituicao = InstituicaoUsuario::create($dados);
                $instituicaoUsuario = $usuario_instituicao;

                $dadosUsuario = [
                    "instituicao_id" => $instituicao->id,
                    "usuario_id" => $instituicaoUsuario->id,
                    "perfil_id" => $request->input('perfil_id'),
                    "desconto_maximo" => $request->input('desconto_maximo')
                ];

                $instituicaoUsuario->instituicao()->attach([$dadosUsuario]);

                foreach($habilidades as $k => $v){
                    $habSelect[] = [
                        'usuario_id' => $usuario_instituicao->id,
                        'habilidade_id' => $v->id,
                        'habilitado' => (in_array($v->id, $habilidadesPerfil)) ? 1 : 0,
                        'instituicao_id' => $instituicao->id,
                    ];
                }


                $usuario_instituicao->criarLogInstituicaoCadastro(
                    $usuario_logado,
                    $instituicao->id
                );
                $usuario_instituicao->instituicaoHabilidades()->attach($habSelect);

                $usuario_instituicao->criarLogInstituicao(
                    $usuario_logado,
                    "Associação com o instituicao - #{$instituicao->id} {$instituicao->nome}",
                    $usuario_instituicao
                );

                return $usuario_instituicao;
            });
        }

        return response()->json([
            'icon' => 'success',
            'title' => 'Sucesso',
            'text' => 'Usuário criado com sucesso!'
        ]);
        // Exemplo customizando
        // return redirect()->route('instituicao.instituicoes_usuarios.index')->with('mensagem', [
        //     'icon' => 'success',
        //     'title' => 'Sucesso.',
        //     'text' => 'Usuário criado com sucesso!'
        // ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\InstituicaoUsuario  $instituicaousuario
     * @return \Illuminate\Http\Response
     */
    public function show(InstituicaoUsuario $instituicaoUsuario)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\InstituicaoUsuario  $instituicaousuario
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, InstituicaoUsuario $instituicaoUsuario)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_usuario');
        $usuario_logado = $request->user('instituicao');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        $perfil = PerfilUsuarioInstituicao::get();

        $usuario_perfil = $instituicaoUsuario->instituicao()->where('instituicao_id', $instituicao->id)->first();
        $usuario_desconto = $usuario_perfil->pivot->desconto_maximo;
        $usuario_perfil = $usuario_perfil->pivot->perfil_id;

        abort_unless($instituicaoUsuario->instituicao->where('id', $request->session()->get('instituicao'))->isNotEmpty(), 403);

        return view('instituicao.usuarios_instituicao/editar', \compact('instituicaoUsuario',  'perfil', 'usuario_perfil', 'usuario_desconto'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\InstituicaoUsuario  $instituicaousuario
     * @return \Illuminate\Http\Response
     */
    public function update(EditarUsuarioInstituicaoRequest $request, InstituicaoUsuario $instituicaoUsuario)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_usuario');
        $usuario_logado = $request->user('instituicao');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicaoUsuario->instituicao->where('id', $request->session()->get('instituicao'))->isNotEmpty(), 403);

        $dados = $request->validated();
        $habilidadesPerfil = PerfilInstituicaoHabilidade::where('perfil_id', $dados['perfil_id'] )->get();
        $habilidadesPerfil = array_column($habilidadesPerfil->toArray(), 'habilidade_id');

        $habilidades = InstituicaoHabilidade::get();

        $habSelect = array();

        if (!$dados['password']) {
            unset($dados['password']);
        }

        if ($request->hasFile('imagem')) {

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
            // Storage::disk('public')->delete($instituicaoUsuario->foto);

            // $caminho = "/usuario_instituicoes";
            // $caminhoCloud = $request->imagem->storePublicly($caminho, "public");
            // $dados['foto'] = $caminhoCloud;
        }

        DB::transaction(function () use ($request, $instituicaoUsuario, $dados, $usuario_logado, $instituicao, $habilidades, $habilidadesPerfil){
            $instituicaoUsuario->update($dados);

            $instituicaoUsuario->criarLogInstituicaoEdicao(
                $usuario_logado,
                $instituicao->id
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
            $vinculo = DB::table("instituicao_has_usuarios")->where([
                "instituicao_id" => $instituicao->id,
                "usuario_id" => $instituicaoUsuario->id
            ])->first();

            DB::table("instituicao_has_usuarios")->where([
                "instituicao_id" => $instituicao->id,
                "usuario_id" => $instituicaoUsuario->id
            ])->delete();
            
            $id_import = null;

            if(!empty($vinculo)){
                $id_import = $vinculo->id_import;
            }

            $dadosUsuario = [
                "instituicao_id" => $instituicao->id,
                "usuario_id" => $instituicaoUsuario->id,
                "perfil_id" => $request->input('perfil_id'),
                "desconto_maximo" => $request->input('desconto_maximo'),
                "id_import" => $id_import,
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

        // return redirect()->route('instituicao.instituicoes_usuarios.edit', [$instituicaoUsuario])->with('mensagem', [
        //     'icon' => 'success',
        //     'title' => 'Sucesso.',
        //     'text' => 'Usuário atualizado com sucesso!'
        // ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\InstituicaoUsuario  $instituicaousuario
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, InstituicaoUsuario $instituicaoUsuario)
    {
        $this->authorize('habilidade_instituicao_sessao', 'excluir_usuario');
        $usuario_logado = $request->user('instituicao');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicaoUsuario->instituicao->where('id', $request->session()->get('instituicao'))->isNotEmpty(), 403);

        DB::transaction(function () use ($instituicaoUsuario, $usuario_logado, $instituicao){

            DB::table('instituicao_usuario_has_habilidades')->where([
                ['instituicao_id', $instituicao->id],
                ['usuario_id', $instituicaoUsuario->id]
            ])->delete();

            $instituicaoUsuario->instituicao()->detach($instituicao->id);
            // $instituicaoUsuario->delete();

            $instituicaoUsuario->criarLogInstituicao(
                $usuario_logado,
                "Desassociação com o instituicao - #{$instituicao->id} {$instituicao->nome}",
                $instituicaoUsuario
            );

            return $instituicaoUsuario;
        });


        //return redirect()->route('usuarios.index')->with('mensagem', 'Excluído com sucesso');
        return redirect()->route('instituicao.instituicoes_usuarios.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Usuário excluído com sucesso!'
        ]);
    }

    public function editHabilidade(Request $request, InstituicaoUsuario $instituicaoUsuario)
    {
        $this->authorize('habilidade_instituicao_sessao', 'habilidade_usuario');
        $usuario_logado = $request->user('instituicao');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicaoUsuario->instituicao->where('id', $request->session()->get('instituicao'))->isNotEmpty(), 403);

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



        return view('instituicao.usuarios_instituicao.habilidades', \compact('habilidades', 'instituicaoUsuario', 'instituicao'));
    }

    public function updateHabilidade(HabilidadeUsuarioInstituicaoRequest $request, InstituicaoUsuario $instituicaoUsuario)
    {
        $this->authorize('habilidade_instituicao_sessao', 'habilidade_usuario');
        $usuario_logado = $request->user('instituicao');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicaoUsuario->instituicao->where('id', $request->session()->get('instituicao'))->isNotEmpty(), 403);

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
            // dd($instituicaoUsuario, $habilidades, $usuario_logado, $instituicao);

            DB::table('instituicao_usuario_has_habilidades')->where([
                ['instituicao_id', $instituicao->id],
                ['usuario_id', $instituicaoUsuario->id]
            ])->delete();

            $instituicaoUsuario->instituicaoHabilidades()->attach($habilidades);
               
            $instituicaoUsuario->criarLogInstituicao(
                $usuario_logado,
                "Alteração habilidades do usuario de instituicao #{$instituicao->id} {$instituicao->nome}",
                $habilidades,
                $instituicao->id
            );

            return $instituicaoUsuario;
        });

        return redirect()->route('instituicao.instituicoes_usuarios.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Habilidades do usuário editada com sucesso!'
        ]);

    }

    public function verificaCpfExistenteInstituicao(Request $request)
    {
        $cpf = $request->input('cpf');
        $instituicao_usuario = InstituicaoUsuario::where('cpf', $cpf)->get();
        // $instituicao_usuario[0]->foto = Storage::cloud()->url($instituicao_usuario[0]->foto);
        return $instituicao_usuario->toJson();
    }

    public function status(Request $request)
    {
        
        $instituicao_usuario = InstituicaoUsuario::find($request->input('id'));
        $usuario_logado = $request->user('instituicao');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        try {
            $statusUsuarioInst = DB::table('instituicao_has_usuarios')->where([
                ['instituicao_id', $instituicao->id],
                ['usuario_id', $instituicao_usuario->id]
            ]);

            $dados['status'] = ($statusUsuarioInst->first()->status) ? 0 : 1;
            $tipo = ($statusUsuarioInst->first()->status) ? 'Desativo' : "Ativo";  
            
            $instituicao_usuario->instituicao()->where('instituicao_id', $instituicao->id)->update($dados);
            // DB::table('instituicao_has_usuarios')->where([
            //     ['instituicao_id', $instituicao->id],
            //     ['usuario_id', $instituicao_usuario->id]
            // ])->update($dados);

            $statusUsuarioInst->update($dados);   
            $instituicao_usuario->criarLogInstituicao($usuario_logado, 'Mudança de status para '.$tipo, $dados, $instituicao->id);
            
            return response()->json([
                'icon' => 'success',
                'title' => 'Sucesso.',
                'text' => 'Status de usuário atualizado com sucesso!'
            ]);
        }catch(\Throwable $th){
           dd($th->message);
        }
        
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

    public function vincularContas(Request $request, InstituicaoUsuario $usuario)
    {
        
        $this->authorize('habilidade_instituicao_sessao', 'editar_usuario');
        $usuario_logado = $request->user('instituicao');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        $contas = $instituicao->contas()->get();
        // $contas_vinculadas = $usuario->contas()->get();

        $contas_vinculadas = collect($usuario->contas()->get())
        ->filter(function ($conta) {
            return !is_null($conta);
        })
        ->map(function ($conta) {
            return $conta->id;
        })->toArray();

        abort_unless($usuario->instituicao->where('id', $request->session()->get('instituicao'))->isNotEmpty(), 403);

        return view('instituicao.usuarios_instituicao/vincular_contas', \compact('usuario',  'contas', 'contas_vinculadas'));
    }

    public function salvarVinculoContas(Request $request, InstituicaoUsuario $usuario)
    {
        
        $this->authorize('habilidade_instituicao_sessao', 'editar_usuario');
        $usuario_logado = $request->user('instituicao');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($usuario->instituicao->where('id', $request->session()->get('instituicao'))->isNotEmpty(), 403);

        DB::transaction(function () use ($instituicao, $usuario_logado, $usuario, $request){
            
            // dd($usuario->contasInstituicao()->get()->toArray());

            foreach($usuario->contasInstituicao()->get() as $values){
                DB::table('contas_usuarios')
                    ->where('usuario_id', $usuario->id)
                    ->where('conta_id', $values->id)
                ->delete(); 
            }
            
            // $usuario->contas()->detach();

            if(!empty($request->input()['contas'])){
                $dados = collect($request->input()['contas'])
                ->filter(function ($conta) use ($request){
                    return !is_null($conta);
                })
                ->map(function ($conta) use ($request) {
                    return [
                        'usuario_id' => $request->input()['usuario_id'],
                        'conta_id' => json_decode($conta)->id,
                    ];
                });

            
                $usuario->contas()->attach($dados);
            }
        });        

        return response()->json([
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Contas vinculadas com sucesso'
        ]);
    }

    public function visualizarPrestadores(Request $request, InstituicaoUsuario $usuario)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_visualizar_prestadores');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        abort_unless($usuario->instituicao->where('id', $request->session()->get('instituicao'))->isNotEmpty(), 403);

        $prestadores =  Especialidade::
        whereHas('prestadoresInstituicao', function($q) use($instituicao){
            $q->where('ativo', 1);
            $q->where('instituicoes_id',$instituicao->id);
        })->get();
        $instituicao_logada = $usuario->instituicao->where('id', $request->session()->get('instituicao'))->first();
        $prestadoresIds = explode(',', $instituicao_logada->pivot->visualizar_prestador);
        // dd($prestadoresIds);
        return view('instituicao.usuarios_instituicao/modal_prestador', \compact('prestadores', 'prestadoresIds', 'usuario'));
    }
    
    public function salvarPrestadores(Request $request, InstituicaoUsuario $usuario)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_visualizar_prestadores');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        $usuario_logado = $request->user('instituicao');
        abort_unless($usuario->instituicao->where('id', $request->session()->get('instituicao'))->isNotEmpty(), 403);

        DB::transaction(function () use ($instituicao, $usuario_logado, $usuario, $request){
            $dados = $request->input('visualizar_prestador');
            $vinculo = DB::table("instituicao_has_usuarios")->where([
                "instituicao_id" => $instituicao->id,
                "usuario_id" => $usuario->id
            ])->first();

            $dadosUsuario = [
                "instituicao_id" => $instituicao->id,
                "usuario_id" => $usuario->id,
                "perfil_id" => $vinculo->perfil_id,
                "id_import" => $vinculo->id_import,
                "status" => $vinculo->status,
                "visualizar_setores" => $vinculo->visualizar_setores,
            ];

            DB::table("instituicao_has_usuarios")->where([
                "instituicao_id" => $instituicao->id,
                "usuario_id" => $usuario->id
            ])->delete();

            if($dados == null){
                $dadosUsuario['visualizar_prestador'] = null;
                $usuario->instituicao()->attach([$dadosUsuario]);
            }else{
                $array = "";
                for ($i=0; $i < count($dados); $i++) { 
                    if($i == 0){
                        $array = $dados[$i];
                    }else{
                        $array .= ','.$dados[$i];
                    }
                }

                $dadosUsuario['visualizar_prestador'] = $array;
                $usuario->instituicao()->attach([$dadosUsuario]);
            }

            $usuario->criarLogInstituicao($usuario_logado, 'ALTERAÇÃO DE VISUALIZAR PRESTADORES AGENDA', $dados, $instituicao->id);
        });

        return response()->json(true);
    }
    
    public function visualizarSetores(Request $request, InstituicaoUsuario $usuario)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_visualizar_setores_usuario');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        abort_unless($usuario->instituicao->where('id', $request->session()->get('instituicao'))->isNotEmpty(), 403);

        $setores =  $instituicao->setoresExame()->get();
        $instituicao_logada = $usuario->instituicao->where('id', $request->session()->get('instituicao'))->first();
        $setoresIds = explode(',', $instituicao_logada->pivot->visualizar_setores);
        // dd($setoresIds);
        return view('instituicao.usuarios_instituicao/modal_setores', \compact('setores', 'setoresIds', 'usuario'));
    }
    
    public function salvarSetores(Request $request, InstituicaoUsuario $usuario)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_visualizar_setores_usuario');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        $usuario_logado = $request->user('instituicao');
        abort_unless($usuario->instituicao->where('id', $request->session()->get('instituicao'))->isNotEmpty(), 403);

        DB::transaction(function () use ($instituicao, $usuario_logado, $usuario, $request){
            $dados = $request->input('visualizar_setores');
            $vinculo = DB::table("instituicao_has_usuarios")->where([
                "instituicao_id" => $instituicao->id,
                "usuario_id" => $usuario->id
            ])->first();

            $dadosUsuario = [
                "instituicao_id" => $instituicao->id,
                "usuario_id" => $usuario->id,
                "perfil_id" => $vinculo->perfil_id,
                "id_import" => $vinculo->id_import,
                "status" => $vinculo->status,
                "visualizar_prestador" => $vinculo->visualizar_prestador,
            ];

            DB::table("instituicao_has_usuarios")->where([
                "instituicao_id" => $instituicao->id,
                "usuario_id" => $usuario->id
            ])->delete();

            if($dados == null){
                $dadosUsuario['visualizar_setores'] = null;
                $usuario->instituicao()->attach([$dadosUsuario]);
            }else{
                $array = "";
                for ($i=0; $i < count($dados); $i++) { 
                    if($i == 0){
                        $array = $dados[$i];
                    }else{
                        $array .= ','.$dados[$i];
                    }
                }

                $dadosUsuario['visualizar_setores'] = $array;
                $usuario->instituicao()->attach([$dadosUsuario]);
            }

            $usuario->criarLogInstituicao($usuario_logado, 'ALTERAÇÃO DE VISUALIZAR SETORES AGENDA', $dados, $instituicao->id);
        });

        return response()->json(true);
    }
}
