
<div class="row">
    <div class="col-md-12">
        <button type="button" class="btn btn-info waves-effect waves-light m-r-10 historico-solicitacao-button" style="float: right">
            <i class="fas fa-history"></i> Historico
        </button>
        <button type="button" class="btn btn-success waves-effect waves-light m-r-10 salvar-solicitacao-button" style="float: right">
            <i class="far fa-save"></i> Salvar
        </button>
        <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10 novo-solicitacao-button" style="float: right">
            <i class="fas fa-plus"></i> Novo
        </button>

        <h4 class="float-left lead">Solicitacão de estoque</h4>
    </div>
</div>
<hr>
<div class="row">
    <div class="form-solicitacao col-md-12">
        <form id="formSolicitacao" method="post" enctype="multipart/form-data">
            @csrf
            @include('instituicao.prontuarios.solicitacao-estoque.form')
        </form>
    </div>

    
</div>
<div class="row">
    <div class="col-md-12">
        <button type="button" class="btn btn-success waves-effect waves-light m-r-10 salvar-solicitacao-button" style="float: right">
            <i class="far fa-save"></i> Salvar
        </button>
    </div>
</div>

<div class="solicitacao-historico">
    {{-- @include('instituicao.prontuarios.solicitacao-estoque.historico') --}}
</div>

