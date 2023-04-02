<?php

namespace App\Http\Controllers\Comercial;

use App\Comercial;
use App\Http\Controllers\Controller;
use App\Http\Requests\ComercialLoja\EditarComercialLojaRequest;
use App\Http\Requests\ComercialLoja\ParcelasComercialLojaRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Image;

class Comercial_loja extends Controller
{
    public function edit(Request $request)
    {
        $this->authorize('habilidade_comercial_sessao', 'editar_comercial');
        $comercial = Comercial::find($request->session()->get('comercial'));

        return view('comercial.comercial_loja/editar', \compact('comercial'));
    }

    public function update(EditarComercialLojaRequest $request)
    {
        $this->authorize('habilidade_comercial_sessao', 'editar_comercial');
        $usuario_logado = $request->user('comercial');
        $comercial = Comercial::find($request->session()->get('comercial'));

        $dados = $request->validated();

        $dados['realiza_entrega'] = $request->boolean('realiza_entrega');
        $dados['retirada_loja'] = $request->boolean('retirada_loja');
        $dados['exibir'] = $request->boolean('exibir');
        
        $dados['cartao_credito'] = $request->boolean('cartao_credito');
        $dados['cartao_entrega'] = $request->boolean('cartao_entrega');
        $dados['dinheiro'] = $request->boolean('dinheiro');

        if ($request->hasFile('imagem')) {

            Storage::cloud()->delete($comercial->logo);
            if($comercial->logo){
                $caminho = Str::of($comercial->logo)->explode('/');
                Storage::cloud()->delete("{$caminho[0]}/{$caminho[1]}/300px-{$caminho[2]}");
                Storage::cloud()->delete("{$caminho[0]}/{$caminho[1]}/200px-{$caminho[2]}");
                Storage::cloud()->delete("{$caminho[0]}/{$caminho[1]}/100px-{$caminho[2]}");
            }
            // $random = Str::random(20);
            $cnpj = preg_replace('/[^0-9]/', '', $comercial->cnpj);
            $imageName = $cnpj. '.' . $request->imagem->extension();
            $imagem_original = $request->imagem->storeAs('/comerciais/'.$cnpj, $imageName, config('filesystems.cloud'));
            $dados['logo'] = $imagem_original;


            $ImageResize = Image::make($request->imagem);

            $image300pxName = "/comerciais/{$cnpj}/300px-{$imageName}";
            $image200pxName = "/comerciais/{$cnpj}/200px-{$imageName}";
            $image100pxName = "/comerciais/{$cnpj}/100px-{$imageName}";

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

            // Storage::disk('public')->delete($comercial->logo);


            // $caminho = "/comerciais/{$cnpj}";
            // $caminhoCloud = $request->imagem->storePublicly($caminho, "public");
            // $dados['logo'] = $caminhoCloud;
        }

        DB::transaction(function () use ($usuario_logado, $comercial, $dados){
            $comercial->update($dados);

            $comercial->criarLogEdicao(
                $usuario_logado,
                $comercial->id
            );
        });

        return response()->json([
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Comercial atualizado com sucesso!'
        ]);
        //return redirect()->route('usuarios.edit', [$usuario])->with('mensagem', 'Salvo com sucesso');
        // return redirect()->route('comercial.comercial_loja.edit', [$comercial])->with('mensagem', [
        //     'icon' => 'success',
        //     'title' => 'Sucesso.',
        //     'text' => 'Comercial atualizado com sucesso!'
        // ]);
    }

    public function edit_parcelas(Request $request)
    {
        $this->authorize('habilidade_comercial_sessao', 'editar_parcelas');
        $comercial = Comercial::find($request->session()->get('comercial'));

        return view('comercial.parcelas/editar', \compact('comercial'));
    }

    public function update_parcelas(ParcelasComercialLojaRequest $request){
        $this->authorize('habilidade_comercial_sessao', 'editar_parcelas');
        $usuario_logado = $request->user('comercial');

        $dados = $request->validated();

        $comercial = Comercial::find($request->session()->get('comercial'));
        DB::transaction(function () use ($usuario_logado, $comercial, $dados){
            $comercial->update($dados);

            $comercial->criarLogEdicao(
                $usuario_logado,
                $comercial->id
            );

        });

        return redirect()->route('comercial.parcelas.edit')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Parcelas atualizada com sucesso!'
        ]);
    }
}
