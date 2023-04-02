<style>
    .table-scroll {
        height: 400px;
        overflow: auto;
        width: 100%;
        padding: 0px;      
        margin: 0px;
    }

    .table-scroll::-webkit-scrollbar{
        width: 5px;
    }
    .table-wrapper-scroll {
        display: block;
    }

    #tableRegistros input[type='checkbox']{
        position: relative;
        left: 0px;
        opacity: 1;
    }
</style>

<div class="col-md-12 my-2 text-right no_print" style="padding-left:0px;padding-top:15px;">
    <button type="button" class="btn btn-outline-secondary" data-toggle="collapse" data-target="#collapseColunas" aria-expanded="false" aria-controls="collapseColunas" style="border: 1px solid #ced4da;" data-toggle="tooltip" data-placement="top" title="Filtar exibição de colunas">
        <i class="fa fa-fw fa-filter" aria-hidden="true"></i>
    </button>

    <div class="collapse my-2 text-left" id="collapseColunas">
        <div class="card card-body">
            <h4 class="lead card-title">Escolha quais colunas deseja exibir</h4>
            <hr>
            <div class="row">
                <div class="col-md-2">
                    <input class="colunaTabela" data-name="convenio" type="checkbox" id="exibeConvenio" checked/>
                    <label for="exibeConvenio"> Convênio</label>
                </div>
                <div class="col-md-2">
                    <input class="colunaTabela" data-name="procedimento" type="checkbox" id="exibeProcedimento" checked />
                    <label for="exibeProcedimento"> Procedimento</label>
                </div>
                <div class="col-md-2">
                    <input class="colunaTabela" data-name="n_parcelas" type="checkbox" id="exibeNParcela" checked />
                    <label for="exibeNParcela"> Nº Parcelas</label>
                </div>
            </div>
        </div>
    </div>

    
    <a href="" id="btnExportExcel" target="_blank" class="btn btn-outline-secondary" style="border: 1px solid #ced4da;" data-toggle="tooltip" data-placement="top" title="Exportar para excel">
        <i class="fa fw fa-file-excel" aria-hidden="true"></i>
    </a>

    @can('habilidade_instituicao_sessao', 'exportar_caixa')

        <button type="button" class="btn btn-outline-secondary alterConta" style="border: 1px solid #ced4da;" data-toggle="tooltip" data-placement="top" title="Alterar conta caixa">
            <i class="mdi mdi-call-merge" aria-hidden="true"></i>
        </button>


    @endcan
</div>

