<?php

namespace App\Http\Controllers\API;

use App\InstituicaoDocumento;
use App\Http\Controllers\Controller;
use App\Http\Resources\DocumentosCollection;
use App\Http\Resources\DocumentosProdutosCollection;
use App\Http\Resources\GruposDocumentoHistoricoCollection;
use App\Produto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentosController extends Controller
{
    public function index(Request $request)
    {
        //PROVISORIO DANDO A CARGA NOS MEDICAMENTOS
        // return $this->cargaMedicamentos($request);
        // return $this->cargaExames($request);
        // return $this->cargaAgenda($request);
        // return $this->cargaLaboratorio($request);


        //LISTANDO RECEITUARIOS
        if($request->grupo == 'receituario'){

            return $this->lista_receituarios($request);
        
        //LISTANDO DOCUMENTOS;
        }else if($request->grupo == 'atestado'){
        
            return $this->lista_atestados($request);

        //LISTANDO EXAMES;
        }else if($request->grupo == 'exame'){
        
            return $this->lista_exames($request);

        }

        


    }


    public function lista_receituarios($request){

        /* PEGANDO OS IDS DAS VINCULAÇÕES AS INSTITUIÇÕES */
        $usuario = $request->user('sanctum');
        $arrayIds = array();

        //VAMOS PEGAR OS IDS DAS INSTITUICOES VINCULADAS DO USUARIO
        $usuarioInstituicoes = $usuario->instituicao()
                                   //->select('instituicao_has_pacientes.id')
                                   ->where('usuario_id', $usuario->id)
                                   ->get();
        
        if(!empty($usuarioInstituicoes)){
            foreach($usuarioInstituicoes as $usuarioInstituicao){
                $arrayIds[] = $usuarioInstituicao->pivot->id;
            }
        }
        /* FIM PEGANDO OS IDS */

        $documentos = InstituicaoDocumento::query()
            ->whereIn('instituicao_has_pacientes_id', $arrayIds)
            ->where('grupo', 'receita')
            ->orderByDesc('data_pedido')
            ->get();

        $documentos->load(['instituicao:instituicoes.id,nome']);

            //VAMOS JUNTAR OS ITENS DO RECEITUARIO
            $arrayReceitas =  array();
            $receitasFinal =  array();

            foreach ($documentos as $row) {

             if($row->tipo == 'novo'){
                 
                   $arrayReceitas[$row->codigo_pedido][] = array(
                    'id_externo' => $row->id_externo,
                    'codigo_pedido' => $row->codigo_pedido,
                    'tipo' => $row->tipo,
                    'nome_prestador' => Str::title($row->nome_prestador),
                    'descricao' => $row->descricao,
                    'data_pedido' => $row->data_pedido->format('d/m/Y'),
                    'descricao' => $row->descricao,
                    'medicacao_qtd' => $row->medicacao_qtd,
                    'medicacao_posologia' => $row->medicacao_posologia,
                    'instituicao_nome' => $row->instituicao->nome,
                );

                }else{
                    
                    $arrayReceitas[][] = array(
                        'id_externo' => $row->id_externo,
                        'codigo_pedido' => $row->codigo_pedido,
                        'tipo' => $row->tipo,
                        'nome_prestador' => Str::title($row->nome_prestador),
                        'descricao' => $row->descricao,
                        'data_pedido' => $row->data_pedido->format('d/m/Y'),
                        'descricao' => $row->descricao,
                        'medicacao_qtd' => $row->medicacao_qtd,
                        'medicacao_posologia' => $row->medicacao_posologia,
                        'instituicao_nome' => $row->instituicao->nome,
                    );

                }

            }

            //VAMOS ORGANIZAR OS ITENS DO RECEITUARIO
            if(!empty($arrayReceitas)){

                foreach ($arrayReceitas as $key=>$receita) {

                    if(!empty($receita[0]['tipo'])): 
                        $tipo = $receita[0]['tipo']; 
                    else: 
                        $tipo = '';
                    endif;

                    if(!empty($receita[0]['nome_prestador'])): 
                        $nome_prestador = $receita[0]['nome_prestador']; 
                    else: 
                        $nome_prestador = '';
                    endif;

                    if(!empty($receita[0]['data_pedido'])): 
                        $data_pedido = $receita[0]['data_pedido']; 
                    else: 
                        $data_pedido = '';
                    endif;

                    if(!empty($receita[0]['descricao'])): 
                        $precricao_old = $receita[0]['descricao']; 
                    else: 
                        $precricao_old = '';
                    endif;

                    if(!empty($receita[0]['instituicao_nome'])): 
                        $instituicao_nome = $receita[0]['instituicao_nome']; 
                    else: 
                        $instituicao_nome = '';
                    endif;
                    

                    //VAMOS JUNTAR A PRESCRIÇÃO
                    if($tipo == 'novo'){

                        $chave_consulta = $receita[0]['codigo_pedido'];

                        $prescricao = '';
                        
                        if(!empty($receita)): 
                            foreach($receita as $val){
                                $prescricao.= $val['descricao'].'......'.$val['medicacao_qtd'].'<br>'.$val['medicacao_posologia'].'<br>';
                            }
                        endif;

                    }else{
                        $chave_consulta = $receita[0]['id_externo'];
                        $prescricao = $precricao_old;
                    }

                    $receitasFinal[] = array(
                        'grupo' => 'receita',
                        'chave_consulta' => $chave_consulta,
                        'tipo' => $tipo,
                        'nome_prestador' => $nome_prestador,
                        'data_pedido' => $data_pedido,
                        'prescricao' => $prescricao,
                        'instituicao_nome' => $instituicao_nome,
                    );

                }


            }

            
            return $receitasFinal;

        //return new DocumentosCollection($receitasFinal);

    }

    public function lista_atestados($request){

        /* PEGANDO OS IDS DAS VINCULAÇÕES AS INSTITUIÇÕES */
        $usuario = $request->user('sanctum');
        $arrayIds = array();

        //VAMOS PEGAR OS IDS DAS INSTITUICOES VINCULADAS DO USUARIO
        $usuarioInstituicoes = $usuario->instituicao()
                                   //->select('instituicao_has_pacientes.id')
                                   ->where('usuario_id', $usuario->id)
                                   ->get();
        
        if(!empty($usuarioInstituicoes)){
            foreach($usuarioInstituicoes as $usuarioInstituicao){
                $arrayIds[] = $usuarioInstituicao->pivot->id;
            }
        }
        /* FIM PEGANDO OS IDS */

        $documentos = InstituicaoDocumento::query()
            ->whereIn('instituicao_has_pacientes_id', $arrayIds)
            ->where('grupo', 'atestado')
            ->orderByDesc('data_pedido')
            ->get();

        $documentos->load(['instituicao:instituicoes.id,nome']);

        foreach ($documentos as $row) {

                  $arrayDocumentos[] = array(
                   'grupo' => 'atestado',
                   'chave_consulta' => $row->id_externo,
                   'tipo' => 'novo',
                   'nome_prestador' => Str::title($row->nome_prestador),
                   'data_pedido' => $row->data_pedido->format('d/m/Y'),
                   'prescricao' => $row->descricao,
                   'instituicao_nome' => $row->instituicao->nome,
               );
        }

        return $arrayDocumentos;

    }




    public function lista_exames($request){

        /* PEGANDO OS IDS DAS VINCULAÇÕES AS INSTITUIÇÕES */
        $usuario = $request->user('sanctum');
        $arrayIds = array();

        //VAMOS PEGAR OS IDS DAS INSTITUICOES VINCULADAS DO USUARIO
        $usuarioInstituicoes = $usuario->instituicao()
                                   //->select('instituicao_has_pacientes.id')
                                   ->where('usuario_id', $usuario->id)
                                   ->get();
        
        if(!empty($usuarioInstituicoes)){
            foreach($usuarioInstituicoes as $usuarioInstituicao){
                $arrayIds[] = $usuarioInstituicao->pivot->id;
            }
        }
        /* FIM PEGANDO OS IDS */

        $filtro = null;

        if($request->filtro != '0'){
            $filtro = $request->filtro;
        }

        $documentos = InstituicaoDocumento::query()
            ->whereIn('instituicao_has_pacientes_id', $arrayIds)
            ->where('grupo', 'exame')
            ->when($filtro, function($q) use ($filtro){
                $q->where('tipo', 'like', $filtro);
            })
            ->orderByDesc('data_pedido')
            ->get();

        $documentos->load(['instituicao:instituicoes.id,nome']);

        //VAMOS JUNTAR OS ITENS DO RECEITUARIO
        $arrayReceitas =  array();
        $receitasFinal =  array();

        foreach ($documentos as $row) {
             
               $arrayReceitas[$row->codigo_pedido][] = array(
                'id_externo' => $row->id_externo,
                'codigo_pedido' => $row->codigo_pedido,
                'tipo' => Str::title($row->tipo),
                'nome_prestador' => Str::title($row->nome_prestador),
                'descricao' => $row->descricao,
                'data_pedido' => $row->data_pedido->format('d/m/Y'),
                'descricao' => $row->descricao,
                'medicacao_qtd' => $row->medicacao_qtd,
                'medicacao_posologia' => $row->medicacao_posologia,
                'instituicao_nome' => $row->instituicao->nome,
            );
            

        }

        //VAMOS ORGANIZAR OS ITENS DO RECEITUARIO
        if(!empty($arrayReceitas)){

            foreach ($arrayReceitas as $key=>$receita) {

                if(!empty($receita[0]['tipo'])): 
                    $tipo = $receita[0]['tipo']; 
                else: 
                    $tipo = '';
                endif;

                if(!empty($receita[0]['nome_prestador'])): 
                    $nome_prestador = $receita[0]['nome_prestador']; 
                else: 
                    $nome_prestador = '';
                endif;

                if(!empty($receita[0]['data_pedido'])): 
                    $data_pedido = $receita[0]['data_pedido']; 
                else: 
                    $data_pedido = '';
                endif;

                if(!empty($receita[0]['descricao'])): 
                    $precricao_old = $receita[0]['descricao']; 
                else: 
                    $precricao_old = '';
                endif;

                if(!empty($receita[0]['instituicao_nome'])): 
                    $instituicao_nome = $receita[0]['instituicao_nome']; 
                else: 
                    $instituicao_nome = '';
                endif;
                

                //VAMOS JUNTAR A PRESCRIÇÃO

                    $chave_consulta = $receita[0]['codigo_pedido'];

                    $prescricao = '';
                    
                    if(!empty($receita)): 
                        foreach($receita as $val){
                            $prescricao.= $val['descricao'].'<br><br>';
                        }
                    endif;


                $receitasFinal[] = array(
                    'grupo' => 'exame',
                    'chave_consulta' => $chave_consulta,
                    'tipo' => $tipo,
                    'nome_prestador' => $nome_prestador,
                    'data_pedido' => $data_pedido,
                    'prescricao' => $prescricao,
                    'instituicao_nome' => $instituicao_nome,
                );

            }


        }

        
        return $receitasFinal;


    }

    public function visualizar(Request $request)
    {


         //EXIBIR RECEITUARIOS
         if($request->documentoGrupo == 'receita'){

            return $this->visualizar_receituarios($request);
        
        //EXIBIR ATESTADOS;
        }else if($request->documentoGrupo == 'atestado'){
        
            return $this->visualizar_atestados($request);

        //EXIBIR EXAMES;
        }else if($request->documentoGrupo == 'exame'){
        
            return $this->visualizar_exames($request);
        }

            

    }

    public function visualizar_receituarios($request)
    {


         //VISUALIZAR MODELO NOVO
         if($request->documentoTipo == 'novo'):
        
            $documentos = InstituicaoDocumento::query()
                ->where('tipo', 'novo')
                ->where('grupo', 'receita')
                ->where('codigo_pedido', $request->documentoChave)
                ->get();
    
            else:
            //VISUALIZAR MODELO ANTIGO
            $documentos = InstituicaoDocumento::query()
                ->where('tipo', 'antigo')
                ->where('grupo', 'receita')
                ->where('id_externo', $request->documentoChave)
                ->get();
    
    
            endif;
    
            //SELECIONANDO A INSTITUIÇÃO
            $documentos->load(['instituicao:instituicoes.id,nome']);
    
            //VAMOS JUNTAR OS ITENS DO RECEITUARIO
            $arrayReceitas =  array();
            $receitasFinal =  array();
    
            foreach ($documentos as $row) {
    
             if($row->tipo == 'novo'){
                 
                   $arrayReceitas[$row->codigo_pedido][] = array(
                    'id_externo' => $row->id_externo,
                    'codigo_pedido' => $row->codigo_pedido,
                    'tipo' => $row->tipo,
                    'nome_prestador' => $row->nome_prestador,
                    'descricao' => $row->descricao,
                    'data_pedido' => $row->data_pedido->format('d/m/Y'),
                    'descricao' => $row->descricao,
                    'medicacao_qtd' => $row->medicacao_qtd,
                    'medicacao_posologia' => $row->medicacao_posologia,
                    'instituicao_nome' => $row->instituicao->nome,
                );
    
                }else{
                    
                    $arrayReceitas[][] = array(
                        'id_externo' => $row->id_externo,
                        'codigo_pedido' => $row->codigo_pedido,
                        'tipo' => $row->tipo,
                        'nome_prestador' => $row->nome_prestador,
                        'descricao' => $row->descricao,
                        'data_pedido' => $row->data_pedido->format('d/m/Y'),
                        'descricao' => $row->descricao,
                        'medicacao_qtd' => $row->medicacao_qtd,
                        'medicacao_posologia' => $row->medicacao_posologia,
                        'instituicao_nome' => $row->instituicao->nome,
                    );
    
                }
    
            }
    
    
            //VAMOS ORGANIZAR OS ITENS DO RECEITUARIO
            if(!empty($arrayReceitas)){
    
                foreach ($arrayReceitas as $key=>$receita) {
    
                    if(!empty($receita[0]['tipo'])): 
                        $tipo = $receita[0]['tipo']; 
                    else: 
                        $tipo = '';
                    endif;
    
                    if(!empty($receita[0]['nome_prestador'])): 
                        $nome_prestador = $receita[0]['nome_prestador']; 
                    else: 
                        $nome_prestador = '';
                    endif;
    
                    if(!empty($receita[0]['data_pedido'])): 
                        $data_pedido = $receita[0]['data_pedido']; 
                    else: 
                        $data_pedido = '';
                    endif;
    
                    if(!empty($receita[0]['descricao'])): 
                        $precricao_old = $receita[0]['descricao']; 
                    else: 
                        $precricao_old = '';
                    endif;
    
                    if(!empty($receita[0]['instituicao_nome'])): 
                        $instituicao_nome = $receita[0]['instituicao_nome']; 
                    else: 
                        $instituicao_nome = '';
                    endif;
                    
    
                    //VAMOS JUNTAR A PRESCRIÇÃO
                    if($tipo == 'novo'){
    
                        $chave_consulta = $receita[0]['codigo_pedido'];
    
                        $prescricao = '';
                        
                        if(!empty($receita)): 
                            foreach($receita as $val){
                                $prescricao.= $val['descricao'].'....................'.$val['medicacao_qtd'].'<br>'.$val['medicacao_posologia'].'<br><br>';
                            }
                        endif;
    
                    }else{
                        $chave_consulta = $receita[0]['id_externo'];
                        $prescricao = $precricao_old;
                    }
    
                    $receitaFinal[] = array(
                        'chave_consulta' => $chave_consulta,
                        'tipo' => $tipo,
                        'nome_prestador' => $nome_prestador,
                        'data_pedido' => $data_pedido,
                        'prescricao' => $prescricao,
                        'instituicao_nome' => $instituicao_nome,
                    );
    
                }
    
    
            }



            /*VAMOS PEGAR OS MEDICAMENTOS ENCONTRADOS PARA SUGERIR*/
            $medicamentos_encontrados = Produto::query()
                ->buscarMedicamentos($receitaFinal[0]['prescricao'])
                ->get();

            $receitaFinal[1] = new DocumentosProdutosCollection($medicamentos_encontrados);
            /* FIM PEGANDO MEDICAMENTOS */
    
    
            return $receitaFinal; 
    
    
            

    }


        public function visualizar_atestados($request)
    {

        $documentos = InstituicaoDocumento::query()
                ->where('grupo', 'atestado')
                ->where('id_externo', $request->documentoChave)
                ->get();

        $documentos->load(['instituicao:instituicoes.id,nome']);

        foreach ($documentos as $row) {

                  $arrayDocumentos[] = array(
                   'grupo' => 'atestado',
                   'chave_consulta' => $row->id_externo,
                   'tipo' => 'novo',
                   'nome_prestador' => $row->nome_prestador,
                   'data_pedido' => $row->data_pedido->format('d/m/Y'),
                   'prescricao' => $row->descricao,
                   'instituicao_nome' => $row->instituicao->nome,
               );
        }

        return $arrayDocumentos;

    }

    public function visualizar_exames($request)
    {


         //VISUALIZAR MODELO NOVO

            $documentos = InstituicaoDocumento::query()
                ->where('grupo', 'exame')
                ->with('procedimentos_instituicao')
                ->where('codigo_pedido', $request->documentoChave)
                ->get();
    
           
    
            //SELECIONANDO A INSTITUIÇÃO
            $documentos->load(['instituicao:instituicoes.id,nome,imagem']);
            

            
    
            //VAMOS JUNTAR OS ITENS DO RECEITUARIO
            $arrayReceitas =  array();
            $receitasFinal =  array();

            $inicio_documentos = 0;

            foreach ($documentos as $row) {

                    //VERIFICANDO SE RESULTADO DE LAUDO ESTÁ PRONTO
                    if($row->tipo != 'LABORATORIO'):
                    $resultado = $this->verificaExisteResultado($row->id);
                    if(!empty($resultado['RETORNO'])):
                        $documentos[$inicio_documentos]->resultado_pronto = 1;
                    else:
                        $documentos[$inicio_documentos]->resultado_pronto = 0;
                    endif;
                  else:
                    $documentos[$inicio_documentos]->resultado_pronto = 0;
                  endif;


                    //VERIFICANDO SE RESULTADO DE LABORATORIO ESTA PRONTO

                  if($row->tipo == 'LABORATORIO'):
                    $resultado = $this->verificaExisteResultadoLaboratorio($row->codigo_pedido);
                    if(!empty($resultado['RETORNO'])):
                        $documentos[$inicio_documentos]->resultado_laboratorio = $this->verificaExisteResultadoLaboratorio($row->codigo_pedido);
                    else:
                        $documentos[$inicio_documentos]->resultado_laboratorio = 0;
                    endif;
                  else:
                    $documentos[$inicio_documentos]->resultado_laboratorio = 0;
                  endif;
    
                 
                   $arrayReceitas[$row->codigo_pedido][] = array(
                    'id_externo' => $row->id_externo,
                    'codigo_pedido' => $row->codigo_pedido,
                    'tipo' => $row->tipo,
                    'nome_prestador' => $row->nome_prestador,
                    'descricao' => $row->descricao,
                    'data_pedido' => $row->data_pedido->format('d/m/Y'),
                    'descricao' => $row->descricao,
                    'medicacao_qtd' => $row->medicacao_qtd,
                    'medicacao_posologia' => $row->medicacao_posologia,
                    'instituicao_nome' => $row->instituicao->nome,
                    'instituicao_imagem' => asset(Storage::cloud()->url($row->instituicao->imagem_300px)),
                );
    
                $inicio_documentos++;
    
            }
            // return $documentos;
    
            //VAMOS ORGANIZAR OS ITENS DO RECEITUARIO
            if(!empty($arrayReceitas)){
    
                foreach ($arrayReceitas as $key=>$receita) {
    
                    if(!empty($receita[0]['tipo'])): 
                        $tipo = $receita[0]['tipo']; 
                    else: 
                        $tipo = '';
                    endif;
    
                    if(!empty($receita[0]['nome_prestador'])): 
                        $nome_prestador = $receita[0]['nome_prestador']; 
                    else: 
                        $nome_prestador = '';
                    endif;
    
                    if(!empty($receita[0]['data_pedido'])): 
                        $data_pedido = $receita[0]['data_pedido']; 
                    else: 
                        $data_pedido = '';
                    endif;
    
                    if(!empty($receita[0]['descricao'])): 
                        $precricao_old = $receita[0]['descricao']; 
                    else: 
                        $precricao_old = '';
                    endif;
    
                    if(!empty($receita[0]['instituicao_nome'])): 
                        $instituicao_nome = $receita[0]['instituicao_nome']; 
                    else: 
                        $instituicao_nome = '';
                    endif;
                    
    
                    //VAMOS JUNTAR A PRESCRIÇÃO
                    
                        $chave_consulta = $receita[0]['codigo_pedido'];
    
                        $prescricao = '';
                        
                        if(!empty($receita)): 
                            foreach($receita as $val){
                                $prescricao.= $val['descricao'].'<br>';
                            }
                        endif;
    
                    
    
                    $receitaFinal[] = array(
                        'chave_consulta' => $chave_consulta,
                        'tipo' => $tipo,
                        'nome_prestador' => $nome_prestador,
                        'data_pedido' => $data_pedido,
                        'prescricao' => $prescricao,
                        'instituicao_nome' => $instituicao_nome,
                        'instituicao_imagem' => $receita[0]['instituicao_imagem'],
                        'itens' => $documentos
                    );
    
                }
    
    
            }
    
    
            return $receitaFinal; 
    
    
            

    }

    public function visualizarResultado(Request $request)
    {

        if($request->documentoTipo == 'exame'){

                $documento = InstituicaoDocumento::query()
                ->where('id', $request->documentoChave)
                ->get();

                // return $documento;
        
                //BUSCANDO OS DADOS NA API DA SANTA CASA
                    $dados = Http::asForm()
                    ->post('http://painel.santacasamontesclaros.com.br:85/ApiAppSC/app/arquivo_imagem', [
                        'chave' => '295bf3771c4790e7As5fd681',
                        'caminho' => $documento[0]->url
                    ])
                    ->json(); 

                    return $dados;

        }else if($request->documentoTipo == 'laboratorio'){
            return $this->verificaExisteResultadoLaboratorio($request->documentoChave);
        }



    }


    public function verificaExisteResultado($id)
    {

        $documento = InstituicaoDocumento::query()
                ->where('id', $id)
                ->get();

                
      //BUSCANDO OS DADOS NA API DA SANTA CASA
        $dados = Http::asForm()
        ->post('http://painel.santacasamontesclaros.com.br:85/ApiAppSC/app/arquivo_imagem', [
            'chave' => '295bf3771c4790e7As5fd681',
            'caminho' => $documento[0]->url
        ])
        ->json(); 

        return $dados;

    }

    public function verificaExisteResultadoLaboratorio($id){



        //BUSCANDO O RESULTADO DE LABORATORIO
        $dados = Http::asForm()
        ->post('http://painel.santacasamontesclaros.com.br:85/ApiAppSC/app/resultado_lab', [
            'chave' => '295bf3771c4790e7As5fd681',
            'cd_pedido' => $id,
            
        ])
        ->json(); 

        $result = collect($dados['RETORNO']);
        $aux['RETORNO'] = $result->groupBy('NM_EXAME');


        // $aux['RETORNO'][0] = [] 

        // dd($aux);
        return $aux;

    }

    public function visualizarResultadoHtml(Request $request)
    {

        //BUSCANDO OS DADOS NA API DA SANTA CASA
        $dados = Http::asForm()
        ->post('http://painel.santacasamontesclaros.com.br:85/ApiAppSC/app/arquivo_imagem', [
            'chave' => '295bf3771c4790e7As5fd681',
            'caminho' => '\\SCMCSCL\laudos_read\2018-03-23\1895841.html'
        ]); 


        echo $dados;

    }

    public function cargaMedicamentos(Request $request){
        

        //BUSCANDO OS DADOS NA API DA SANTA CASA
        $dados = Http::asForm()
        ->post('http://painel.santacasamontesclaros.com.br:85/ApiAppSC/app/medicamento', [
            'chave' => '295bf3771c4790e7As5fd681'
        ])
        ->json(); 

        foreach($dados['RETORNO'] as $composicoes){


            $array_composicoes[] = array(
                'componente' => $composicoes['DESCRICAO'],
                'codigo_externo' => $composicoes['CODIGO'],
            );


        }


        DB::table('medicamentos')->insert($array_composicoes);
    }

    public function cargaExames(Request $request){

        //BUSCANDO OS DADOS NA API DA SANTA CASA
        $dados = Http::asForm()
        ->post('http://painel.santacasamontesclaros.com.br:85/ApiAppSC/app/tabela_exame', [
            'chave' => '295bf3771c4790e7As5fd681',
            // 'tipo' => 'LAB',
            'tipo' => 'IMA',
        ])
        ->json(); 

        return $dados;

        // foreach($dados['RETORNO'] as $composicoes){


        //     $array_composicoes[] = array(
        //         'componente' => $composicoes['DESCRICAO'],
        //         'codigo_externo' => $composicoes['CODIGO'],
        //     );


        // }


        // DB::table('medicamentos')->insert($array_composicoes);
    }

    public function cargaLaboratorio(Request $request){

        // $dados = Http::asForm()
        //     ->post('http://painel.santacasamontesclaros.com.br:85/ApiAppSC/app/dados_prontuario', [
        //         'prontuario' => '28218',
        //         'dti' => '01/01/2000',
        //         'dtf' => date('d/m/Y')
        //     ])
        //     ->json(); 

        //     return $dados;
        // echo 'aui';

        //BUSCANDO TABELAS DE CADASTROS DE AGENDAS
        // $dados = Http::asForm()
        // ->post('http://painel.santacasamontesclaros.com.br:85/ApiAppSC/app/resultado_lab', [
        //     'chave' => '295bf3771c4790e7As5fd681',
        //     'cd_pedido' => '326501',
            
        // ])
        // ->json(); 

        // return $dados;

    }

    public function cargaAgenda(Request $request){
        /*
        API Tabelas da Agenda:
        Link: http://painel.santacasamontesclaros.com.br:85/ApiAppSC/app/tabelas_agenda
        parâmetros: { tipo , cod }
        tipos: 
        __ SERV : Lista os serviços
        __ SET : Lista os setores
        __ PREST : Lista os prestadores


        API Dados da Agenda:
        Link: http://painel.santacasamontesclaros.com.br:85/ApiAppSC/app/dados_agenda
        parâmetros: { dti *, dtf *, setor *, prestador, servico  }
        * Campos Obrigatórios

        258 - CENTRAL DE CONSULTAS

        tipos:  
        __ CONV : Lista os convenios

        */

        //BUSCANDO TABELAS DE CADASTROS DE AGENDAS
        $dados = Http::asForm()
        ->post('http://painel.santacasamontesclaros.com.br:85/ApiAppSC/app/tabelas_agenda', [
            'chave' => '295bf3771c4790e7As5fd681',
            'tipo' => 'PREST',
        ])
        ->json(); 

        return $dados;



        //BUSCANDO AGENDAS
        // $dados = Http::asForm()
        // ->post('http://painel.santacasamontesclaros.com.br:85/ApiAppSC/app/dados_agenda', [
        //     'chave' => '295bf3771c4790e7As5fd681',
        //     'setor' => '258',
        //     'dti' => '21/01/2021',
        //     'dtf' => '22/01/2021',
        // ])
        // ->json(); 

        // return $dados;
    }

    public function getGrupos(Request $request)
    {
        $usuario = $request->user('sanctum');
        $arrayIds = array();
        
        //VAMOS PEGAR OS IDS DAS INSTITUICOES VINCULADAS DO USUARIO
        $usuarioInstituicoes = $usuario->instituicao()
                                   //->select('instituicao_has_pacientes.id')
                                   ->where('usuario_id', $usuario->id)
                                   ->get();
        
        if(!empty($usuarioInstituicoes)){
            foreach($usuarioInstituicoes as $usuarioInstituicao){
                $arrayIds[] = $usuarioInstituicao->pivot->id;
            }
        }
        /* FIM PEGANDO OS IDS */
        
        $dadosGrupos = InstituicaoDocumento::whereIn('instituicao_has_pacientes_id', $arrayIds)
            ->where('grupo', 'exame')
            ->get();

        $dados = [];
        $grupos = null;
        foreach ($dadosGrupos as $key => $value) {
            // if(!$dados[$value->tipo]){
            if(!array_key_exists($value->tipo, $dados)){
                
                $grupos[] = [
                    'id' => $value->tipo,
                    'nome' => $value->tipo
                ];

                $dados[$value->tipo] = 1;
                
            }
        }
  
        return response()->json($grupos);
    }

}
