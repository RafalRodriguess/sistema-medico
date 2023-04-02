{{-- @php
    dd($faturamento->toArray())
@endphp --}}
{{-- @endphp pq esse end aq ??? so fiz pra te mostrar--}}
<div class="card-body">

    <input type="hidden" id="idlote" value="{{$faturamento->id}}">


    {{-- ********POR HORA OCULTANDO OS FILTROS PARA INSERÇÃO MANUAL POIS SERÁ TUDO AUTOMATICO VIA CRON  --}}


    <form action="javascript:void(0)" id="formFiltros">
        @csrf
        {{-- <div class="row">
           
            <div class="col-sm-12">
                <div class="form-group">
                <label for="">Prestadores:</label>
                <br>
                <select class="form-control select2" style="width: 100%" name="prestadores[]" id="prestadores" multiple>
                    @foreach($prestadores as $prestador)
                    <option value="{{$prestador->prestador->id}}" 
                    <?php echo (isset($dadosBusca) && in_array($prestador->id, $dadosBusca['prestadores'])) ? 'selected' : '' ?> 
                    <?php echo (!isset($dadosBusca)) ? 'selected' : '' ?>
                    {{$prestador->prestador->nome}}
                    </option>
                    @endforeach
                </select>
            </div>
           </div>
           
            <div class="col-sm-12">
                <div class="form-group">
                <label for="">Convênios:</label>
                <br>
                <select style="width: 100%;" id="selectConvenio" name="convenios[]" class="form-control select2" multiple>
                    @foreach($convenios as $convenio)
                    <option value="{{$convenio->id}}" 
                    <?php echo (isset($dadosBusca) && in_array($convenio->id, $dadosBusca['convenios'])) ? 'selected' : '' ?> 
                    <?php echo (!isset($dadosBusca)) ? 'selected' : '' ?>>
                    {{$convenio->nome}}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>
            
        </div> --}}


        {{-- <div class="row">

            <div class="col-md-3">
                <div class="form-group">
                <label>Data e hora início atendimento:</label>
                <input class="form-control" type="datetime-local" name="data_inicio" value="<?php echo (isset($dadosBusca['data_inicio'])) ? $dadosBusca['data_inicio'] : date('Y-m-d') . 'T' . '00:00:00'; ?>" id="example-datetime-local-input">
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                <label>Data e hora fim atendimento:</label>
                <input class="form-control" type="datetime-local" name="data_fim" value="<?php echo (isset($dadosBusca['data_fim'])) ? $dadosBusca['data_fim'] : date('Y-m-d') . 'T' . date('H:i') . ':00'; ?>" id="example-datetime-local-input">
                </div>
            </div>

            
        </div>

        <div class="row">
            <div class="col-md-3">
                <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="ti-back-left"></i> Pesquisar</button>
            </div>
        </div> --}}

    </form>

    {{-- <hr> --}}



    <div class="row">

        




        {{-- <div class="col-lg-6">

            <div class="card">

                <button onclick="transferir_guias_lote()" style="top: 10px;left: 4px;background: #26c6da;border: 1px solid #26c6da;" type="button" class="btn waves-effect waves-light btn-success m-r-10">
                <i class="ti-arrow-right"></i> 
                Transferir guias selecionada(s) para lote
                </button>

                <div class="card-body">
                    <h4 class="card-title">Guias de atendimento a adicionar ao lote</h4>
                    <h6 class="card-subtitle">Confira os filtros acima para adição de guias </h6>
                    <div class="table-responsive">
                        <table class="table color-table info-table tabela-filtros" id="table_agendamentos">
                            <!-- RESULTADOS DOS FILTROS OBTIDOS -->
                        </table>
                    </div>
                </div>
            </div>
        </div> --}}
        <div class="col-lg-12">
            <div class="card">

                <div class="card-body">

                    {{-- GUIAS PENDENTES DE LOTES ANTERIORES --}}
                    @if(sizeof($guias_pendentes) > 0 && $faturamento->status == 0)


                    <div class="row">
                        <div class="col-md-10">
                            {{-- <h4 class="card-title">Guias de asdfadf pertecentes ao lote</h4> --}}
                        <h6 class="card-subtitle">Atendimento(s) com pendência  <code>não enviada em lotes anteriores</code></h6>
                        </div>
            
                        
                        
                    </div>

                    <div class="table-responsive">
                        <table class="table color-table warning-table">
                            {{-- <thead>
                                <tr>
                                    <th style="background: #26c6da;border: 1px solid #26c6da;">Situação</th>
                                    <th style="background: #26c6da;border: 1px solid #26c6da;">Cod Atend.</th>
                                    <th style="background: #26c6da;border: 1px solid #26c6da;">Paciente</th>
                                    <th style="background: #26c6da;border: 1px solid #26c6da;">Atendimento</th>
                                    <th style="background: #26c6da;border: 1px solid #26c6da;">Procedimento(s)</th>
                                    <th style="background: #26c6da;border: 1px solid #26c6da;">Ações</th>
                                </tr>
                            </thead> --}}


                            <tbody>
                                @foreach($guias_pendentes as $guia_pendente)
                                <tr style="background: #fbf9c9">
                                    <td>Não enviado</td>
                                    <td>{{$guia_pendente->agendamento_paciente->id}} ({{$guia_pendente->agendamento_paciente->instituicoesAgenda->prestadores->prestador->nome}})</td>
                                    <td>{{$guia_pendente->agendamento_paciente->pessoa->nome}}</td>
                                    <td>{{ date("d/m/Y", strtotime($guia_pendente->agendamento_paciente->data))}} - {{ date("H:i", strtotime($guia_pendente->agendamento_paciente->data))}}</td>
                                    <td>
                                        @php
                                        $total_procedimentos = sizeof($guia_pendente->agendamento_paciente->agendamentoProcedimento);
                                        $percorrendo = 0;
                                        @endphp

                                        @foreach($guia_pendente->agendamento_paciente->agendamentoProcedimento as $procedimento)
                                         {{$procedimento->procedimentoInstituicaoConvenio->procedimentoInstituicao->procedimento->cod}}
                                         -
                                         {{$procedimento->procedimentoInstituicaoConvenio->procedimentoInstituicao->procedimento->descricao}}

                                         ({{$procedimento->procedimentoInstituicaoConvenio->convenios->nome}})

                                         @php
                                         $percorrendo++;
                                         if($percorrendo != $total_procedimentos):
                                          echo '<br>';
                                         endif;
                                         @endphp

                                        @endforeach
                                    </td>

                                    <td>
                                        <form action="{{ route('instituicao.faturamento.addGuiasPendenteLote', [$faturamento]) }}" method="post" class="d-inline form-add-registro-lote">
                                            @csrf
                                            <input type="hidden" name="faturamento_protocolo_id_old" value="{{$guia_pendente->faturamento_protocolo_id}}">
                                            <input type="hidden" name="faturamento_protocolo_id" value="{{$faturamento->id}}">
                                            <input type="hidden" name="agendamento_id" value="{{$guia_pendente->agendamento_paciente->id}}">
                                            <button type="button" class="btn btn-xs btn-secondary btn-add-registro-lote"  aria-haspopup="true" aria-expanded="false"
                                            data-toggle="tooltip" data-placement="top" data-original-title="Adicionar atendimento a este lote">
                                                    <i class="ti-import"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            
                        </table>
                    </div>
                    

                    @endif
                    {{-- FIM GUIAS PENDENTES --}}


                    {{-- <h4 class="card-title">Guias de atendimento pertecentes ao lote</h4>
                    <h6 class="card-subtitle">Guias com pendência marcadas com <code>vermelho</code></h6> --}}

                    <div class="row">
                        <div class="col-md-10">
                            <h4 class="card-title">Guias de atendimento pertecentes ao lote</h4>
                    <h6 class="card-subtitle">Guias com pendência marcadas com <code>vermelho</code></h6>
                        </div>
            
                        {{-- @can('habilidade_instituicao_sessao', 'cadastrar_convenio') --}}
                            {{-- <div class="col-md-2">
                                <div class="form-group" style="margin-bottom: 0px !important;">
                                    <a href="{{ route('instituicao.convenio.create') }}">
                                    <button type="button" class="btn waves-effect waves-light btn-block btn-info">Incluir pendente(s)</button>
                                    </a>
                                </div>
                            </div> --}}
                        {{-- @endcan --}}
                    </div>

                    <div class="table-responsive">
                        <table class="table color-table warning-table">
                            <thead>
                                <tr>
                                    <th style="background: #26c6da;border: 1px solid #26c6da;">Situação</th>
                                    <th style="background: #26c6da;border: 1px solid #26c6da;">Cod Atend.</th>
                                    <th style="background: #26c6da;border: 1px solid #26c6da;">Paciente</th>
                                    <th style="background: #26c6da;border: 1px solid #26c6da;">Atendimento</th>
                                    <th style="background: #26c6da;border: 1px solid #26c6da;">Procedimento(s)</th>
                                    <th style="background: #26c6da;border: 1px solid #26c6da;">Ações</th>
                                </tr>
                            </thead>


                            <tbody>
                                @if(!empty($guias))

                                {{-- INICIANDO OS ÍNDEICES DE SOMATORIAS POR CONVENIOS --}}
                                @foreach($guias as $guia)
                                @foreach($guia->agendamento_paciente->agendamentoProcedimento as $procedimento)
                                @php
                                $soma_convenio[$procedimento->procedimentoInstituicaoConvenio->convenios->nome] = 0;
                                @endphp
                                @endforeach
                                @endforeach

                                @foreach($guias as $guia)

                                @php
                                //GUIA EM ABERTO
                                if($guia->status == 0):
                                    $css_atendimento = 'style="background: #f9f9f9;"';
                                    $css_guias = '';
                                    $situacao = 'Pendente';
                                elseif($guia->status == 4 || $guia->status == 5):
                                    $css_atendimento = 'style="background: #e6e290;"';
                                    $css_guias = 'style="background: #f6f2a8;"';
                                    $situacao = 'Removida';
                                endif
                                //GUIA REMOVIDA DO LOTE
                                @endphp

                                <tr @php echo $css_atendimento @endphp>
                                    <td>{{$situacao}}</td>
                                    <td>{{$guia->agendamento_paciente->id}} ({{$guia->agendamento_paciente->instituicoesAgenda->prestadores->prestador->nome}})</td>
                                    <td>{{$guia->agendamento_paciente->pessoa->nome}}</td>
                                    <td>{{ date("d/m/Y", strtotime($guia->agendamento_paciente->data))}} - {{ date("H:i", strtotime($guia->agendamento_paciente->data))}}</td>
                                    <td>
                                        @php
                                        $total_procedimentos = sizeof($guia->agendamento_paciente->agendamentoProcedimento);
                                        $percorrendo = 0;
                                        @endphp

                                        @if(!empty($guias))
                                        @foreach($guia->agendamento_paciente->agendamentoProcedimento as $procedimento)
                                         {{$procedimento->procedimentoInstituicaoConvenio->procedimentoInstituicao->procedimento->cod}}
                                         -
                                         {{$procedimento->procedimentoInstituicaoConvenio->procedimentoInstituicao->procedimento->descricao}}

                                         ({{$procedimento->procedimentoInstituicaoConvenio->convenios->nome}})

                                         @php
                                         //INCREMENTANDO OS ITENS
                                         $soma_convenio[$procedimento->procedimentoInstituicaoConvenio->convenios->nome] = $soma_convenio[$procedimento->procedimentoInstituicaoConvenio->convenios->nome] + 1;

                                         $percorrendo++;
                                         if($percorrendo != $total_procedimentos):
                                          echo '<br>';
                                         endif;
                                         @endphp

                                        @endforeach
                                        @endif
                                    </td>

                                    <td>
                                        {{-- MARCANDO PARA NAO ENVIAR ATENDIMENTO NESTE LOTE E FICANDO COMO PENDENTE --}}
                                        @if($faturamento->status == 0 && $guia->status == 0)
                                        <form action="{{ route('instituicao.faturamento.removerGuiasLote', [$faturamento]) }}" method="post" class="d-inline form-remove-registro">
                                            @csrf
                                            <input type="hidden" name="faturamento_protocolo_id" value="{{$faturamento->id}}">
                                            <input type="hidden" name="agendamento_id" value="{{$guia->agendamento_paciente->id}}">
                                            <button type="button" class="btn btn-xs btn-secondary btn-remove-registro"  aria-haspopup="true" aria-expanded="false"
                                            data-toggle="tooltip" data-placement="top" data-original-title="Remover atendimento deste lote">
                                                    <i class="ti-share-alt"></i>
                                            </button>
                                        </form>
                                        @endif

                                    </td>
                                </tr>
                                {{-- EXIBINDO A GUIA QUE SERÁ ENVIADA A SANCOOP --}}
                                @if($guia->agendamento_paciente->agendamentoGuias)
                                @foreach($guia->agendamento_paciente->agendamentoGuias as $guia_tipo)
                                <tr @php echo $css_guias @endphp>
                                    <td colspan="4">
                                      <strong>  Tipo da Guia: {{strtoupper($guia_tipo->tipo_guia)}} </strong>
                                    </td>
                                    <td colspan="2">
                                       <strong>  Cod. carteirinha: {{ ($guia->agendamento_paciente->carteirinha) ?  strtoupper($guia->agendamento_paciente->carteirinha->carteirinha) : '' }} </strong>
                                    </td>
                                </tr>
                                

                                        {{-- TEMOS QUE PERCORRER OS PROCEDIMENTOS PARA EXIBIR O CORRETO DE ACORTO COM CADA TIPO DE GUIA QUE SERÁ ENVIADO --}}
                                        @foreach($guia->agendamento_paciente->agendamentoProcedimento as $procedimento_agenda)

                                        <tr @php echo $css_guias @endphp>
                                        

                                        @php

                                        // dd($procedimento_agenda)

                                        //MONTAR GUIA DO TIPO CONSULTA
                                        if($guia_tipo->tipo_guia == 'consulta'
                                        // && $procedimento_agenda->procedimentoInstituicaoConvenio->convenios->divisao_tipo_guia == 2
                                        && $procedimento_agenda->procedimentoInstituicaoConvenio->procedimentoInstituicao->procedimento->tipo_guia == 1):

                                            echo '<td colspan="2">Procedimento: '. $procedimento_agenda->procedimentoInstituicaoConvenio->procedimentoInstituicao->procedimento->cod.'</td>';
                                            echo '<td colspan="2">Qtd:'. $procedimento_agenda->qtd_procedimento.'</td>';
                                            echo '<td colspan="2">Autorização: '. $guia_tipo->cod_aut_convenio.'</td>';


                                        //MONTAR GUIA DO TIPO SADT
                                        elseif($guia_tipo->tipo_guia == 'sadt'):


                                            //REGRA PARA CONSULTA JUNTO SÓ SE O CONVENIO PERMITIR EM SADT
                                            if($procedimento_agenda->procedimentoInstituicaoConvenio->convenios->divisao_tipo_guia == 1
                                            && $procedimento_agenda->procedimentoInstituicaoConvenio->procedimentoInstituicao->procedimento->tipo_guia == 1):

                                                echo '<td colspan="2">Procedimento:'. $procedimento_agenda->procedimentoInstituicaoConvenio->procedimentoInstituicao->procedimento->cod.'</td>';
                                                echo '<td colspan="2">Qtd:'. $procedimento_agenda->qtd_procedimento.'</td>';
                                                echo '<td colspan="2">Autorização:'. $guia_tipo->cod_aut_convenio.'</td>';


                                            //REGRA PARA EXAMES EM GERAL
                                            elseif($procedimento_agenda->procedimentoInstituicaoConvenio->procedimentoInstituicao->procedimento->tipo_guia == 2):

                                                echo '<td colspan="2">Procedimento:'. $procedimento_agenda->procedimentoInstituicaoConvenio->procedimentoInstituicao->procedimento->cod.'</td>';
                                                echo '<td colspan="2">Qtd:'. $procedimento_agenda->qtd_procedimento.'</td>';
                                                echo '<td colspan="2">Autorização:'. $guia_tipo->cod_aut_convenio.'</td>';


                                            endif;

                                        endif;

                                        @endphp

                                        
                                        </tr>

                                        @endforeach
                                        
                                    

                                @endforeach
                                @endif
                                

                                @endforeach

                                {{-- RODAPÉ COM RESUMOS --}}

                                <tr style="background: #b3d7db;border: 1px solid #b3d7db;">
                                    <td colspan="4">&nbsp;</td>
                                    <td colspan="2" style="color: #fff;">Total procedimentos protocolo</td>
                                </tr>

                                @if(!empty($guias))
                                @foreach($soma_convenio as $key => $value)

                                <tr>
                                    <td colspan="4">&nbsp;</td>
                                    <td>
                                        <b>
                                        @php
                                        echo $key;
                                        @endphp
                                        </b>
                                    </td>
                                    <td>
                                        <b>
                                        @php
                                        echo $value;
                                        @endphp
                                        </b>
                                    </td>
                                </tr>

                                @endforeach
                                @endif

                                <tr>
                                    <td colspan="4">&nbsp;</td>
                                    <td><b>Total de Atendimentos</b></td>
                                    <td>
                                        <b>
                                        @php
                                        echo sizeof($guias);
                                        @endphp
                                        </b>
                                    </td>
                                </tr>

                                @endif
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


   
</div>



