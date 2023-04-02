<?php

namespace App\Http\Controllers\Instituicao;

use App\Http\Controllers\Controller;
use App\Http\Requests\ComercialLoja\ParcelasComercialLojaRequest;
use App\Http\Requests\InstituicaoBackend\EditarInstituicaoBackendResquest;
use App\Instituicao;
use App\Ramo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Image;

class Instituicao_loja extends Controller
{
    public function edit(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_instituicao');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        $ramos = Ramo::get();

        return view('instituicao.instituicao_loja/editar', \compact('instituicao', 'ramos'));
    }

    public function update(EditarInstituicaoBackendResquest $request, Instituicao $instituicao)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_instituicao');
        $usuario_logado = $request->user('instituicao');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $dados = $request->validated();

        $dados['finalizar_consultorio'] = $request->boolean('finalizar_consultorio');

        if ($request->hasFile('imagem')) {

            $random = Str::random(20);

            if($instituicao->imagem){
                $pasta = Str::of($instituicao->imagem)->explode('/');
                Storage::cloud()->deleteDirectory($pasta[0].'/'.$pasta[1]);
            }

            $imageName = $random. '.' . $request->imagem->extension();
            $imagem_original = $request->imagem->storeAs('/instituicoes/'.$random, $imageName, config('filesystems.cloud'));
            $dados['imagem'] = $imagem_original;


            $ImageResize = Image::make($request->imagem);

            $image300pxName = '/instituicoes/'.$random.'/'.'300px-'. $imageName;
            $image200pxName = '/instituicoes/'.$random.'/'.'200px-'. $imageName;
            $image100pxName = '/instituicoes/'.$random.'/'.'100px-'. $imageName;

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

        }

        DB::transaction(function () use ($usuario_logado, $instituicao, $dados){
            $instituicao->update($dados);

            $instituicao->criarLogEdicao(
                $usuario_logado,
                $instituicao->id
            );
        });

        return response()->json([
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Instituicao atualizado com sucesso!'
        ]);
    }

    public function edit_parcelas(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_parcelas');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        return view('instituicao.parcelas/editar', \compact('instituicao'));
    }

    public function update_parcelas(ParcelasComercialLojaRequest $request){
        $this->authorize('habilidade_instituicao_sessao', 'editar_parcelas');
        $usuario_logado = $request->user('instituicao');

        $dados = $request->validated();

        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        DB::transaction(function () use ($usuario_logado, $instituicao, $dados){
            $instituicao->update($dados);

            $instituicao->criarLogEdicao(
                $usuario_logado,
                $instituicao->id
            );

        });

        return redirect()->route('instituicao.parcelas.edit')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Parcelas atualizada com sucesso!'
        ]);
    }

    public function configuracoes(Request $request)
    {
        
        $this->authorize('habilidade_instituicao_sessao', 'config_instituicao');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        $usuario_logado = $request->user('instituicao');

        $config = json_decode($instituicao->config);
        $modelos_recibo = $instituicao->modelosRecibo()->get();

        abort_unless($usuario_logado->instituicao->where('id', $request->session()->get('instituicao'))->isNotEmpty(), 403);

        return view('instituicao.usuarios_instituicao/configuracoes', \compact('instituicao', 'config', 'modelos_recibo'));
    }

    public function salvarConfig(Request $request, Instituicao $instituicao){
        $this->authorize('habilidade_instituicao_sessao', 'config_instituicao');
        $usuario_logado = $request->user('instituicao');

        abort_unless($usuario_logado->instituicao->where('id', $request->session()->get('instituicao'))->isNotEmpty(), 403);
        $dados = $request->input();
        unset($dados["_token"]);

        DB::transaction(function () use ($usuario_logado, $instituicao, $dados){
            $instituicao->update(["config" => $dados]);

            $instituicao->criarLogEdicao(
                $usuario_logado,
                $instituicao->id
            );

        });

        return response()->json([
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Configurações salvas com sucesso!'
        ]);
    }
}
