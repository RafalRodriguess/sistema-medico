<table class='table table-sm table-bordered table-striped'>
    <thead>
        <tr>
            <th></th>
            <th>Id</th>
            <th>Paciente</th>
            <th>Descrição</th>
            <th>Caixa</th>
            <th>Data vencimento</th>            
            <th>Valor parcela</th>
            <th>Valor Total</th>
            <th>Status</th>
            <th>Data pagamento</th>
            <th>Valor pago</th>
            <th>Forma de pagamento</th>
        </tr>
    </thead>

    <tbody>
        @foreach($contas_receber as $item)
            <tr>
                <td>
                    @if(!empty($item->notaFiscal->status) && $item->notaFiscal->status !== "Cancelada")
                        <i class="mdi mdi-block-helper"></i>
                    @else
                        <div class="form-check text-center">
                            <input type="checkbox" class="form-check-input conta_selecionada @if(!empty($item->conta_pai)) conta_pai_{{$item->conta_pai}} @endif" value="{{($item->conta_pai) ? $item->conta_pai : $item->id}}" name="conta_id" style="position: relative; left: 0px; opacity: 1;" data-dados="{{$item}}">
                        </div>
                    @endif
                </td>
                <td>{{$item->id}}</td>
                <td>
                    @if($item->tipo == 'paciente')
                        @if(empty($item->paciente->nome))
                            {{dd($item->paciente)}}
                        @endif
                        {{($item->pessoa_id) ? "Paciente: ".$item->paciente->nome : "Paciente Avulso"}}
                    @elseif($item->tipo == 'convenio')
                        {{dd($item->convenio)}}
                        Convenio: {{$item->convenio->nome}}
                    @endif
                <td>{{$item->descricao}}</td>
                <td>{{$item->contaCaixa->descricao}}</td>
                <td>{{date("d/m/Y", strtotime($item->data_vencimento))}}</td>
                <td>{{number_format($item->valor_parcela,2,",",".")}}</td>
                <td>{{number_format($item->valor_total,2,",",".")}}</td>
                <td class="text-center">{{($item->status) ? "Pago" : "-"}}</td>
                <td class="text-center">{{(!empty($item->data_pago)) ? date("d/m/Y", strtotime($item->data_pago)) : "-"}}</td>
                <td>{{number_format($item->valor_pago,2,",",".")}}</td>
                <td>{{App\ContaReceber::forma_pagamento_texto($item->forma_pagamento)}}</td>
            </tr>
        @endforeach
    </tbody>    
</table>

<div class='col-sm-2'>
    <button type="button" class="btn btn-success waves-effect btn-select-paciente"><i class="mdi mdi-checkbox-marked-circle-outline"></i> Selecionar</button>
</div>

<script>
    $(".btn-select-paciente").on("click", function(){
        // dados = $(this).data("dados");

        var paciente = "";
        var texthtml = "";

        $(".conta_selecionada").each(function (index, element) {
            
            
            if($(element).is(':checked')){
                dados = $(element).data('dados');
                
                if(paciente == ""){
                    paciente = dados.paciente;
                }else if(paciente.id != dados.paciente.id){
                    $.toast({
                        heading: 'Erro',
                        text: 'Você deve selecionar apenas contas a receber do mesmo paciente!',
                        position: 'top-right',
                        loaderBg: '#ff6849',
                        icon: 'error',
                        hideAfter: 5000,
                        stack: 10
                    });

                    return texthtml = "";
                }

                texthtml = texthtml + "<div class='row'><div class='form-group col-sm-9'><label>conta a receber</label> <i class='mdi mdi-eye-outline modal_mostra_conta btn btn-secondary btn-sm ml-1' data-conta_rceber_id='"+dados.id+"'></i><input type='hidden' name='contas_receber[]' value='"+dados.id+"'><input type='text' class='form-control' readonly value='#"+dados.id+" "+dados.descricao+"'></div><div class='form-group col-sm-3'><label>Valor</label><input type='text' alt='decimal' class='form-control valor_parcela' readonly value='"+dados.valor_parcela+"'></div></div>";
            }
        })
        
        if(paciente != "" && texthtml != ""){
            $("#pessoa_id").val(paciente.id);
            $("#pessoa").val("#"+paciente.id+" "+paciente.nome+" ("+paciente.cpf+")");
            
            $("#cliente_uf").val(paciente.estado);
            $("#cliente_cidade").val(paciente.cidade);
            $("#cliente_bairro").val(paciente.bairro);
            $("#cliente_logradouro").val(paciente.rua);
            $("#cliente_numero").val(paciente.numero);
            $("#cliente_cep").val(paciente.cep);
            $("#cliente_complemento").val(paciente.complemento);

            $("#contasReceberSelec").html(texthtml);
            atualizaValorTotal();

            if($("#cliente_uf").val().length == 0 || $("#cliente_cidade").val().length == 0 || $("#cliente_bairro").val().length == 0 || $("#cliente_logradouro").val().length == 0 || $("#cliente_numero").val().length == 0 || $("#cliente_cep").val().length == 0){
                $.toast({
                    heading: 'Erro',
                    text: 'Endereço do cliente auxente ou incompleto!',
                    position: 'top-right',
                    loaderBg: '#ff6849',
                    icon: 'error',
                    hideAfter: 5000,
                    stack: 10
                });
            }

            $("#modalContaReceber").modal('hide');
        }
    });

    $(".conta_selecionada").on("change", function(){
        dados = $(this).data('dados');
        if(dados.conta_pai){
            if($(this).is(":checked")){
                console.log(dados.conta_pai)
                $(".conta_pai_"+dados.conta_pai).prop("checked", true)
            }else{
                $(".conta_pai_"+dados.conta_pai).prop("checked", false)
            }
        }
        
    })
</script>