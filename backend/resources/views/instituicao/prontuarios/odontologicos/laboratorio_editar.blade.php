<div>
    <table data-toggle="table" data-height="300" id="table_lab" >
        <thead>
            <tr>
                <th data-field="state" data-checkbox="true" class="checkbox_modal_criado lab_state_table_head"></th>
                <th class="lab_dentes_table_head" data-field="dentes">Dentes</th>
                <th class="lab_procedimento_table_head" data-field="procedimento">Procedimento</th>
                <th class="lab_regiao_table_head" data-field="regiao">Região</th>
                <th class="lab_desconto_table_head" data-field="desconto">Valor Total</th>
                <th class="lab_valor_table_head" data-field="valor">Valor Laboratório</th>
            </tr>
        </thead>
        <tbody class="itens_modal_table_body">
            @foreach ($orcamento->itens as $item)
                <tr class="tr_modal_criado_lab item_{{$item->id}}" data-valor="{{$item->valor}}" data-id="{{$item->id}}" @if ($item->procedimento_instituicao_convenio_id)
                    data-convenio="{{$item->procedimentos->convenios->id}}"
                @endif data-desconto="{{$item->desconto}}"> 
                    <td></td>
                    <td data-id="{{$item->id}}">{{$item->dente_id}}</td>
                    <td>{{$item->procedimentosItens->descricao}}</td>
                    <td>
                        @if ($item->regiao)
                            {{($item->regiao) ? $item->regiao->descricao : ''}}
                        @elseif(count($item->regiaoProcedimento) > 0)
                            @foreach ($item->regiaoProcedimento as $keyR => $regiao)
                                @if ($keyR == 0)
                                    {{$regiao->descricao}}
                                @else
                                     / {{$regiao->descricao}}
                                @endif
                            @endforeach
                        @endif
                    </td>
                    <td data-valor="{{$item->valor}}" class="valor_item">
                        @if ($item->procedimento_instituicao_convenio_id)
                            @if ($item->procedimentos->valor > 0)
                                R$ {{number_format($item->procedimentos->valor, 2, ',','.')}}
                            @endif
                        @elseif($item->valor > 0)
                            R$ {{number_format($item->valor, 2, ',','.')}}
                        @else
                            R$ 0,00
                        @endif
                    </td>
                    <td class="input_desconto_orcamento"><input type="text" alt="decimal" class="form-control valor_lab"  name="valor_lab" value="{{($item->laboratorio > 0) ? number_format($item->laboratorio,2 ,',','.') : 0}}" onchange="verificaValorLaboratorio(this)" @if (!Gate::allows('habilidade_instituicao_sessao', 'editar_laboratorio_odontologico'))
                        disabled
                    @endif></td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td style="position: relative; left: 0px; opacity: 1;"></td>
                <td ></td>
                <td ></td>
                <td ></td>
                <td ></td>
                <td ></td>
                <td ></td>
            </tr>
        </tfoot>
    </table>
    @can('habilidade_instituicao_sessao', 'editar_laboratorio_odontologico')
        <div class="form-groupn text-right pb-2 pt-2">
            {{-- @can('habilidade_instituicao_sessao', 'alterar_orcamento_odontologico_negociador_responsavel') --}}
                <button type="button" class="btn btn-info waves-effect nav-link editar_labotario">Salvar alterações</button>
            {{-- @endcan --}}
        </div>
    @endcan
</div>

<script>
    var editRows = [];
    function verificaValorLaboratorio(element){
        var valor = retornaFormatoValorLab($(element).val());
        var valor_total = $(element).parents('.tr_modal_criado_lab').find('.valor_item').text().replace('R$', '')  
        valor_total = retornaFormatoValorLab(valor_total);

        if(parseFloat(valor) > parseFloat(valor_total)){
            Swal.fire("Alerta!", "O valor para o laboratório é maior que o valor do procedimento", "warning");
        }

        var id = $(element).parents('.tr_modal_criado_lab').attr('data-id');

        $.each(editRows, function(index, value){
            if(value.id == id){
                editRows.splice(index, -1);
            }
        })

        editRows.push({id: id, laboratorio: valor})
    }

    function retornaFormatoValorLab(valor){
        var novo = valor;
        novo = novo.replace('.','')
        novo = novo.replace(',','.')
        return novo;
    }

    $(".editar_labotario").on('click', function(e){
        e.stopImmediatePropagation()
        e.preventDefault();
        e.stopPropagation();

        var ids = "";
        $.each(editRows, function(index, value) {
            var valor = value.valor;
            laboratorio = value.laboratorio
            if(ids == ""){
                ids = value.id+','+laboratorio;
            }else{
                ids = ids+";"+value.id+','+laboratorio;
            }

        });

        var paciente_id = $("#paciente_id").val();
        var orcamento_id = $("#orcamento_id").val();

        $.ajax({
            url: "{{route('instituicao.odontologico.alterarValorLaboratorio', ['paciente' => 'paciente_id', 'orcamento' => 'orcamento_id'])}}".replace('orcamento_id', orcamento_id).replace('paciente_id', paciente_id),
            type: "POST",
            data: {ids: ids, "_token": "{{csrf_token()}}"},
            beforeSend: () => {
                $('.loading').css('display', 'block');
                $('.loading').find('.class-loading').addClass('loader')
            },
            success: (result) => {
                $.toast({
                    heading: 'Sucesso',
                    text: 'Orçamento laboratório alterado com sucesso!',
                    position: 'top-right',
                    loaderBg: '#ff6849',
                    icon: 'success',
                    hideAfter: 9000,
                    stack: 10
                });
            },
            complete: () => {
                $('.loading').css('display', 'none');
                $('.loading').find('.class-loading').removeClass('loader') 
            },
            error: function(response) {
                if(response.responseJSON.errors){
                    Object.keys(response.responseJSON.errors).forEach(function(key) {
                        $.toast({
                            heading: 'Erro',
                            text: response.responseJSON.errors[key][0],
                            position: 'top-right',
                            loaderBg: '#ff6849',
                            icon: 'error',
                            hideAfter: 9000,
                            stack: 10
                        });

                    });
                }
            }
        })
    })
</script>