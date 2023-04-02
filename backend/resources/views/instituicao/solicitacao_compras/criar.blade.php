@extends('instituicao.layout')

@section('conteudo')
    @component('components/page-title', [
        'titulo' => 'Cadastrar Compras',
        'breadcrumb' => [
            'Solicitação Compras' => route('instituicao.solicitacaoCompras.index'),
            'Novo',
        ],
        ])
    @endcomponent

    <div class="card">
        <div class="card-body ">
            <form action="{{ route('instituicao.solicitacaoCompras.store') }}" method="post">
                @csrf

                <div class="row">
                    <div class="col-sm-2">
                        <div class="form-group @if($errors->has('data_solicitacao')) has-danger @endif">
                            <label class="form-control-label p-0 m-0">Data Solicitação <span class="text-danger">*</span></label>
                            <input type="text" name="data_solicitacao" id="data_solicitacao" alt="date" value="{{ old('data_solicitacao') }}"
                                class="form-control campo @if($errors->has('data_solicitacao')) form-control-danger @endif">
                            @if($errors->has('data_solicitacao'))
                                <small class="form-text text-danger">{{ $errors->first('data_solicitacao') }}</small>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group @if($errors->has('data_maxima')) has-danger @endif">
                            <label class="form-control-label p-0 m-0">Data Máxima</label>
                            <input type="text" name="data_maxima" alt="date"  value="{{ old('data_maxima') }}"
                                class="form-control campo @if($errors->has('data_maxima')) form-control-danger @endif">
                            @if($errors->has('data_maxima'))
                                <small class="form-text text-danger">{{ $errors->first('data_maxima') }}</small>
                            @endif
                        </div>
                    </div>
                     <div class="col-sm-2">
                        <div class="form-group @if($errors->has('data_impressao')) has-danger @endif">
                            <label class="form-control-label p-0 m-0">Data Impressão</label>
                            <input type="text" name="data_impressao" alt="date"  value="{{ old('data_impressao') }}"
                                class="form-control campo @if($errors->has('data_impressao')) form-control-danger @endif">
                            @if($errors->has('data_impressao'))
                                <small class="form-text text-danger">{{ $errors->first('data_impressao') }}</small>
                            @endif
                        </div>
                    </div>

                     <div class=" col-md-6 form-group @if($errors->has('setores_exames_id')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Setor<span class="text-danger">*</span></label>
                        <select class="form-control select2 @if($errors->has('setores_exames_id')) form-control-danger @endif" name="setores_exames_id" id="setores_exames_id" required style="width: 100%">
                            <option value="">Selecione</option>
                            @foreach($setores as $setor)
                                <option value="{{ $setor->id }}">{{ $setor->descricao }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('setores_exames_id'))
                            <small class="form-control-feedback">{{ $errors->first('setores_exames_id') }}</small>
                        @endif
                    </div>
                    {{-- 
                    <div class="col-sm-2">
                        <div class="form-group @if($errors->has('cod_usuario')) has-danger @endif">
                            <label class="form-control-label p-0 m-0">Código do Usuário <span class="text-danger">*</span></label>
                            <input type="text" name="cod_usuario" value="{{ old('cod_usuario') }}"
                                class="form-control campo @if($errors->has('cod_usuario')) form-control-danger @endif">
                            @if($errors->has('cod_usuario'))
                                <small class="form-text text-danger">{{ $errors->first('cod_usuario') }}</small>
                            @endif
                        </div>
                    </div>
                    --}}
                </div>
                <div class="row">
                    <div class="col-sm-2">
                        <div class="form-group @if($errors->has('nome_solicitante')) has-danger @endif">
                            <label class="form-control-label p-0 m-0">Nome Solicitante <span class="text-danger">*</span></label>
                            <input type="text" name="nome_solicitante" value="{{ old('nome_solicitante') }}"
                                class="form-control campo @if($errors->has('nome_solicitante')) form-control-danger @endif">
                            @if($errors->has('nome_solicitante'))
                                <small class="form-text text-danger">{{ $errors->first('nome_solicitante') }}</small>
                            @endif
                        </div>
                    </div>

                    <div class=" col-md-2 form-group @if($errors->has('motivo_pedido_id')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Motivo do Pedido<span class="text-danger">*</span></label>
                        <select class="form-control select2 @if($errors->has('motivo_pedido_id')) form-control-danger @endif" name="motivo_pedido_id" id="motivo_pedido_id" required style="width: 100%">
                            <option value="">Selecione</option>
                            @foreach($motivoPedidos as $motivoPedido)
                                <option value="{{ $motivoPedido->id }}">{{ $motivoPedido->descricao }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('motivo_pedido_id'))
                            <small class="form-control-feedback">{{ $errors->first('motivo_pedido_id') }}</small>
                        @endif
                    </div>

                    <div class=" col-md-3 form-group @if($errors->has('comprador_id')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Comprador<span class="text-danger">*</span></label>
                        <select class="form-control select2 @if($errors->has('comprador_id')) form-control-danger @endif" name="comprador_id" id="comprador_id" required style="width: 100%">
                            <option value="">Selecione</option>
                            @foreach($compradores as $comprador)
                                <option value="{{ $comprador->id }}">{{$comprador->usuario->nome }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('comprador_id'))
                            <small class="form-control-feedback">{{ $errors->first('comprador_id') }}</small>
                        @endif
                    </div>

                     <div class=" col-md-5 form-group @if($errors->has('estoque_id')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Estoque<span class="text-danger">*</span></label>
                        <select class="form-control select2 @if($errors->has('estoque_id')) form-control-danger @endif" name="estoque_id" id="estoque_id" required style="width: 100%">
                            <option value="">Selecione</option>
                            @foreach($estoques as $estoque)
                                <option value="{{ $estoque->id }}">{{$estoque->descricao }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('estoque_id'))
                            <small class="form-control-feedback">{{ $errors->first('estoque_id') }}</small>
                        @endif
                    </div>
                    {{--  
                    <div class="col-sm-2">
                        <div class="form-group @if($errors->has('sol_agrup')) has-danger @endif">
                            <label class="form-control-label p-0 m-0">Sol. Agrup. <span class="text-danger">*</span></label>
                            <input type="text" name="sol_agrup" value="{{ old('sol_agrup') }}"
                                class="form-control campo @if($errors->has('sol_agrup')) form-control-danger @endif">
                            @if($errors->has('sol_agrup'))
                                <small class="form-text text-danger">{{ $errors->first('sol_agrup') }}</small>
                            @endif
                        </div>
                    </div>
                    --}}
                </div>
                <div class="row">
                    <div class="col-sm-2">
                        <div class="form-group num_parcelas">
                            <label class="form-control-label">Tipo</label>
                            <div class="demo-radio-button">
                                <input name="servico_produto" type="radio" id="radio_1" value="1" checked />
                                <label for="radio_1">Serviço</label>
                                <input name="servico_produto" type="radio" id="radio_2" value="2"/>
                                <label for="radio_2">Produto</label>
                            </div>
                        </div>
                    </div> 
                    <div class="col-md-1">
                        <input type="checkbox" id="urgente" name="urgente" value="1" class="filled-in chk-col-teal" @if (old('urgente'))
                                    checked
                                @endif/>
                        <label for="urgente">Urgente</label>                    
                    </div> 
                    <div class="col-sm-1 form-group">
                        <label class="form-control-label">Solic. OPME?</label>
                        <select class="form-control" name="solicitacao_opme">
                            <option value="1" @if (old('solicitacao_opme') == '1')
                                selected
                            @endif>Sim</option>
                            <option value="0" @if (old('solicitacao_opme') == '0')
                                selected
                            @endif>Não</option>
                        </select>
                    </div>
                    {{-- 
                    <div class="col-sm-2">
                        <div class="form-group @if($errors->has('atendimento')) has-danger @endif">
                            <label class="form-control-label p-0 m-0">Atendimento <span class="text-danger">*</span></label>
                            <input type="text" name="atendimento" value="{{ old('atendimento') }}"
                                class="form-control campo @if($errors->has('atendimento')) form-control-danger @endif">
                            @if($errors->has('atendimento'))
                                <small class="form-text text-danger">{{ $errors->first('atendimento') }}</small>
                            @endif
                        </div>
                    </div>  
                    <div class="col-sm-2">
                        <div class="form-group @if($errors->has('pre_int')) has-danger @endif">
                            <label class="form-control-label p-0 m-0">Pre. int <span class="text-danger">*</span></label>
                            <input type="text" name="pre_int" value="{{ old('pre_int') }}"
                                class="form-control campo @if($errors->has('pre_int')) form-control-danger @endif">
                            @if($errors->has('pre_int'))
                                <small class="form-text text-danger">{{ $errors->first('pre_int') }}</small>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group @if($errors->has('av_cirurgia')) has-danger @endif">
                            <label class="form-control-label p-0 m-0">Av Cirurgia<span class="text-danger">*</span></label>
                            <input type="text" name="av_cirurgia" value="{{ old('av_cirurgia') }}"
                                class="form-control campo @if($errors->has('av_cirurgia')) form-control-danger @endif">
                            @if($errors->has('av_cirurgia'))
                                <small class="form-text text-danger">{{ $errors->first('av_cirurgia') }}</small>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group @if($errors->has('data_maxima_apoio_cotacao')) has-danger @endif">
                            <label class="form-control-label p-0 m-0">Dt Máxima Apoio Cotações <span class="text-danger">*</span></label>
                            <input type="text" name="data_maxima_apoio_cotacao" id="data_maxima_apoio_cotacao" alt="date" value="{{ old('data_maxima_apoio_cotacao') }}"
                                class="form-control campo @if($errors->has('data_maxima_apoio_cotacao')) form-control-danger @endif">
                            @if($errors->has('data_maxima_apoio_cotacao'))
                                <small class="form-text text-danger">{{ $errors->first('data_maxima_apoio_cotacao') }}</small>
                            @endif
                        </div>
                    </div>
                    --}}
                </div>
                <div class="row col-12">
                    <div class="card col-12 px-0 py-3 shadow-none">
                        <div class="form-group col-md-12 p-0">
                            <div class="col-md-8">
                                <label class="form-control-label p-0 m-0 @if($errors->has('produtos')) has-danger @endif">Produto</label>
                                <div class="input-group">
                                    <div class="col p-0">
                                        <select id="produto-select" style="width: 100%" class="form-control @if($errors->has('produtos')) form-control-danger @endif"></select>
                                    </div>
                                    <div class="px-1">
                                        <button onclick="addProduct()" type="button" class="btn btn-primary"><i class="fas fa-plus"></i></button>
                                    </div>
                                </div>
                                @if ($errors->has('produtos'))
                                    <small class="form-control-feedback text-danger">{{ $errors->first('produtos') }}</small>
                                @endif
                            </div>
                            <div class="mt-4 col-12 table-container">
                                <table class="table table-bordered">
                                    <colgroup>
                                        <col style="width: auto">
                                        <col style="width: 400px">
                                        <col style="width: 250px">
                                        <col style="width: 50px">
                                        <col style="width: 50px">
                                    </colgroup>
                                    <thead>
                                        <tr>
                                            <th>Produto</th>
                                            <th>Fornecedor</th>
                                            <th>Qtd. Solicitada</th>
                                            <th>Oferta Máxima</th> 
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody id="produtos-container">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group text-right">
                    <hr>
                    <a href="{{ route('instituicao.solicitacaoCompras.index') }}">
                        <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                    </a>
                    <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script id="produto-template" type="text/template">
        <tr class="produto-input">
            <input class="produto-id" type="hidden" name="produtos[#][produto_id]">
            <td class="name"></td>
            <td  class="text-right">
                <select style="width: 100%"  class=" fornecedor-input form-control" name="produtos[#][pessoa_id]"></select>
            </td>
            <td class="text-right"><input class="quantidade-input form-control" style="max-width: 100px" name="produtos[#][qtd_solicitada]" type="number" min="0" value="0"></td>
            <td class="text-right"><input class="oferta-input form-control" style="max-width: 100px" name="produtos[#][oferta_max]" type="number" min="0" value="0"></td>
            <td><button onclick="" type="button" class="button-remove btn btn-danger"><i class="fas fa-trash-alt"></i></button></td> -->
        </tr>
    </script>

    <script>
        const produto_container = $('#produtos-container')
        const protuto_template = $('#produto-template')
        const produto_select = $('#produto-select')
        let produto_next_id = 0
        let produtos_selecionados = Array()
        var produtos_disponiveis = Array()
       
        function addProduct(produto = null)
        {
            if(produto == null) {
                produto = {
                    id: produto_select.val(),
                    descricao: produto_select.find(`[value="${produto_select.val()}"]`).text()
                }
            }
            // Pega os demais dados do produto
            const dados_extra = produtos_disponiveis.find(el => el.id == produto.id);
            // Verifica se já existe
            if(produtos_selecionados.find(el => el.id == produto.id) || !dados_extra)
                return;
            // Cria e insere na tabela
            const elemento = $(protuto_template.html());
            elemento.attr('id', `entrada-produto-${produto.id}`);
            elemento.find('.produto-id').val(produto.id);
            elemento.find('.produto-id').attr('name', elemento.find('.produto-id').attr('name').replace('#', produto_next_id));
            elemento.find('.name').text(produto.descricao);
            $.getJSON('{{route("instituicao.ajax.buscar-fornecedores")}}', function (dados){
                var option = '<option value="">Selecione...</option>';
                if (dados.length > 0){
                    $.each(dados, function(i, obj){
                        option += '<option value="'+obj.id+'">'+obj.nome+'</option>';
                    })
                    elemento.find('.fornecedor-input').html(option);
                 }else{
                    elemento.find('.fornecedor').html(option);
                }
            });
            elemento.find('.fornecedor-input').attr('name', elemento.find('.fornecedor-input').attr('name').replace('#', produto_next_id));
            elemento.find('.quantidade-input').attr('name', elemento.find('.quantidade-input').attr('name').replace('#', produto_next_id));
            elemento.find('.oferta-input').attr('name', elemento.find('.oferta-input').attr('name').replace('#', produto_next_id));
            elemento.find('.button-remove').attr('onclick', `removeProduct(${produto.id})`);
            produto_next_id++;
            produto_container.append(elemento);
            produtos_selecionados.push(produto);
        }

        function removeProduct(id)
        {
            const index = produtos_selecionados.findIndex(el => el.id == id)
            produtos_selecionados.splice(index, 1);
            $(`#entrada-produto-${id}`).remove()
        }
    
        $(document).ready(function() {
            $('.select2basic').select2();
            $('#produto-select').select2({
                ajax: {
                    url: '{{route("instituicao.ajax.buscar-produtos")}}',
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
                        produtos_disponiveis = data;
                        return {
                            results: $.map(data, function(obj) {
                                return {
                                    id: obj.id,
                                    text: `#${obj.id} - ${obj.descricao} [un: ${obj.unidade}]`
                                }
                            })
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
        });
    </script>
@endpush