<table id="tableRegistros" class="table table-bordered" data-toggle-column="first" >
    <thead>
        <tr>
            <th><input type="checkbox" class="form-control export_caixa_all"  style="display: none;" data-toggle="tooltip" data-placement="top" title="Marcar / Desmarcar todos"/></th>
            <th>Data pago/recebido</th>
            <th>Fornecedor/Paciente</th>
            <th class="colConvenio">Convênio</th>
            <th class="colProcedimento">Procedimento</th>
            <th>Conta Caixa</th>
            <th>Forma pagamento</th>
            <th class="colParcelas">Nº Parcelas</th>
            <th>Valor Pago/Recebido</th>
        </tr>
    </thead>
    
    <tbody>
        @php
            $convenios = array();
            $itens_procedimentos = array();
        @endphp

        @foreach($contasReceber as $item)
            <tr>
                <td>
                    <input type="checkbox" value="{{$item->id}}"  name="conta_id" class="form-control export_caixa"  style="display: none;"/>
                    <i class="fa fa-arrow-right"  style="color: #006400;" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Entrada"></i>
                </td>
                <td>{{\Carbon\Carbon::createFromFormat('Y-m-d',$item->data_pago)->format('d/m/Y')}}</td>
                
                <td>
                    @if ($item->tipo == 'paciente')
                        @if ($item->paciente)
                            Paciente: {{$item->paciente->nome}}
                        @elseif(!$item->pessoa_id)
                            Paciente avulso!
                        @endif
                    @elseif($item->tipo == 'convenio')
                        Convênio: {{$item->convenio->nome}}
                    @elseif($item->tipo == "movimentacao")
                        Movimentação: {{$item->descricao}}
                    @endif
                </td>
                
                @php
                    $texto_convenios = array();
                    $texto_procedimentos = array();

                    if($item->agendamentos){
                        foreach ($item->agendamentos->agendamentoProcedimento as $procedimentos) {
                            $part = ($item->agendamentos->valor_total > 0) ? $procedimentos->valor_atual * ($item->valor_pago/$item->agendamentos->valor_total) : 0;

                            $convenio = $procedimentos->procedimentoInstituicaoConvenioTrashed->convenios->nome;
                            
                            $convenios[$convenio] = (!empty($convenios[$convenio])) ? $convenios[$convenio] + $part : $part;

                            $proc = $procedimentos->procedimentoInstituicaoConvenioTrashed->procedimentoInstituicao->procedimentoTrashed->descricao;
                            
                            $itens_procedimentos[$proc] = (!empty($itens_procedimentos[$proc])) ? $itens_procedimentos[$proc] + $part : $part;

                            if(in_array($convenio, $texto_convenios)){
                                $texto_convenios[] = $convenio;
                            }

                            if(in_array($proc, $texto_procedimentos)){
                                $texto_procedimentos[] = $proc;
                            }
                        }
                    }
                @endphp
 
                <td class="colConvenio">
                    {{(!empty($item->agendamentos)) ? $item->agendamentos->agendamentoProcedimento[0]->procedimentoInstituicaoConvenioTrashed->convenios->nome : "-"}}                
                </td>
                <td class="colProcedimento">
                    {{(!empty($item->agendamentos)) ? $item->agendamentos->agendamentoProcedimento[0]->procedimentoInstituicaoConvenioTrashed->procedimentoInstituicao->procedimentoTrashed->descricao : "-"}}

                    @if(!empty($item->agendamentos) && $item->agendamentos->agendamentoProcedimento->count() > 1)
                        <span class="text-muted" data-toggle="tooltip" data-placement="top" title="" data-original-title="{{implode("; ", $texto_procedimentos)}}"><small>(+{{$item->agendamentos->agendamentoProcedimento->count()-1}})</small></span>
                    @endif
                </td>
                    
                <td>{{(!empty($item->contaCaixa)) ? $item->contaCaixa->descricao : "-"}}</td>
                <td>
                    @if(!empty($item->forma_pagamento))
                        {{App\ContaPagar::forma_pagamento_texto($item->forma_pagamento)}}
                    @elseif($item->tipo == "movimentacao")
                        Movimentação
                    @else
                        -
                    @endif
                </td>
                <td class="colParcelas">{{(!empty($item->agendamentos)) ? $item->agendamentos->parcelas : $item->num_parcela}}</td>

                <td style="color: #006400;"><b>{{number_format($item->valor_pago, 2, ",", ".")}}</b></td>
            </tr>
        @endforeach

        @foreach($contasPagar as $item)
            <tr>
                <td><i class="fa fa-arrow-left"  style="color: #8b0000;" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Saida"></i></td>
                <td>{{\Carbon\Carbon::createFromFormat('Y-m-d',$item->data_pago)->format('d/m/Y')}}</td>
                <td>
                    
                    @if ($item->tipo == 'paciente')
                        @if ($item->paciente)
                            Paciente: {{$item->paciente->nome}}
                        @elseif(!$item->pessoa_id)
                            Paciente avulso!
                        @endif
                    @elseif($item->tipo == 'fornecedor')
                        Fornecedor: {{!empty($item->fornecedor->nome_fantasia) ? $item->fornecedor->nome_fantasia : $item->fornecedor->nome}}
                    @elseif($item->tipo == 'prestador')
                        Prestador: {{$item->prestador->nome}}
                    @elseif($item->tipo == "movimentacao")
                        Movimentação: {{$item->descricao}}
                    @endif
                </td>
                <td class="colConvenio">-</td>
                <td class="colProcedimento">-</td>
                <td>{{(!empty($item->contaCaixa)) ? $item->contaCaixa->descricao : "-"}}</td>
                <td>
                    @if(!empty($item->forma_pagamento))
                        {{App\ContaPagar::forma_pagamento_texto($item->forma_pagamento)}}
                    @elseif($item->tipo == "movimentacao")
                        Movimentação
                    @else
                        -
                    @endif
                </td>
                <td class="colParcelas">{{(!empty($item->agendamentos)) ? $item->agendamentos->parcelas : $item->num_parcela}}</td>
                <td style="color: #8b0000;"><b>{{number_format($item->valor_pago, 2, ",", ".")}}</b></td>
            </tr>
        @endforeach
    </tbody>
