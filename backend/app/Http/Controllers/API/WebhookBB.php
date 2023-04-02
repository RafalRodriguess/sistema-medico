<?php

namespace App\Http\Controllers\Api;

use App\ContaReceber;
use App\Http\Controllers\Controller;
use App\Instituicao;
use App\InstituicaoUsuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class WebhookBB extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function statusApiWh(Request $request){
        $boletos = $request->all();
        $error = [];
        if(!empty($boletos)){
            foreach($boletos as $item){
                $conta_rec = ContaReceber::where('apibb_numero', $item['id'])->first();
                
                if(!empty($conta_rec)){
                    $instituicao = Instituicao::find($conta_rec->instituicao_id);
                    $usuario = InstituicaoUsuario::find(1);

                    if($instituicao->apibb_codigo_cedente == $item['numeroConvenio']){
                        // dd($item);
                        if($item['codigoEstadoBaixaOperacional'] == 1 || $item['codigoEstadoBaixaOperacional'] == 2){
                            if($conta_rec->status == 0){
                                // dd($item, $conta_rec);
                                DB::transaction(function () use ($conta_rec, $item, $instituicao, $usuario) {
                                    $dados = [
                                        'data_pago' => date("Y-m-d"),
                                        'valor_pago' => (float) $item['valorPagoSacado'],
                                        'status' => 1,
                                    ];

                                    $conta_rec->update($dados);
                                    $conta_rec->criarLogEdicao($usuario, $instituicao->id);
                                });
                            }else{
                                $error[] = "conta receber de id {$conta_rec->id} já se encontra baixada em  {$conta_rec->data_pago}";
                            }
                        }elseif($item['codigoEstadoBaixaOperacional'] == 10){
                            if($conta_rec->status == 1){
                                // dd($item, $conta_rec);
                                DB::transaction(function () use ($conta_rec, $item, $instituicao, $usuario) {
                                    $dados = [
                                        'data_pago' => null,
                                        'valor_pago' => null,
                                        'status' => 0,
                                    ];

                                    $conta_rec->update($dados);
                                    $conta_rec->criarLogEdicao($usuario, $instituicao->id);
                                });
                            }else{
                                $error[] = "conta receber de id {$conta_rec->id} não se encontra baixada, estorno não realizado";
                            }
                        }else{
                            $error[] = "boleto {$item['id']} possui codigo de baixa não previsto";
                        }
                    }else{
                        $error[] = "boleto {$item['id']} possui convenio diferente do convenio da instituicao";
                    }
                }else{
                    $error[] = "boleto {$item['id']} não localizado";
                }
            }
        }else{
            $error[] = "Retorno vazio";
        }

        $arquivo_nome = "webhookBB_".date("Y_m_d_H_i_s").".json";
        $path = Storage::disk('public')->put('webhook_bb/'.$arquivo_nome, json_encode($boletos));

        if(empty($erros)){
            foreach($error as $v){
                echo $v."<br>";
            }
        }else{
            echo "Tudo ok";
        }
    }

}
