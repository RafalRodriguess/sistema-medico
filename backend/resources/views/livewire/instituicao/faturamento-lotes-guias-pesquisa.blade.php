<div class="card-body">

    <input type="hidden" id="idlote" value="{{$faturamento->id}}">

    <form action="javascript:void(0)" id="formFiltros">
        @csrf
        <div class="row">
           
            <div class="col-sm-12">
                <div class="form-group">
                <label for="">Prestadores:</label>
                <br>
                <select class="form-control select2" style="width: 100%" name="prestadores[]" id="prestadores" multiple>
                    @foreach($prestadores as $prestador)
                    <option value="{{$prestador->prestador->id}}" 
                    <?php echo (isset($dadosBusca) && in_array($prestador->id, $dadosBusca['prestadores'])) ? 'selected' : '' ?> 
                    <?php echo (!isset($dadosBusca)) ? 'selected' : '' ?>>
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
            
        </div>


        <div class="row">
            
        </div>

        <div class="row">

            

            

            {{-- <div class="col-md-2">

                <div class="form-group" style="margin-bottom: 0px !important;">
                    <label for="">Status do pedido:</label>
                    <select name="status" class="form-control">
                        <option value="0">Todos Status</option>
                        <option <?= (isset($dadosBusca) && $dadosBusca['status'] == 'pendente') ? 'selected' : '' ?> value="pendente">pendente</option>
                        <option <?= (isset($dadosBusca) && $dadosBusca['status'] == 'aprovado') ? 'selected' : '' ?> value="aprovado">aprovado</option>
                        <option <?= (isset($dadosBusca) && $dadosBusca['status'] == 'finalizado') ? 'selected' : '' ?> value="finalizado">finalizado</option>
                    </select>
                </div>
            </div> --}}

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


            {{-- <div class="col-md-2">
                <label>Forma de pagamento:</label>
                <select name="tipo_pagamento" id="" class="form-control">
                    <option value="" selected>Todos</option>
                    <option value="credito">Crédito</option>
                    <option value="debito">Dédito</option>
                    <option value="dinheiro">Dinheiro</option>
                </select>

            </div> --}}

            
        </div>

        <div class="row">
            <div class="col-md-3">
                <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="ti-back-left"></i> Pesquisar</button>
            </div>
        </div>

    </form>

    <hr>



    <div class="row">
        <div class="col-lg-6">


           

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
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Guias de atendimento pertecentes ao lote</h4>
                    <h6 class="card-subtitle">Guias com pendência marcadas com <code>vermelho</code></h6>
                    <div class="table-responsive">
                        <table class="table color-table warning-table">
                            <thead>
                                <tr>
                                    <th>Cod</th>
                                    <th>Paciente</th>
                                    <th>Atendimento</th>
                                    <th>Prestador</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>


                            <tbody>
                                @if(!empty($guias))
                                @foreach($guias as $guia)
                                <tr>
                                    <td>{{$guia->agendamento_paciente->id}}</td>
                                    <td>{{$guia->agendamento_paciente->pessoa->nome}}</td>
                                    <td>{{ date("d/m/Y", strtotime($guia->agendamento_paciente->data))}}</td>
                                    <td>{{$guia->agendamento_paciente->instituicoesAgenda->prestadores->prestador->nome}}</td>
                                    <td>-</td>
                                </tr>
                                @endforeach
                                @endif;
                                
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
        
    </script>
@endpush