@push('scripts')
    <script>


        function imprimir(){
            window.print();
        }


        //MARCANDO TODAS AS GUIAS PARA TRANSFERIR PARA O LOTE

        function marca_all_agendamentos(){
            var input = $('#check_agendamentos_all');
            var estado = input[0].checked;
                console.log(estado)
                $('#table_agendamentos input:checkbox').each(function(i, chk) {
                    chk.checked = estado;
                });;
               
        }

        // TRANSFERINDO TODAS GUIAS SELECIONADAS PARA O LOTE
        function transferir_guias_lote(){
            if (confirm('Confirmar transmissão de guias selecionada(s)?')) {
            var id = '';
            var obj = $(".checks_agendamentos:checked");
            if (obj.length > 0) {
                obj.each(function() {
                    id += ';' + $(this).val();
                });
                id = id.substr(1);

                $.ajax("{{ route('instituicao.faturamento.adicionarGuias') }}", {
                    type: 'POST',
                    data: {
                        idsagendamentos: id,
                        idlote : $('#idlote').val(),
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function(result) {
                        console.log(result)
                        if (result == 'erro') {
                            // alert('Ocorreu um erro ao enviar as guias. Entrar em contato imediadamente no suporte Asa Saúde (38) 9 9826 6833');
                            alert('Ocorreu um erro ao enviar as guias. Entrar em contato imediadamente no suporte Asa Saúde (38) 9 9826 6833 ')
                        } else {
                            // swal("Guia(s) enviadas!", "Acompanhe o processamento neste mesmo módulo.", "success");
                            alert('Guia(s) enviadas!", "Acompanhe o processamento neste mesmo módulo. ')
                        }

                        // setTimeout(function() {
                        //     location.reload(1);
                        // }, 2000);
                    }
                });
            } else {
                alert('Selecione pelo menos 1 atendimento!')
            }
        }
        }

        // $('.acao').on('click', '.gera_financeiro_modal', function(){
        //     var formData = new FormData($('#formRelatorioAtendimento')[0])
            
            
        //     var modal = 'modalVerFinanceiro';

        //     $.ajax("{{route('instituicao.relatorioAtendimento.verFinanceiro')}}", {
        //         method: "POST",
        //         data: formData,
        //         processData: false,
        //         contentType: false,
        //         beforeSend: () => {
        //             $('.loading').css('display', 'block');
        //             $('.loading').find('.class-loading').addClass('loader')
        //         },
        //         success: function (result) {
        //             $("#modalFinanceiro").html(result);
        //             $('#' + modal).modal();                    
        //         },
        //         complete: () => {
        //             $('.loading').css('display', 'none');
        //             $('.loading').find('.class-loading').removeClass('loader') 
        //         }
        //     });
            

        // })

        $('#formFiltros').on('submit', function(e){
            e.preventDefault()
            var formData = new FormData($(this)[0]);
            $.ajax("{{route('instituicao.faturamento.tabelaFiltros')}}", {
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: () => {
                    $('.loading').css('display', 'block');
                    $('.loading').find('.class-loading').addClass('loader')
                },
                success: function (result) {
                    console.log(result)
                    $(".tabela-filtros").html(result);
                    $(".imprimir").css('display', 'block')
                    ativarClass();
                },
                complete: () => {
                    $('.loading').css('display', 'none');
                    $('.loading').find('.class-loading').removeClass('loader') 
                }
            })
        })

        function ativarClass(){
            // $(".table-responsive").find('.accordion').find('#demo-foo-row-toggler').footable()
        }

        function limpa_filtros(elemento){
            $("#"+elemento).find("option").attr("selected", false);
            $("#"+elemento).val('').trigger('change');
        }

        function seleciona_filtros(elemento){
            if(elemento == "procedimentos"){
                $("#"+elemento).val([]);
                var dados = [];
                dados.push("todos")
                $("#"+elemento).val(dados)
                $("#"+elemento).trigger('change');
            }else{
                $("#"+elemento).val([]);
                var dados = [];
                $("#"+elemento).find("option").each(function(index, elem){
                    $(elem).attr("selected", true);
                    dados.push($(elem).val())
                })
                $("#"+elemento).val(dados)
                $("#"+elemento).trigger('change');
            }
        }


        /* REMOVENDO ATENDIMENTO DO LOTE */

        $('.form-remove-registro').on('click','.btn-remove-registro', function(e) {
            e.preventDefault();

            Swal.fire({   
                title: "Confirmar remoção?",   
                text: "Ao confirmar você estará removendo o atendimento do lote!",   
                icon: "warning",   
                showCancelButton: true,   
                confirmButtonColor: "#DD6B55",   
                confirmButtonText: "Sim, confirmar!",   
                cancelButtonText: "Não, cancelar!",
            }).then(function (result) {   
                if (result.value) {     
                    $(e.currentTarget).parents('form').submit();
                } 
            });
        });


        /* ADICIONANDO  ATENDIMENTO PENDENTE AO LOTE */

        $('.form-add-registro-lote').on('click','.btn-add-registro-lote', function(e) {
            e.preventDefault();

            Swal.fire({   
                title: "Confirmar adição?",   
                text: "Ao confirmar você estará adicionando o atendimento do lote!",   
                icon: "warning",   
                showCancelButton: true,   
                confirmButtonColor: "#DD6B55",   
                confirmButtonText: "Sim, confirmar!",   
                cancelButtonText: "Não, cancelar!",
            }).then(function (result) {   
                if (result.value) {     
                    $(e.currentTarget).parents('form').submit();
                } 
            });
        });
        
    </script>
@endpush
