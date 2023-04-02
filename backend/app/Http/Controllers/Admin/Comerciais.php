<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Comerciais\CriarComercialRequest;
use App\Http\Requests\Comerciais\EditarComercialRequest;
use App\Http\Requests\ContaBancaria\CriarContaBancariaRequest;
use App\Comercial;
use App\Fretes;
use App\ContaBancaria;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Libraries\PagarMe;
use Illuminate\Support\Str;
use Image;

class Comerciais extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('habilidade_admin', 'visualizar_comercial');

        return view('admin.comerciais/lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('habilidade_admin', 'cadastrar_comercial');
        $comerciais = Comercial::all();
        return view('admin.comerciais/criar', \compact('comerciais'));
    }

    public function store(CriarComercialRequest $request)
    {
        $this->authorize('habilidade_admin', 'cadastrar_comercial');

        $dados = $request->validated();

        $dados['exibir'] = $request->boolean('exibir');
    
        $dados['cartao_entrega'] = $request->boolean('cartao_entrega');
        $dados['dinheiro'] = $request->boolean('dinheiro');
        $dados['cartao_credito'] = $request->boolean('cartao_credito');

        $cnpj = preg_replace('/[^0-9]/', '', $request->cnpj);
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


        $comercial = DB::transaction(function () use ($request, $dados){
            $comercial = Comercial::create($dados);

            $usuario_logado = $request->user('admin');
            $comercial->criarLogCadastro($usuario_logado);

            return $comercial;
        });

        $entrega = [
            'comercial_id' => $comercial->id,
            'tipo_filtro' => $request->input('tipo_filtro'),
            'tipo_frete' => 'entrega',
            'ativado' => 0,
        ];

        $retirada = [
            'comercial_id' => $comercial->id,
            'tipo_frete' => 'retirada',
            'ativado' => 0,
        ];

        //cadastra os fretes de retirada do comercial
        $frete = DB::transaction(function () use ($request, $retirada){
            $frete = Fretes::create($retirada);
            $usuario_logado = $request->user('admin');
            $frete->criarLogCadastro($usuario_logado);
            return $frete;
        });
        //cadastra os fretes de entrega do comercial
        $frete = DB::transaction(function () use ($request, $entrega){
            $frete = Fretes::create($entrega);
            $usuario_logado = $request->user('admin');
            $frete->criarLogCadastro($usuario_logado);
            return $frete;
        });

        $this->HorariosFuncionamento($comercial);

        return response()->json([
            'icon' => 'success',
            'title' => 'Sucesso',
            'text' => 'Comercial criado com sucesso!'
        ]);

        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Comercial  $Comercial
     * @return \Illuminate\Http\Response
     */
    public function show(Comercial $comercial)
    {
        //
    }

    public function edit(Comercial $comercial)
    {
        $this->authorize('habilidade_admin', 'editar_comercial');

        $configfrete = Fretes::wherehas('comercial',function($q) use ($comercial){
            $q->where('tipo_frete', 'entrega');
            $q->where('comercial_id', $comercial->id);
        })->get()->first();



        return view('admin.comerciais/editar', \compact('comercial', 'configfrete'));

    }

    public function update(EditarComercialRequest $request, Comercial $comercial)
    {
        $this->authorize('habilidade_admin', 'editar_comercial');
        $dados = $request->validated();
        $dados['realiza_entrega'] = $request->boolean('realiza_entrega');
        $dados['retirada_loja'] = $request->boolean('retirada_loja');
        $dados['exibir'] = $request->boolean('exibir');
    
        $dados['cartao_entrega'] = $request->boolean('cartao_entrega');
        $dados['dinheiro'] = $request->boolean('dinheiro');
        $dados['cartao_credito'] = $request->boolean('cartao_credito');

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

        }

        DB::transaction(function () use ($comercial,$request,$dados){
            $comercial->update($dados);

            $usuario_logado = $request->user('admin');
            $comercial->criarLogEdicao(
              $usuario_logado
            );

            return $comercial;
        });

        return response()->json([
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Comercial atualizado com sucesso!'
        ]);
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Comercial  $Comercial
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Comercial $comercial)
    {
        $this->authorize('habilidade_admin', 'excluir_comercial');
        DB::transaction(function () use ($comercial,$request){
            $comercial->delete();

            $usuario_logado = $request->user('admin');
            $comercial->criarLogExclusao(
              $usuario_logado
            );

            return $comercial;
        });

        return redirect()->route('comercial.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Comercial excluído com sucesso!'
        ]);
    }

    public function editBanco(Comercial $comercial){
        // dd($comercial->banco);
        $this->authorize('habilidade_admin', 'editar_conta_bancaria_comercial');
        return view('admin.comerciais/banco', \compact('comercial'));
    }

    public function updateBanco(CriarContaBancariaRequest $request, Comercial $comercial){

        $this->authorize('habilidade_admin', 'editar_conta_bancaria_comercial');

        $pagarMe = new PagarMe();

        $banco_pagarme = $pagarMe->cadastrarContaBancaria([
            'bank_code' => $request->banco_id,
            'agencia' => $request->agencia,
            'agencia_dv' => $request->agencia_dv,
            'conta' => $request->conta,
            'conta_dv' => $request->conta_dv,
            'type' => $request->type,
            'document_number' => $request->documento_titular,
            'legal_name' => $request->nome_titular
        ]);

        $banco = ContaBancaria::create([
            'bank_name' => $request->banco_nome,
            'bank_code' => $banco_pagarme->bank_code,
            'agencia' => $banco_pagarme->agencia,
            'agencia_dv' => $banco_pagarme->agencia_dv,
            'conta' => $banco_pagarme->conta,
            'conta_dv' => $banco_pagarme->conta_dv,
            'type' => $banco_pagarme->type,
            'documento_titular' => $banco_pagarme->document_number,
            'nome_titular' => $banco_pagarme->legal_name,
            'id_pagarme' => $banco_pagarme->id,
        ]);

        $comercial->banco()->associate($banco);
        if($comercial->id_recebedor==null){
            $comercial->id_recebedor = $pagarMe->cadastrarRecebedor($banco->id_pagarme)->id;
        }else{
            $pagarMe->atualizarBancoRecebedor($comercial->id_recebedor, $banco->id_pagarme);
        }
        $comercial->update();


        return redirect()->route('comercial.banco', [$comercial])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Banco atualizado com sucesso!'
        ]);

    }

    private function HorariosFuncionamento($comercial)
    {
        $semana[0] = 'domingo';
        $semana[1] = 'segunda-feira';
        $semana[2] = 'terça-feira';
        $semana[3] = 'quarta-feira';
        $semana[4] = 'quinta-feira';
        $semana[5] = 'sexta-feira';
        $semana[6] = 'sabado';
        for ($i=0; $i < 7; $i++) { 
            $dados = [
                'dia_semana' => $semana[$i],
                'horario_inicio' => '08:00',
                'horario_fim' => '18:00',
                'full_time' => false,
                'fechado' => false,
            ];

            DB::transaction( function() use($dados, $comercial){
                $comercial->horarioFuncionamento()->create($dados);
            });
        }
    }

}
