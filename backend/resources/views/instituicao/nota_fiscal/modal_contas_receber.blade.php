<div id="modalContaReceber" class="modal fade bs-example-modal-lg" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <span>Pesquisar contas a receber</span>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
           
            <div class="modal-body">
                <div class="card-body">
                    <form action="javascript:void(0)" id="formPesquisa">
                        @csrf
                        <div class="row" style="margin-bottom: 20px">
                            <div class="col-md-10"></div>
                        </div>
                    
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <select name="tipo" id="tipo" class="form-control selectfild2" onchange="tipoPesquisa()" style="width: 100%">
                                    <option value="">Todos tipo</option>
                                        @foreach ($tipos as $tipo)
                                            <option value="{{ $tipo }}" @if($tipo == 'paciente') selected @endif>
                                                {{ App\ContaReceber::tipos_texto_all($tipo) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="form-group">
                                    <select name="tipo_id" class="form-control selectfild2" id="tipo_id" style="width: 100%" disabled>
                                        <option value="0">Selecione um tipo</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-5">
                                <div class="form-group">
                                    <input type="text" name="search" class="form-control" placeholder="Pesquise por descrição...">
                                </div>
                            </div>
                
                            <div class="col-md-3">
                                <div class="form-group">
                                    <input type="date" name="data_inicio" class="form-control" placeholder="Data vencimento inicio">
                                </div>
                            </div>
                
                            <div class="col-md-3">
                                <div class="form-group">                     
                                    <input type="date" name="data_fim" class="form-control" placeholder="Data vencimento final">
                                </div>
                            </div>
                
                            <div class="col-md-1">
                                <div class="form-group">
                                    <span alt="default" class="add fas fa-plus-circle" style="cursor: pointer;">
                                        <a class="mytooltip" href="javascript:void(0)">
                                            <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="" data-original-title="Abrir mais filtros"></i>
                                        </a>
                                    </span>
                                    <span alt="default" class="remove fas fa-minus-circle" style="cursor: pointer; display: none">
                                        <a class="mytooltip" href="javascript:void(0)">
                                            <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="" data-original-title="Esconder filtros"></i>
                                        </a>
                                    </span>
                                </div>
                            </div>
                
                            <div class="col-md-12 filtros" style="display: none">
                                <div class="row">                    
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <select name="forma_pagamento_id" class="form-control selectfild2" style="width: 100%">
                                            <option value="">Todas Formas pagamento</option>
                                                @foreach ($formaPagamentos as $formaPagamento)
                                                    <option value="{{ $formaPagamento }}">
                                                        {{ App\ContaReceber::forma_pagamento_texto($formaPagamento) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <select name="status_id" id="status_id" class="form-control selectfild2" style="width: 100%">
                                                <option value="3">Todas pagas e não pagas</option>
                                                <option value="1">Pagos</option>
                                                <option value="0">Não pagos</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <select name="conta_id" class="form-control selectfild2" style="width: 100%">
                                                <option value="0">Todas contas</option>
                                                @foreach ($contas as $item)
                                                    <option value="{{$item->id}}">{{$item->descricao}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <select name="plano_conta_id" class="form-control selectfild2" style="width: 100%">
                                                <option value="0">Todos Planos de Conta</option>
                                                @foreach ($planosConta as $item)
                                                    <option value="{{$item->id}}">{{$item->codigo}} - {{$item->descricao}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                               
                        <div class="form-group" style="margin-bottom: 10px !important; float: right;">
                            <button type="submit" id="pesquisar" class="btn waves-effect waves-light btn-block btn-success" >Pesquisar</button>
                        </div>
                    </form>

                    <hr>
                    
                    <div id='tabela'></div>
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>



<script>
    $( document ).ready(function() {
        tipoPesquisa();
    });

    function tipoPesquisa(){
        tipo = $('#tipo').val()            
        if(tipo == "paciente"){
            $("#tipo_id").prop("disabled", false);
            $('#tipo_id').html('');

            $("#tipo_id").select2({
                placeholder: "Pesquise por nome do paciente",
                allowClear: true,
                minimumInputLength: 3,

                language: {
                    searching: function () {
                        return 'Buscando paciente (aguarde antes de selecionar)…';
                    },
                    
                    inputTooShort: function (input) {
                        return "Digite " + (input.minimum - input.input.length)+ " caracteres para pesquisar"; 
                    },
                },
                
                ajax: {
                    url:"{{route('instituicao.contasPagar.getPacientes')}}",
                    dataType: 'json',
                    type: 'get',
                    delay: 100,

                    data: function (params) {
                        return {
                            q: params.term, // search term
                            page: params.page || 1
                        };
                    },

                    processResults: function (data, params) {
                        params.page = params.page || 1;
                        return {
                            results: _.map(data.results, item => ({
                                id: Number.parseInt(item.id),
                                text: `${item.nome} ${(item.cpf) ? '- ('+item.cpf+')': ''}`,
                            })),
                            pagination: {
                                more: data.pagination.more
                            }
                        };
                    },
                    cache: true
                },
            });
        }else if(tipo == "convenio"){
            $("#tipo_id").prop("disabled", false);
            $('#tipo_id').html('');

            $("#tipo_id").select2({
                placeholder: "Pesquise por nome do convenio",
                allowClear: true,

                language: {
                    searching: function () {
                        return 'Buscando convenio (aguarde antes de selecionar)…';
                    },
                },
                
                ajax: {
                    url:"{{route('instituicao.contasReceber.getConvenios')}}",
                    dataType: 'json',
                    type: 'get',
                    delay: 100,

                    data: function (params) {
                        return {
                            q: params.term, // search term
                            page: params.page || 1
                        };
                    },

                    processResults: function (data, params) {
                        params.page = params.page || 1;
                        return {
                            results: _.map(data.results, item => ({
                                id: Number.parseInt(item.id),
                                text: item.nome,
                            })),
                            pagination: {
                                more: data.pagination.more
                            }
                        };
                    },
                    cache: true
                },
            });
        }else{
            $('#tipo_id').prop('disabled', true)
            $('#tipo_id').html('')
        }
    }

    $(".add").on('click', function() {
        $(".filtros").css('display', 'block');
        $(".add").css('display', 'none');
        $(".remove").css('display', 'block');
    })

    $(".remove").on('click', function() {
        $(".filtros").css('display', 'none');
        $(".remove").css('display', 'none');
        $(".add").css('display', 'block');
    })

    $('#pesquisar').on("click", function(){
       var formData = new FormData($("#formPesquisa")[0]);

       $.ajax({
            url: "{{route('instituicao.notasFiscais.pesquisarContaReceber')}}",
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,

            success: function (result) {
                $("#tabela").html(result);
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
    })

    
</script>