</table>
@can('habilidade_instituicao_sessao', 'exportar_caixa')
    <div class="col-sm export_caixa my-2" style="display: none;">     
        <div class="row">        
            <div class="col-sm-4 form-group">
                <label class="form-label">Conta Caixa destino para altarar</label>
                <select class="form-control select2" style="width: 100%" name="conta_destino" id="conta_destino">
                    <option value="">Selecione a conta de destinho</option>
                    @foreach ($conta_caixa as $item)
                        <option value="{{$item->id}}">{{$item->descricao}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-2 form-group">
                <button type="button" class="btn btn-success waves-effect waves-light m-r-10" style="margin-top: 30px" id="confirmAlterCaixa">
                    <i class="mdi mdi-check" aria-hidden="true"></i> Confirmar
                </button>
            </div>
        </div>
    </div>
@endcan

<div class="row justify-content-md-center">
    <div class="col-sm-12 quebraPagina painel-financeiro">
        <div class="row  painel-financeiro justify-content-md-center">
            <div class="card card-body bg-primary text-center mx-2 col-sm-2">
                <label class="card-title text-white">Saldo Inicial</label>
                <h3 class="lead text-white">R$ {{number_format($saldo_inicial, 2, ",", ".")}}</h3>
            </div>
            <div class="card card-body text-center bg-success mx-2 col-sm-2">
                <label class="card-title text-white">Total de entradas</label>
                <div class="row">
                    <h3 class="lead text-white col-sm-9">R$ {{number_format($formaPagamento['entradas']->sum('valor'), 2, ",", ".")}}</h3>
                    @can('habilidade_instituicao_sessao', 'cadastrar_movimentacoes')
                        <div class="form-groupn col-sm-2">
                            <button type="button" class="text-white btn waves-effect waves-light btn-block geraMovimento" data-value="{{number_format($formaPagamento['entradas']->sum('valor'), 2, ",", ".")}}"><i class="mdi mdi-clipboard-flow" aria-hidden="true"></i></button>
                        </div>
                    @endcan
                </div>
            </div>
            <div class="card card-body text-center bg-danger mx-2 col-sm-2">
                <label class="card-title text-white">Total de saidas</label>
                <h3 class="lead text-white">R$ {{number_format($formaPagamento['saidas']->sum('valor'), 2, ",", ".")}}</h3>
            </div>
            <div class="card card-body text-center bg-secondary mx-2 col-sm-2">
                <label class="card-title text-white">Resultado final</label>
                <h3 class="lead text-white">R$ {{number_format($formaPagamento['entradas']->sum('valor') - $formaPagamento['saidas']->sum('valor'), 2, ",", ".")}}</h3>
            </div>
            <div class="card card-body text-center bg-info mx-2 col-sm-2">
                <label class="card-title text-white">Result acumulado</label>
                <h3 class="lead text-white">R$ {{number_format($saldo_inicial + $formaPagamento['entradas']->sum('valor') - $formaPagamento['saidas']->sum('valor'), 2, ",", ".")}}</h3>
            </div>
        </div>
    </div>
    
    <div class="col-sm">
        <table class="table table-bordered forma-pag-tabela">
            <thead>
                <tr>
                    <td></td>
                    <th>Forma de pagamento</th>
                    <th>Valor</th>
                    <th>Ação</th>
                </tr>
            </thead>

            <tbody>
                @foreach($formaPagamento['entradas'] as $entrada)
                    <tr>    
                        <td><i class="fa fa-arrow-right"  style="color: #006400;" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Entrada"></i></td>
                        <td>
                            @if(!empty($entrada->forma_pagamento))
                                {{App\ContaPagar::forma_pagamento_texto($entrada->forma_pagamento)}}
                            @elseif($entrada->tipo == "movimentacao")
                                Movimentação
                            @else
                                -
                            @endif
                        </td>
                        <td style="color: #006400;"><b>{{number_format($entrada->valor, 2, ",", ".")}}</b></td>
                        <td>
                            @can('habilidade_instituicao_sessao', 'cadastrar_movimentacoes')
                                <div class="form-groupn">
                                    <button type="button" class="btn waves-effect waves-light btn-block geraMovimento" data-value="{{number_format($entrada->valor, 2, ",", ".")}}"><i class="mdi mdi-clipboard-flow" aria-hidden="true"></i></button>
                                </div>
                            @endcan
                        </td>
                    </tr>
                @endforeach

                @foreach($formaPagamento['saidas'] as $saida)
                    <tr>    
                        <td><i class="fa fa-arrow-left"  style="color: #8b0000;" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Saida"></i></td>
                        <td>
                            @if(!empty($saida->forma_pagamento))
                                {{App\ContaPagar::forma_pagamento_texto($saida->forma_pagamento)}}
                            @elseif($saida->tipo == "movimentacao")
                                Movimentação
                            @else
                                -
                            @endif
                        </td>
                        <td style="color: #8b0000;"><b>{{number_format($saida->valor, 2, ",", ".")}}</b></td>
                        <td></td>
                    </tr>
                @endforeach
            </tbody>

            <tfoot>
                <tr>
                    <th><i class="fa fa-plus-circle" aria-hidden="true"></i></th>
                    <th>Total</th>
                    <th><b>{{number_format($formaPagamento['entradas']->sum('valor') - $formaPagamento['saidas']->sum('valor'), 2, ",", ".")}}</b></th>
                </tr>
            </tfoot>
        </table>
    </div>
    
    <div class="col-sm">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Convenio</th>
                    <th>Valor</th>
                </tr>
            </thead>

            <tbody>
                @foreach($convenios as $convenio => $valor)
                    <tr>    
                        <td>{{$convenio}}</td>
                        <td><b>{{number_format($valor, 2, ",", ".")}}</b></td>
                    </tr>
                @endforeach
            </tbody>

            <tfoot>
                <tr>
                    <th>Total</th>
                    <th><b>{{number_format(array_sum($convenios), 2, ",", ".")}}</b></th>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="col-sm-5">
        <table class="table table-bordered table-scroll table-wrapper-scroll" id="tableScroll">
            <thead>
                <tr>
                    <th>Procedimento</th>
                    <th>Valor</th>
                </tr>
            </thead>

            <tbody>
                @foreach($itens_procedimentos as $proc => $valor)
                    <tr>    
                        <td>{{$proc}}</td>
                        <td><b>{{number_format($valor, 2, ",", ".")}}</b></td>
                    </tr>
                @endforeach
            </tbody>

            <tfoot>
                <tr>
                    <th>Total</th>
                    <th><b>{{number_format(array_sum($itens_procedimentos), 2, ",", ".")}}</b></th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<script>
    $(document).ready(function() {

        $('[data-toggle="tooltip"]').tooltip()

        $("#exibeNParcela").on('change', function(){
            
            if($(this).is(':checked')){
                $(".colParcelas").css("display", "")
            }else{
                $(".colParcelas").css("display", "none")
            }
        })

        $("#exibeConvenio").on('change', function(){
            if($(this).is(':checked')){
                $(".colConvenio").css("display", "")
            }else{
                $(".colConvenio").css("display", "none")
            }
        })

        $("#exibeProcedimento").on('change', function(){
            if($(this).is(':checked')){
                $(".colProcedimento").css("display", "")
            }else{
                $(".colProcedimento").css("display", "none")
            }
        })

        link();
    });

    $(".forma-pag-tabela").on("click", ".geraMovimento", function(e){
        valor = $(this).attr('data-value')
        geraMovimento(valor)
    })

    $(".painel-financeiro").on("click", ".geraMovimento", function(e){
        valor = $(this).attr('data-value')
        geraMovimento(valor)
    })

    function link(){
        var inicio = $("#data_inicio").val();
        var fim = $("#data_fim").val();
        var contas = $("#contas").val();

        href = "{{route('instituicao.relatoriosFluxoCaixa.exportExcel')}}"+"/?data_inicio="+inicio+"&data_fim="+fim+"&contas="+contas;

        $("#btnExportExcel").attr("href", href);
    }

    

    $('#exportExcel').on('click', function(){       
        
        var inicio = $("#data_inicio").val();
        var fim = $("#data_fim").val();
        var contas = $("#contas").val();

        $.ajax("{{route('instituicao.relatoriosFluxoCaixa.exportExcel')}}", {
            method: "GET",
            data: {
                "_token": "{{ csrf_token() }}",
                "data_inicio": inicio,
                "data_fim": fim,
                "contas": contas,
            },
            beforeSend: () => {
                $('.loading').css('display', 'block');
                $('.loading').find('.class-loading').addClass('loader')
            },
            success: function (e) {
                window.open(window.URL.createObjectURL(e),'_blank');
            },
            complete: () => {
                $('.loading').css('display', 'none');
                $('.loading').find('.class-loading').removeClass('loader');
            }
        })
    });

    function geraMovimento(valor = null){
        var contas = $("#contas").val();
        
        if(contas.length == 1 ){
            var modal = 'modalMovimentacao';
            var inicio = $("#data_inicio").val();
            var fim = $("#data_fim").val();


            var url = "{{route('instituicao.relatoriosFluxoCaixa.showMovimentacao', ['data_inicio'=>'-inicio','data_fim'=>'-fim','contas'=>'_contas','valor'=>'_valor'])}}".replace('-inicio', inicio).replace('-fim', fim).replace('_contas', contas).replace('_valor', valor);
            console.log(url);

            window.open(url.replace(/&amp;/g, "&"), 'Movimentacao', 'width=1024, height=860');
            

            // $.ajax("{{route('instituicao.relatoriosFluxoCaixa.showMovimentacao')}}", {
            //     method: "GET",
            //     data: {
            //         '_token': "{{csrf_token()}}",
            //         'data_inicio': $("#data_inicio").val(),
            //         'data_fim': $("#data_fim").val(),
            //         'contas': contas,
            //         'valor': valor,
            //     },
            //     beforeSend: () => {
            //         $('.loading').css('display', 'block');
            //         $('.loading').find('.class-loading').addClass('loader');
            //     },
            //     success: function (result) {
            //         $("#modalGeraMovimento").html(result);
            //         $('#' + modal).modal();                    
                    
            //         // $(".tabela").html(result);
            //         // $(".imprimir").css('display', 'block')
            //     },
            //     complete: () => {
            //         $('.loading').css('display', 'none');
            //         $('.loading').find('.class-loading').removeClass('loader') ;
            //     }
            // })

        }else{
            $(".geraMovimento").attr("disabled", true);
            $.toast({
                heading: 'Erro',
                text: "Escolha apenas uma conta para gerar a transferência",
                position: 'top-right',
                loaderBg: '#ff6849',
                icon: 'error',
                hideAfter: 9000,
                stack: 10
            });
        }
    }

    $(".alterConta").on('click', function(){
        if($('.alterConta').hasClass("clickado")){
            $(".export_caixa").css("display", "none");
            $(".export_caixa_all").css("display", "none");
            $(".alterConta").removeClass("clickado");
        }else{
            $(".export_caixa").css("display", "block");
            $(".export_caixa_all").css("display", "block");
            $(".alterConta").addClass("clickado");
        }
    });

    $(".export_caixa_all").on("change", function(){
        if($(this).is(":checked")){
            $(".export_caixa").prop("checked", true);
        }else{
            $(".export_caixa").prop("checked", false);
        }
    })

    $("#confirmAlterCaixa").on('click', function(){
        
        var contas_selecionadas = $("input[name='conta_id']:checked").map(function(){
            return this.value;
        }).get();

        var conta_caixa_destino = $("#conta_destino").val()
        Swal.fire({
            title: "Atenção!",
            text: "Deseja alterar o caixa das contas a receber em massa?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Sim, confirmar!",
            cancelButtonText: "Não, cancelar!",
        }).then(function(result) {
            if(result.isConfirmed){
                $.ajax("{{route('instituicao.relatoriosFluxoCaixa.altararCaixa')}}", {
                    method: "POST",
                    data: {
                        '_token': "{{csrf_token()}}",
                        'contas': contas_selecionadas,
                        'conta_destino': conta_caixa_destino,
                    },
                    beforeSend: () => {
                        $('.loading').css('display', 'block');
                        $('.loading').find('.class-loading').addClass('loader');
                    },
                    success: function (response) {
                        $.toast({
                            heading: response.title,
                            text: response.text,
                            position: 'top-right',
                            loaderBg: '#ff6849',
                            icon: response.icon,
                            hideAfter: 3000,
                            stack: 10
                        });
                        $("#formRelatorio").submit();
                    },
                    complete: () => {
                        $('.loading').css('display', 'none');
                        $('.loading').find('.class-loading').removeClass('loader') ;
                    }
                })
            }
        });

        console.log(contas, conta_caixa_destino);

    })


</script>