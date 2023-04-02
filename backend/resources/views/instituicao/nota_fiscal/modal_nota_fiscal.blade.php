<div class="modal-header">
    <h4 class="modal-title" id="myLargeModalLabel">Emitir Nota Fiscal</h4>
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
</div>

<div class="modal-body">
    <div class="card">
        <div class="card-body ">
            <form id="formEmiteNotaModal">
                @csrf
                <div class="row">
                    <div class="col-md-2 form-group @if($errors->has('cod_servico_municipal')) has-danger @endif">
                        <label class="form-control-label">Cod Serviço</label>
                        <input type="text" class="form-control"name="cod_servico_municipal" value="{{ old('cod_servico_municipal', $config_fiscal->cod_servico_municipal) }}" readonly>
                        @if($errors->has('cod_servico_municipal'))
                            <small class="form-control-feedback">{{ $errors->first('cod_servico_municipal') }}</small>
                        @endif
                    </div>

                    <div class="col-md-2 form-group @if($errors->has('aliquota_iss')) has-danger @endif">
                        <label class="form-control-label">Aliquota Iis</label>
                        <input type="text" class="form-control" name="aliquota_iss" value="{{ old('aliquota_iss', $config_fiscal->aliquota_iss) }}" id="aliquota_iss" readonly alt="decimal">
                        @if($errors->has('aliquota_iss'))
                            <small class="form-control-feedback">{{ $errors->first('aliquota_iss') }}</small>
                        @endif
                    </div>
                    
                    <div class="col-md form-group @if($errors->has('descricao')) has-danger @endif">
                        <label class="form-control-label">Descrição</label>
                        <input type="text" class="form-control"
                            name="descricao" value="{{ old('descricao', $config_fiscal->descricao) }}">
                        @if($errors->has('descricao'))
                            <small class="form-control-feedback">{{ $errors->first('descricao') }}</small>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 form-group @if($errors->has('pessoa_id')) has-danger @endif">
                        <label class="form-control-label">Paciente</label>
                        <input type="hidden" value="{{$paciente->id}}" name="pessoa_id" id="pessoa_id">
                        <input type="text" class="form-control" value="#{{$paciente->id}} {{$paciente->nome}} @if(!empty($paciente->cpf)) ({{$paciente->cpf}}) @endif" readonly id="pessoa">
                        @if($errors->has('pessoa_id'))
                            <small class="form-control-feedback">{{ $errors->first('pessoa_id') }}</small>
                        @endif
                    </div>

                    <div class="col-md form-group @if($errors->has('contas_receber_id')) has-danger @endif">
                        <label class="form-control-label">Contas receber</label>
                        <hr>
                        <div class="col-sm-12" id="contasReceberSelec">
                            @foreach($contas_receber as $item)
                                <div class='row'>
                                    <div class='form-group col-sm-9'>
                                        <label>Descrição</label>
                                        <input type='hidden' name='contas_receber[]' value='{{$item->id}}'>
                                        <input type='text' class='form-control' readonly value='#{{$item->id}} {{$item->descricao}}'>
                                    </div>
                                    <div class='form-group col-sm-3'>
                                        <label>Valor</label>
                                        <input type='text' alt='decimal' class='form-control valor_parcela' readonly value='{{$item->valor_parcela}}'>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>                    
                </div>

                <div class="row">
                    <div class="col-md-2 form-group @if($errors->has('valor_total')) has-danger @endif">
                        <label class="form-control-label">Valor total</label>
                        <input type="text" name="valor_total" class="form-control" value="" id="valorTotalnota" alt="decimal">
                        @if($errors->has('valor_total'))
                            <small class="form-control-feedback">{{ $errors->first('valor_total') }}</small>
                        @endif
                    </div>

                    <div class="col-md-2 form-group">
                        <label class="form-control-label">Valor IIS</label>
                        <input type="text" name="valor_iis" class="form-control" value="" id="valorIis" alt="decimal" readonly>
                    </div>

                    <div class="col-md-2 form-group">
                        <label class="form-control-label">ISS retirdo na fonte</label>
                        <select class="form-control" name="iss_retido_fonte" id="iss_retido_fonte" readonly disabled>
                            <option value="0" @if($config_fiscal->iss_retido_fonte == 0) selected @endif>Não</option>
                            <option value="1" @if($config_fiscal->iss_retido_fonte == 1) selected @endif>Sim</option>
                        </select>
                    </div>

                    <div class="col-md-2 form-group @if($errors->has('deducoes')) has-danger @endif">
                        <label class="form-control-label">Deduções</label>
                        <input type="text" name="deducoes" class="form-control" value="" id="deducoes" alt="decimal">
                        @if($errors->has('deducoes'))
                            <small class="form-control-feedback">{{ $errors->first('deducoes') }}</small>
                        @endif
                    </div>
                </div>

                <hr>

                <div class="card card-body">
                    <h4>
                        Endereço tomador
                        <a class="float-right" href="{{route('instituicao.pessoas.edit', [$paciente])}}" target="_blank">
                            <button class="btn" type="button"><i class="ti-pencil-alt edit_paciente"></i></button>
                        </a>                           
                    </h4>

                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label class="form-control-label">Estado</label>
                            <input type="text" name="cliente_uf" class="form-control" value="{{$paciente->estado}}" id="cliente_uf" readonly>
                        </div>

                        <div class="col-md-4 form-group">
                            <label class="form-control-label">Cidade</label>
                            <input type="text" name="cliente_cidade" class="form-control" value="{{$paciente->cidade}}" id="cliente_cidade" readonly>
                        </div>

                        <div class="col-md-4 form-group">
                            <label class="form-control-label">Bairro</label>
                            <input type="text" name="cliente_bairro" class="form-control" value="{{$paciente->bairro}}" id="cliente_bairro" readonly>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label class="form-control-label">Logradouro</label>
                            <input type="text" name="cliente_logradouro" class="form-control" value="{{$paciente->rua}}" id="cliente_logradouro" readonly>
                        </div>

                        <div class="col-md-2 form-group">
                            <label class="form-control-label">Número</label>
                            <input type="text" name="cliente_numero" class="form-control" value="{{$paciente->numero}}" id="cliente_numero" readonly>
                        </div>

                        <div class="col-md-4 form-group">
                            <label class="form-control-label">Complemento</label>
                            <input type="text" name="cliente_complemento" class="form-control" value="{{$paciente->complemento}}" id="cliente_complemento" readonly>
                        </div>

                        <div class="col-md-2 form-group">
                            <label class="form-control-label">CEP</label>
                            <input type="text" name="cliente_cep" class="form-control" value="{{$paciente->cep}}" id="cliente_cep" readonly>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-12 form-group">
                        <label class="form-control-label">Observação</label>
                        <textarea name="observacao" rows="5" class="form-control" id="observacao" value="{{old('observacao')}}"></textarea>
                    </div>
                </div>

                <div class="form-group text-right pb-2">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>

    $( document ).ready(function() {
        atualizaValorTotal();
        $('input').setMask();
    });
    

    $("#valorTotalnota").on("change", function(){
        var valor_nota =  $(this).val().replace(",", ".")
        var aliquota_iss = $("#aliquota_iss").val().replace(",", ".")
        var valor_iis = (valor_nota * (aliquota_iss/100)).toFixed(2);

        $("#valorIis").val(valor_iis.replace(".",","),  $(this).val())
    })

    function atualizaValorTotal(){
        var valor = 0;
        $(".valor_parcela").each(function(index, element){
            valor = valor + parseFloat($(element).val());
        })

        $("#valorTotalnota").val(valor.toFixed(2)).change();
        $('.valor_parcela').setMask();
        $('#valorTotalnota').setMask();
    }

    $(".edit_paciente").on('click', function(){

    })
    
    $("form").submit(function(e){
        e.preventDefault();
        
        var formData = new FormData($("#formEmiteNotaModal")[0]);

        $.ajax({
            url: "{{route('instituicao.notasFiscais.criarNota')}}",
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,

            success: function (result) {
                if(result.icon == "error"){
                    $.toast({
                        heading: result.title,
                        text: result.text,
                        position: 'top-right',
                        loaderBg: '#ff6849',
                        icon: result.icon,
                        hideAfter: 9000,
                        stack: 10
                    });
                }else{
                    $.toast({
                        heading: result.title,
                        text: result.text,
                        position: 'top-right',
                        loaderBg: '#ff6849',
                        icon: result.icon,
                        hideAfter: 9000,
                        stack: 10
                    });

                    $("#modalEmitirNota").modal('hide');
                    $("#modalDescricao").modal('show');                    
                }
            },

            error: function (response) {
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
    });

</script>