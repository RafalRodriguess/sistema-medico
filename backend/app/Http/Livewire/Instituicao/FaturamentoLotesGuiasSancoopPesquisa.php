<?php

namespace App\Http\Livewire\Instituicao;

use App\Instituicao;
use App\FaturamentoLote;
use App\FaturamentoLoteGuia;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FaturamentoLotesGuiasSancoopPesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;
    
    public $pesquisa = '';

    public $instituicao;

    private $dados;

    private  $prestadores;
    private  $convenios;
    public  $faturamento;
    private  $guias;
    private  $guias_pendentes;

    protected $updatesQueryString = [
        'pesquisa' => ['except' => ''],
    ];

    public function mount(Request $request, FaturamentoLote $faturamento)
    {
        $this->instituicao = Instituicao::find($request->session()->get('instituicao'));
        $this->faturamento = $faturamento;

        /*SANCOOP */
        $dadosLoteCompleto = FaturamentoLote::where('id', $faturamento->id)
                                        ->with(['prestador' => function ($q) use ($faturamento) {
                                           $q->where('id', $faturamento->prestadores_id);
                                         }])
                                        ->first()
                                        ->toArray();

                                        // echo '<pre>';
                                        // print_r($dadosLoteCompleto);
                                        // exit;

        //VERIFICANDO SE É FATURAMENTO DA SANCOOP
        // if($dadosLoteCompleto['tipo'] == 2 && $dadosLoteCompleto['status'] == 0):
           if($dadosLoteCompleto['tipo'] == 2):

            //CONSULTANDO E ATUALIZANDO PROTOCOLO
            $dadosLoteSancoop = $this->consultarLoteNaSancoop($dadosLoteCompleto['cod_externo'],$dadosLoteCompleto['prestador']['cpf']);

            //ATUALIZANDO PARA GUIAS TRANSMITIDAS E AGUARDANDO GUIAS FÍSICAS
            if(!empty($dadosLoteSancoop) && $dadosLoteSancoop->Situação == 'PENDENTE'):
                $atualizar_status_protocolo['status'] = 1;
                $faturamento->update($atualizar_status_protocolo);
            elseif(!empty($dadosLoteSancoop) && $dadosLoteSancoop->Situação == 'ENTREGUE'):
                $atualizar_status_protocolo['status'] = 2;
                $faturamento->update($atualizar_status_protocolo);
            endif;


            //CONSULTANDO E ATUALIZANDO GUIAS ****TERMINAR AQUIII TB
            $dadosGuiasLoteSancoop = $this->consultarGuiasLoteNaSancoop($dadosLoteCompleto['cod_externo']);


        endif;

        /*SANCOOP */

    }

    public function render()
    {
        config(["auth.defaults.guard" => "instituicao"]);
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_lotes');
        $this->performQuery();
        return view('livewire.instituicao.faturamento-lotes-guias-sancoop-pesquisa', [
            'prestadores' => $this->prestadores,
            'convenios' => $this->convenios,
            'guias' => $this->guias,
            'guias_pendentes' => $this->guias_pendentes,
        ]);
    }

    private function performQuery(): void
    {

        
        //PEGANDO AS GUIAS
        $this->guias = $this->faturamento->guias()->with([
                                                'agendamento_paciente',
                                                'agendamento_paciente.pessoa',
                                                'agendamento_paciente.agendamentoGuias',
                                                'agendamento_paciente.instituicoesAgenda.prestadores.prestador',
                                                'agendamento_paciente.agendamentoProcedimento',
                                                'agendamento_paciente.agendamentoProcedimento.procedimentoInstituicaoConvenio.convenios',
                                                'agendamento_paciente.agendamentoProcedimento.procedimentoInstituicaoConvenio.procedimentoInstituicao.procedimento',
                                            ])
                                         ->get();

                                         

                                        //  foreach($this->guias as $key=>$valor):
                                        //     $this->guias->$key->teste = 'ok';
                                        //  endforeach;

                                        //  dd($this->guias->toArray());
                                        //  exit;

        //PEGANDO PRESTADORES INSTITUIÇÃO
        $this->prestadores = $this->instituicao->prestadores()
                                               ->with('prestador')
                                               ->get();


        //PEGANDO CONVÊNIOS INSTITUIÇÃO
        $this->convenios = $this->instituicao->convenios()
                                             ->get();

        // dd($this->guias->toArray());
        // exit;

        //PEGANDO GUIAS PENDENTES CASO QUEIRA INCLUIR NO LOTE
        $this->guias_pendentes = FaturamentoLoteGuia::where('faturamento_protocolos_guias.status', 4)
                                   ->join('faturamento_protocolos', 'faturamento_protocolos.id', 'faturamento_protocolo_id')
                                   ->where('instituicao_id', $this->instituicao->id)
                                   ->with('agendamento_paciente')
                                   ->with('agendamento_paciente.pessoa')
                                   ->with('agendamento_paciente.agendamentoGuias')
                                   ->with('agendamento_paciente.instituicoesAgenda.prestadores.prestador')
                                   ->with('agendamento_paciente.agendamentoProcedimento')
                                   ->with('agendamento_paciente.agendamentoProcedimento.procedimentoInstituicaoConvenio.convenios')
                                   ->with('agendamento_paciente.agendamentoProcedimento.procedimentoInstituicaoConvenio.procedimentoInstituicao.procedimento')
                                   ->get();

        //                              var_dump($this->guias_pendentes);
        // exit;
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }

    //CONSULTANDO A SITUAÇÃO DO LOTE NA SANCOOP
    public function consultarLoteNaSancoop($protocolo, $cpfprestador)
    {

        //OBTENDO TOKEN DE AUTORIZAÇÃO
        // CODIGOS INSTITUICOES NA SANCOOP - 79 angios - 3623 santa casa
        $parameters['ID'] = 181;
        $parameters['Hash'] = 'TWVkLlNpb3MuMjJAIUAj';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://websios.sancoop.com.br:9001/Token');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($parameters));
    
        $headers = [
            "Content-Type: application/json"
        ];
    
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
        $return['result'] = json_decode(curl_exec($ch));
        $return['code_http'] = curl_getinfo($ch, CURLINFO_HTTP_CODE);


        /* CASO TENHA AUTENTICADO */
        if(!empty($return['result']->token)):

            //CONSULTANDO API SANCOOP PARA VERIFICAR SE EXISTE O COOPERADO
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'http://websios.sancoop.com.br:9001/Protocolo?CodProtocolo='.$protocolo.'&CPF='.$cpfprestador.'');
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            // curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($parameters));
        
            $headers = [
                "Content-Type: application/json",
                "Authorization: Bearer {$return['result']->token}"
            ];
        
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
            $return['result'] = json_decode(curl_exec($ch));
            $return['code_http'] = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            curl_close($ch);
            

            if(!empty($return['result']->Protocolo)):
                return $return['result']->Protocolo[0];
            else:
                return 0;
            endif;

        endif;

    }

    //CONSULTANDO AS GUIAS DO LOTE NA SANCOOP
    public function consultarGuiasLoteNaSancoop($protocolo)
    {

        //OBTENDO TOKEN DE AUTORIZAÇÃO
        // CODIGOS INSTITUICOES NA SANCOOP - 79 angios - 3623 santa casa
        $parameters['ID'] = 181;
        $parameters['Hash'] = 'TWVkLlNpb3MuMjJAIUAj';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://websios.sancoop.com.br:9001/Token');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($parameters));
    
        $headers = [
            "Content-Type: application/json"
        ];
    
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
        $return['result'] = json_decode(curl_exec($ch));
        $return['code_http'] = curl_getinfo($ch, CURLINFO_HTTP_CODE);


        /* CASO TENHA AUTENTICADO */
        if(!empty($return['result']->token)):

            //CONSULTANDO API SANCOOP PARA VERIFICAR SE EXISTE O COOPERADO
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'http://websios.sancoop.com.br:9001/Guias?CodProtocolo='.$protocolo.'');
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            // curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($parameters));
        
            $headers = [
                "Content-Type: application/json",
                "Authorization: Bearer {$return['result']->token}"
            ];
        
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
            $return['result'] = json_decode(curl_exec($ch));
            $return['code_http'] = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            curl_close($ch);

            // echo '<pre>';
            // print_r($return);
            // exit;
            

            if(!empty($return['result']->Guias)):
                return $return['result']->Guias;
            else:
                return 0;
            endif;

        endif;

    }
}