<script>
    $(document).ready(function(){
        $(".select2").select2();
        $('.select2basic').select2();

        atualizaHistoricoSolicitacao();
        
        $('#produto-select').select2({
            placeholder: "Busque o produto",
            ajax: {
                url: "{{route('instituicao.ajax.buscar-produtos')}}",
                type: 'post',
                dataType: 'json',
                quietMillis: 20,
                data: function(params) {
                    return {
                        search: params.term,
                        '_token': '{{csrf_token()}}',
                        paginate: true
                    };
                },
                processResults: function(data) {
                    produtos_disponiveis = data.results;
                    return {
                        results: $.map(data.results, function(obj) {
                            return {
                                id: obj.id,
                                text: `#${obj.id} - ${obj.descricao} [un: ${obj.unidade.descricao}]`
                            }
                        }),
                        pagination: {
                            more: data.pagination.more
                        }
                    }
                }
            },
            language: {
                searching: function () {
                    return 'Buscando ...';
                },

                noResults: function () {
                    return 'Nenhum resultado encontrado';
                },

                inputTooShort: function (input) {
                    return "Digite " + (input.minimum - input.input.length)+ " caracteres para pesquisar";
                },
            },
            escapeMarkup: function(m) {
                return m;
            }
        });
        
        $('#prestador-select').select2({
            placeholder: "Busque o prestador",
            ajax: {
                url: "{{ route('instituicao.ajax.buscaprestador') }}",
                type: 'post',
                dataType: 'json',
                quietMillis: 20,
                data: function(params) {
                    return {
                        search: params.term,
                        '_token': '{{csrf_token()}}',
                    };
                },
                processResults: function(data) {
                    return {
                        results: $.map(data.results, function(obj) {
                            return {
                                id: obj.id,
                                text: obj.prestador.nome
                            };
                        }),
                        pagination: {
                            more: data.pagination.more
                        }
                    }
                }
            },
            language: {
                searching: function () {
                    return 'Buscando ...';
                },

                noResults: function () {
                    return 'Nenhum resultado encontrado';
                },

                inputTooShort: function (input) {
                    return "Digite " + (input.minimum - input.input.length)+ " caracteres para pesquisar";
                },
            },
            escapeMarkup: function(m) {
                return m;
            }
        });

        $('.checkbox').iCheck({
            checkboxClass: 'icheckbox_square',
            radioClass: 'iradio_square',
            increaseArea: '90%'
        })

        const produtos_a_inserir = {!! json_encode($produtos) !!};
        if(produtos_a_inserir.length > 0) {
            produtos_a_inserir.forEach(item => {
                console.log(item);
                addProduct(item, true);
            });
        }

        $(".novo-solicitacao-button").on('click', function(e){
            e.preventDefault()
            Swal.fire({
                title: "Novo!",
                text: 'Deseja criar novo o solicitacao ?',
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                cancelButtonText: "Não, cancelar!",
                confirmButtonText: "Sim, confirmar!",
            }).then(function(result) {
                if(result.value){
                    $("#produtos-container").html('');
                    $("#estoque-origem-select").val();
                    $('.checkbox').prop('checked', false);
                    $('.checkbox').cange();
                    $("#observacoes").val("");
                    $("#estoque-origem-select").val("");
                    $("#estoque-origem-select").change();
                    $("#produto-select").val('');
                    $("#produto-select").change();
                    removeIcon("tab-soliciracao-estoque")
                    
                }
            })
        })
    })

    $('#formSolicitacao').bind('input propertychange', 'form', function(){
        addIcon("tab-soliciracao-estoque")
    })

    $(".historico-solicitacao-button").on('click', function(e){
        $(".solicitacao-historico").find('#modalHistoricoSolicitacao').modal('show')
    })

    $(".salvar-solicitacao-button").on('click', function(e){
        e.preventDefault()
        if($("#solicitacao_id").val()){
            Swal.fire({
                title: "Editar!",
                text: 'Deseja editar o solicitacao ?',
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                cancelButtonText: "Não, cancelar!",
                confirmButtonText: "Sim, confirmar!",
            }).then(function(result) {
                if(result.value){
                    salvarSolicitacao();
                }
            })
        }else{
            salvarSolicitacao()
        }
    });

    function salvarSolicitacao(){
        var formData = new FormData($("#formSolicitacao")[0]);

        var paciente_id = $("#paciente_id").val();
        var agendamento_id = $("#agendamento_id").val();

        $.ajax({
            url: "{{route('agendamento.internacoes.estoque.salvar', ['agendamento' => 'agendamento_id', 'paciente' => 'paciente_id'])}}".replace('agendamento_id', agendamento_id).replace('paciente_id', paciente_id),
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: () => {
                $('.loading').css('display', 'block');
                $('.loading').find('.class-loading').addClass('loader')
            },
            success: (result) => {
                $.toast({
                    heading: 'Sucesso',
                    text: 'Solicitação de estoque salva com sucesso!',
                    position: 'top-right',
                    loaderBg: '#ff6849',
                    icon: 'success',
                    hideAfter: 9000,
                    stack: 10
                });
                $("#formSolicitacao").find("#solicitacao_id").val(result.id)
                atualizaHistoricoSolicitacao();
                carregaResumoPag();
            },
            complete: () => {
                removeIcon("tab-soliciracao-estoque")
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
    }

    function atualizaHistoricoSolicitacao(){

        var paciente_id = $("#paciente_id").val();
        var agendamento_id = $("#agendamento_id").val();

        $.ajax({
            url:"{{route('agendamento.internacoes.estoque.getHistorico', ['agendamento' => 'agendamento_id', 'paciente' => 'paciente_id'])}}".replace('agendamento_id', agendamento_id).replace('paciente_id', paciente_id),
            type: 'get',
            beforeSend: () => {
                $(".solicitacao-historico").html('');
            },
            success: function(result) {
                $(".solicitacao-historico").html(result);
            },
            complete: () => {
            }
        });
    }

    const produto_container = $('#produtos-container');
    const protuto_template = $('#produto-template');
    const produto_select = $('#produto-select');
    let agendamentos_search = Array()
    let produto_next_id = 0
    let produtos_selecionados = Array()
    var produtos_disponiveis = Array()

    function addProduct(produto = null, has_extra = false)
    {
        if(produto == null) {
            produto = {
                id: produto_select.val(),
                descricao: produto_select.find(`[value="${produto_select.val()}"]`).text()
            }
        }
        // Pega os demais dados do produto
        const dados_extra = !has_extra ? produtos_disponiveis.find(el => el.id == produto.id) : null;
        // Verifica se já existe
        if(produtos_selecionados.findIndex(el => el.id == produto.id) !== -1 || (!dados_extra && !has_extra))
            return;
        // Cria e insere na tabela
        const elemento = $(protuto_template.html());
        elemento.attr('id', `entrada-produto-${produto.id}`);
        elemento.find('.produto-id').val(produto.id);
        elemento.find('.produto-id').attr('name', elemento.find('.produto-id').attr('name').replace('#', produto_next_id));
        elemento.find('.quantidade-input').attr('name', elemento.find('.quantidade-input').attr('name').replace('#', produto_next_id++));
        elemento.find('.quantidade-input').val(produto.quantidade ?? 1);
        elemento.find('.name').text(produto.descricao);
        elemento.find('.classe').text(!has_extra ? (dados_extra.classe.descricao ?? '') : (produto.classe.descricao ?? ''));
        elemento.find('.unidade').text(!has_extra ? (dados_extra.unidade.descricao ?? '') : (produto.unidade.descricao ?? ''));
        elemento.find('.button-remove').attr('onclick', `removeProduct(${produto.id})`);
        produto_container.append(elemento);
        produtos_selecionados.push(produto);
    }

    function removeProduct(id)
    {
        const index = produtos_selecionados.findIndex(el => el.id == id)
        produtos_selecionados.splice(index, 1);
        $(`#entrada-produto-${id}`).remove()
    }
</script>

<script id="produto-template" type="text/template">
    <tr class="produto-input">
        <input class="produto-id" type="hidden" name="produtos[#][produtos_id]">
        <td class="name"></td>
        <td class="classe"></td>
        <td class="unidade"></td>
        <td class="text-right"><input class="quantidade-input form-control" style="max-width: 100px" name="produtos[#][quantidade]" type="number" min="0" value="0"></td>
        <td><button onclick="" type="button" class="button-remove btn btn-danger"><i class="fas fa-trash-alt"></i></button></td>
    </tr>
</script>