    <div class="col-md-12 my-2 text-right no_print" style="padding-left:0px;padding-top:15px;">
        <button type="button" class="btn btn-outline-secondary" data-toggle="collapse" data-target="#collapseColunas" aria-expanded="false" aria-controls="collapseColunas" style="border: 1px solid #ced4da;">
            <i class="fa fa-fw fa-filter" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Filtar exibição de colunas"></i>
        </button>

        <div class="collapse my-2 text-left no_print" id="collapseColunas">
            <div class="card card-body">
                <h4 class="lead card-title">Escolha quais colunas deseja exibir</h4>
                <hr>
                <div class="row">
                    <div class="col-md-2">
                        <input class="colunaTabela" type="checkbox" id="exibeSemDesconto" checked/>
                        <label for="exibeSemDesconto"> Repasse s desconto</label>
                    </div>

                    <div class="col-md-2">
                        <input class="colunaTabela" type="checkbox" id="exibeComDesconto" checked/>
                        <label for="exibeComDesconto"> Repasse c desconto</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="cabecalho" style="display: none;">
        <h4>Relatório de repasse simples</h4>
    </div>    
    
    <table id="demo-foo-row-toggler" class="table table-bordered" data-toggle-column="first" style="margin: 0px">
        <thead>
            <tr>
                <th >Cod Agenda</th>
                <th >Profissional/agenda</th>
                <th >Paciente</th>
                <th >Data</th>
                <th >Convênio</th>          
                <th >Serviço / Procedimento</th>
                <th >Tipo repasse</th>
                <th class="colunaSemDesconto">Repasse s desconto</th>
                <th class="colunaComDesconto">Repasse c desconto</th>
            </tr>
        </thead>
        <tbody>
            @php
                // dd($agendamentos[0]);
                
                $total = 0;
                $total_desconto = 0;
                $total_sem_desconto = 0;
                $total_geral_desconto = 0;
                $total_com_desconto = 0;
            @endphp
            @foreach($agendamentos as $item)
                @foreach ($item->agendamentoProcedimento as $procedimento)    
                    <tr >
                        <td>{{$item->id}}</td>
                        <td >{{$item->instituicoesAgenda->prestadores->prestador->nome}}</td>
                        <td >{{$item->pessoa->nome}}</td>
                        <td >{{date('d/m/Y H:i', strtotime($item->data))}}</td>    
                    
                        @php
                            $total_procedimento = $item->agendamentoProcedimento->sum('valor_atual') + $item->agendamentoProcedimento->sum('valor_convenio');
                            $desconto_porcentagem = 0;
                            $desconto_por_procedimentos = 0;
                            
                            if($item->desconto > 0){
                                $desconto_porcentagem = (($item->desconto * 100)/$total_procedimento);
                                $desconto_por_procedimentos = $desconto_porcentagem/$item->agendamentoProcedimento->count();
                            }
                        @endphp
                        
                        <td >{{$procedimento->procedimentoInstituicaoConvenioTrashed->convenios->nome}}</td>
                        <td >
                            {{$procedimento->procedimentoInstituicaoConvenioTrashed->procedimentoInstituicao->procedimento->descricao}}
                        </td>
                        
                        <td>
                            @if ($procedimento->tipo != null)
                                {{ucwords($procedimento->tipo)}}
                            @else
                                -
                            @endif
                        </td>

                        <td class="colunaSemDesconto">
                            @if (count($item->contaReceber) > 0)
                                @if (($item->contaReceber[0]->forma_pagamento == "cartao_credito" || $item->contaReceber[0]->forma_pagamento == "cartao_debito") && $procedimento->valor_repasse_cartao > 0)
                                    @if ($procedimento->tipo_cartao == 'porcentagem')
                                        @php
                                            $repasse = ($procedimento->valor_atual+$procedimento->valor_convenio) * ($procedimento->valor_repasse_cartao/100);

                                            $total += $repasse;
                                        @endphp
                                        {{number_format($repasse, 2, ',', '.')}}
                                    @else
                                        {{number_format($procedimento->valor_repasse_cartao, 2, ',', '.')}}

                                        @php
                                            $total += $procedimento->valor_repasse_cartao;
                                        @endphp
                                    @endif

                                @else
                                    @if ($procedimento->tipo == 'porcentagem')
                                        @php
                                            $repasse = ($procedimento->valor_atual+$procedimento->valor_convenio) * ($procedimento->valor_repasse/100);

                                            $total += $repasse;
                                        @endphp
                                        {{number_format($repasse, 2, ',', '.')}}
                                    @else
                                        {{number_format($procedimento->valor_repasse, 2, ',', '.')}}

                                        @php
                                            $total += $procedimento->valor_repasse;
                                        @endphp
                                    @endif
                                @endif
                            @else
                                @if ($procedimento->tipo == 'porcentagem')
                                    @php
                                        $repasse = ($procedimento->valor_atual+$procedimento->valor_convenio) * ($procedimento->valor_repasse/100);

                                        $total += $repasse;
                                    @endphp
                                    {{number_format($repasse, 2, ',', '.')}}
                                @else
                                    {{number_format($procedimento->valor_repasse, 2, ',', '.')}}

                                    @php
                                        $total += $procedimento->valor_repasse;
                                    @endphp
                                @endif
                            @endif
                        </td>

                        <td class="colunaComDesconto">
                            @if (count($item->contaReceber) > 0)
                                @if (($item->contaReceber[0]->forma_pagamento == "cartao_credito" || $item->contaReceber[0]->forma_pagamento == "cartao_debito") && $procedimento->valor_repasse_cartao > 0)
                                    @if ($procedimento->tipo_cartao == 'porcentagem')
                                        @php
                                            $valor_repasse_novo = 0;
                                            
                                            $valor_procedimento_retirar = (($procedimento->valor_atual+$procedimento->valor_convenio) * $desconto_por_procedimentos)/100;
                                            $valor_procedimento_novo = ($procedimento->valor_atual+$procedimento->valor_convenio) - $valor_procedimento_retirar;
                                            $valor_repasse_novo = (($valor_procedimento_novo)*$procedimento->valor_repasse_cartao/100);
                                            $total_desconto += $valor_repasse_novo;
                                        @endphp
                                        {{number_format($valor_repasse_novo, 2, ",",".")}}
                                    @else
                                        @php
                                            $valor_repasse_novo = 0;
                                            if($procedimento->valor_repasse_cartao > 0 && $item->desconto > 0){
                                            
                                                $porcento_repasse = (($procedimento->valor_atual+$procedimento->valor_convenio)/($procedimento->valor_repasse_cartao * 100));
                                                $valor_procedimento_retirar = (($procedimento->valor_atual+$procedimento->valor_convenio) * $desconto_por_procedimentos)/100;
                                                $valor_procedimento_novo = ($procedimento->valor_atual+$procedimento->valor_convenio) - $valor_procedimento_retirar;
                                                $valor_repasse_real = ($valor_procedimento_novo*$porcento_repasse);
                                                $valor_repasse_novo = $procedimento->valor_repasse_cartao - $valor_repasse_real;
                                                
                                                $total_desconto += $valor_repasse_novo;
                                            }else{
                                                $valor_repasse_novo = $procedimento->valor_repasse_cartao;
                                                $total_desconto += $valor_repasse_novo;
                                            }
                                        @endphp
                                    
                                        {{number_format($valor_repasse_novo, 2, ',', '.')}}
                                    @endif
                                @else
                                    @if ($procedimento->tipo == 'porcentagem')
                                        @php
                                            $valor_repasse_novo = 0;
                                            
                                            $valor_procedimento_retirar = (($procedimento->valor_atual+$procedimento->valor_convenio) * $desconto_por_procedimentos)/100;
                                            $valor_procedimento_novo = ($procedimento->valor_atual+$procedimento->valor_convenio) - $valor_procedimento_retirar;
                                            $valor_repasse_novo = (($valor_procedimento_novo)*$procedimento->valor_repasse/100);
                                            $total_desconto += $valor_repasse_novo;
                                        @endphp
                                        {{number_format($valor_repasse_novo, 2, ",",".")}}
                                    @else
                                        @php
                                            $valor_repasse_novo = 0;
                                            if($procedimento->valor_repasse > 0 && $item->desconto > 0){
                                            
                                                $porcento_repasse = (($procedimento->valor_atual+$procedimento->valor_convenio)/($procedimento->valor_repasse * 100));
                                                $valor_procedimento_retirar = (($procedimento->valor_atual+$procedimento->valor_convenio) * $desconto_por_procedimentos)/100;
                                                $valor_procedimento_novo = ($procedimento->valor_atual+$procedimento->valor_convenio) - $valor_procedimento_retirar;
                                                $valor_repasse_real = ($valor_procedimento_novo*$porcento_repasse);
                                                $valor_repasse_novo = $procedimento->valor_repasse - $valor_repasse_real;
                                                
                                                $total_desconto += $valor_repasse_novo;
                                            }else{
                                                $valor_repasse_novo = $procedimento->valor_repasse;
                                                $total_desconto += $valor_repasse_novo;
                                            }
                                        @endphp
                                    
                                        {{number_format($valor_repasse_novo, 2, ',', '.')}}
                                    @endif
                                @endif
                            @else
                                @if ($procedimento->tipo == 'porcentagem')
                                    @php
                                        $valor_repasse_novo = 0;
                                        
                                        $valor_procedimento_retirar = (($procedimento->valor_atual+$procedimento->valor_convenio) * $desconto_por_procedimentos)/100;
                                        $valor_procedimento_novo = ($procedimento->valor_atual+$procedimento->valor_convenio) - $valor_procedimento_retirar;
                                        $valor_repasse_novo = (($valor_procedimento_novo)*$procedimento->valor_repasse/100);
                                        $total_desconto += $valor_repasse_novo;
                                    @endphp
                                    {{number_format($valor_repasse_novo, 2, ",",".")}}
                                @else
                                    @php
                                        $valor_repasse_novo = 0;
                                        if($procedimento->valor_repasse > 0 && $item->desconto > 0){
                                        
                                            $porcento_repasse = (($procedimento->valor_atual+$procedimento->valor_convenio)/($procedimento->valor_repasse * 100));
                                            $valor_procedimento_retirar = (($procedimento->valor_atual+$procedimento->valor_convenio) * $desconto_por_procedimentos)/100;
                                            $valor_procedimento_novo = ($procedimento->valor_atual+$procedimento->valor_convenio) - $valor_procedimento_retirar;
                                            $valor_repasse_real = ($valor_procedimento_novo*$porcento_repasse);
                                            $valor_repasse_novo = $procedimento->valor_repasse - $valor_repasse_real;
                                            
                                            $total_desconto += $valor_repasse_novo;
                                        }else{
                                            $valor_repasse_novo = $procedimento->valor_repasse;
                                            $total_desconto += $valor_repasse_novo;
                                        }
                                    @endphp
                                
                                    {{number_format($valor_repasse_novo, 2, ',', '.')}}
                                @endif
                            @endif
                            
                        </td>                        
                    </tr>                        
                @endforeach                
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td >
                    <b>Total Repasse</b>
                </td>
                <td colspan="6">
                    
                </td>
               
                <td  class="colunaSemDesconto">
                    <b>{{number_format($total, 2, ',', '.')}}</b>
                </td>
                <td class="colunaComDesconto">
                    <b>{{number_format($total_desconto, 2, ',', '.')}}</b>
                </td>
            </tr>
            <tr>
                <td >
                    <b>Pacientes atendidos</b>
                </td>
                <td colspan="7"></td>
                <td >
                    <b>{{count($agendamentos)}}</b>
                </td>
            </tr>
        </tfoot>
    </table>

    <script>
        $(document).ready(function() {
    
            $('[data-toggle="tooltip"]').tooltip()
    
            $("#exibeSemDesconto").on('change', function(){
                
                if($(this).is(':checked')){
                    $(".colunaSemDesconto").css("display", "")
                }else{
                    $(".colunaSemDesconto").css("display", "none")
                }
            })
    
            $("#exibeComDesconto").on('change', function(){
                if($(this).is(':checked')){
                    $(".colunaComDesconto").css("display", "")
                }else{
                    $(".colunaComDesconto").css("display", "none")
                }
            })
        });    
    </script>