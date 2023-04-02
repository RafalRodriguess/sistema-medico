<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\NotaFiscal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotasFiscais extends Controller
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

    public function getStatusWebHook(Request $request){
        // dd($request->all());
        // return [];
        
        if(!empty($request->all())){
            $dados_nfse_webhook = $request->all();            
            if(!empty($dados_nfse_webhook['nfeId']) ){
                $nota = NotaFiscal::where('id_nfse_enotas', $dados_nfse_webhook['nfeId'])->orwhere('id', $dados_nfse_webhook['nfeIdExterno'])->first();
                if(!empty($nota)){
                    $dados = [
                        'status' => $dados_nfse_webhook['nfeStatus'],
                        'id_nfse_enotas' => $dados_nfse_webhook['nfeId'],
                        'motivo_status' => $dados_nfse_webhook['nfeMotivoStatus'],
                        // 'json_nfe' => $dados_nfse_webhook,
                        'numero_nota' => (!empty($dados_nfse_webhook['nfeNumero'])) ? $dados_nfse_webhook['nfeNumero'] : null,
                    ];

                    $retorno = DB::transaction(function () use($nota, $dados){
                        $nota->update($dados);

                        return true;
                    });
                    
                    if($retorno === true){
                        // return response('ok', 200)->json(['ok'], 200);
                        return response()->json(["text" => 'ok', "dados" => $dados_nfse_webhook], 200);
                    }else{
                        // return response()->json([$retorno], 500);
                        return response()->json([$retorno], 500);
                    }
                }else{
                    return response()->json(["text" => 'Nota não localizada pelo id ', "dados" => $dados_nfse_webhook], 404);
                }
            }else{
                return response()->json(["text" => 'Valores obrigatorios não recebidos', "dados" => $dados_nfse_webhook], 500);
            }
        }else{
            return response()->json(["text" => 'POST vazio'], 500);
        }
    }
}
