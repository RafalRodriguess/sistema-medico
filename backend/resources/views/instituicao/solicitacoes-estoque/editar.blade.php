@extends('instituicao.layout')
@section('conteudo')
    @component('components/page-title', [
        'titulo' => "Atualizar solicitação de estoque # {$solicitacao->id}",
        'breadcrumb' => [
            'Solicitações de estoque' => route('instituicao.solicitacoes-estoque.index'),
            'Editar',
        ],
    ])
    @endcomponent
    <div class="card">
        <div class="card-body ">
            <form action="{{ route('instituicao.solicitacoes-estoque.update', $solicitacao) }}" method="post">
                @method('put')
                @csrf

                <div class="row">
                    <div class="col-md-4 col-sm-10 form-group @if ($errors->has('destino')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Destino <span class="text-danger">*</span></label>
                        <select name="destino" id="select-destino" onchange="selectForm($(this).val())"
                            class="form-control  @if ($errors->has('destino')) form-control-danger @endif">
                            <option selected hidden disabled>Selecione ...</option>
                            @foreach ($opcoes_destino as $id => $opcao)
                                <option @if (old('destino', $solicitacao->destino) == $id) selected="selected" @endif value="{{ $id }}">{{ $opcao }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('destino'))
                            <small class="form-control-feedback">{{ $errors->first('destino') }}</small>
                        @endif
                    </div>

                    <div class="col-md-8 col-sm-10 form-group @if ($errors->has('estoque_origem_id')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Estoque de origem <span class="text-danger">*</span></label>
                        <select id="estoque-origem-select" name="estoque_origem_id" style="width: 100%"
                            class="form-control  @if ($errors->has('estoque_origem_id')) form-control-danger @endif">
                            <option selected hidden disabled>Selecione ...</option>
                            @foreach ($estoques_origem as $estoque)
                                <option @if (old('estoque_origem_id', $solicitacao->estoque_origem_id) == $estoque->id) selected="selected" @endif value="{{ $estoque->id }}">{{ $estoque->descricao }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('estoque_origem_id'))
                            <small class="form-control-feedback">{{ $errors->first('estoque_origem_id') }}</small>
                        @endif
                    </div>
                </div>

                <div id="form-destino-1" class="row form-switcher" style="display: none">
                    <div class="col-md-6 col-sm-10 form-group @if ($errors->has('agendamento_atendimentos_id')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Atendimento <span class="text-danger">*</span></label>
                        <select id="agendamento-select" name="agendamento_atendimentos_id" style="width: 100%"
                            class="form-control  @if ($errors->has('agendamento_atendimentos_id')) form-control-danger @endif">
                            @php
                                if($solicitacao->destino == 1) {
                                    $atendimento = $solicitacao->estoqueDestino()['agendamento_atendimento']->first();
                                    if(!empty($atendimento)) {
                                        $data = (new \DateTime($atendimento->data_hora))->format('d/m/Y');
                                        $hora = (new \DateTime($atendimento->data_hora))->format('H:i:s');
                                        $prestador = !empty($atendimento->agendamento->instituicoesPrestadores ?? null) ? $atendimento->agendamento->instituicoesPrestadores->prestador->nome : '';
                                    }
                                } else {
                                    $atendimento = null;
                                }
                                if($atendimento) {
                                }
                            @endphp
                            @if($atendimento)
                                <option value="{{ $atendimento->id }}" selected>{{ "#{$atendimento->agendamento_id} {$data} - {$hora} - {$atendimento->pessoa->nome} - {$prestador}" }}</option>
                            @endif
                        </select>
                        @if ($errors->has('agendamento_atendimentos_id'))
                            <small class="form-control-feedback">{{ $errors->first('agendamento_atendimentos_id') }}</small>
                        @endif
                    </div>
                    <div class="col-md-6 col-sm-10 form-group">
                        <label class="form-control-label p-0 m-0">Paciente</label>
                        <input value="@if($atendimento) {{ $atendimento->pessoa->nome }} @endif" readonly id="paciente-agendamento" class="form-control">
                    </div>

                    <div class="col-md-6 col-sm-10 form-group @if ($errors->has('instituicoes_prestadores_id')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Prestador solicitante <span class="text-danger">*</span></label>
                        <select id="prestador-select" name="instituicoes_prestadores_id" style="width: 100%"
                            class="form-control  @if ($errors->has('instituicoes_prestadores_id')) form-control-danger @endif">
                            @php
                                if($solicitacao->destino == 1) {
                                    $prestador = $solicitacao->estoqueDestino()['instituicao_prestador']->first();
                                } else {
                                    $prestador = null;
                                }
                            @endphp
                            @if ($prestador)
                                <option value="{{ $prestador->id }}" selected>{{ $prestador->prestador->nome }}</option>
                            @endif
                        </select>
                        @if ($errors->has('instituicoes_prestadores_id'))
                            <small class="form-control-feedback">{{ $errors->first('instituicoes_prestadores_id') }}</small>
                        @endif
                    </div>
                </div>

                <div id="form-destino-2" class="row form-switcher" style="display: none">
                    <div class="col-md-6 col-sm-8 form-group @if ($errors->has('setores_exame_id')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Setor <span class="text-danger">*</span></label>
                        <select name="setores_exame_id" style="width: 100%"
                            class="form-control  @if ($errors->has('setores_exame_id')) form-control-danger @endif">
                            <option selected hidden disabled>Selecione ...</option>
                            @foreach ($setores_exame as $setor)
                                <option @if (old('setores_exame_id', $solicitacao->setores_exame_id) == $setor->id) selected="selected" @endif value="{{ $setor->id }}">{{ $setor->descricao }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('setores_exame_id'))
                            <small class="form-control-feedback">{{ $errors->first('setores_exame_id') }}</small>
                        @endif
                    </div>
                    <div class="col-md-6 col-sm-8 form-group @if ($errors->has('unidades_internacoes_id')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Unidade de internação</label>
                        <select name="unidades_internacoes_id" style="width: 100%"
                            class="form-control  @if ($errors->has('unidades_internacoes_id')) form-control-danger @endif">
                            <option selected hidden disabled>Selecione ...</option>
                            @foreach ($unidades_internacao as $unidade)
                                <option @if (old('unidades_internacoes_id', $solicitacao->unidades_internacoes_id) == $unidade->id) selected="selected" @endif value="{{ $unidade->id }}">{{ $unidade->nome }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('unidades_internacoes_id'))
                            <small class="form-control-feedback">{{ $errors->first('unidades_internacoes_id') }}</small>
                        @endif
                    </div>
                </div>

                <div id="form-destino-3" class="row form-switcher" style="display: none">
                    <div class="col-md-8 col-sm-10 form-group @if ($errors->has('estoque_destino_id')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Estoque de destino <span class="text-danger">*</span></label>
                        <select name="estoque_destino_id" style="width: 100%"
                            class="form-control  @if ($errors->has('estoque_destino_id')) form-control-danger @endif">
                            <option selected hidden disabled>Selecione ...</option>
                            @foreach ($estoques_origem as $estoque)
                                <option @if (old('estoque_destino_id', $solicitacao->estoque_destino_id) == $estoque->id) selected="selected" @endif value="{{ $estoque->id }}">{{ $estoque->descricao }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('estoque_destino_id'))
                            <small class="form-control-feedback">{{ $errors->first('estoque_destino_id') }}</small>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-8 col-sm-10 form-group @if ($errors->has('observacoes')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Observações</label>
                        <textarea name="observacoes" rows="2"
                            class="form-control  @if ($errors->has('observacoes')) form-control-danger @endif">{{ old('observacoes', $solicitacao->observacoes) }}</textarea>
                        @if ($errors->has('observacoes'))
                            <small class="form-control-feedback">{{ $errors->first('observacoes') }}</small>
                        @endif
                    </div>
                    <div class="col-md-4 pt-4">
                        <div class="form-group d-flex flex-column justify-content-center @if($errors->has('urgente')) has-danger @endif">
                            <div class="d-flex flex-wrap-revert align-items-center">
                                <label class="form-control-label mr-2 mb-0">Urgente?</label>
                                <input type="checkbox" name="urgente" @if(old('urgente', $solicitacao->urgente)) checked="checked" @endif
                                class="form-control checkbox @if($errors->has('urgente')) form-control-danger @endif">
                            </div>
                            @if($errors->has('urgente'))
                                <div class="form-control-feedback">{{ $errors->first('urgente') }}</div>
                            @endif
                        </div>
                    </div>
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
                                        <col style="width: 300px">
                                        <col style="width: 300px">
                                        <col style="width: 100px">
                                        <col style="width: 50px">
                                    </colgroup>
                                    <thead>
                                        <tr>
                                            <th>Descrição</th>
                                            <th>Classe</th>
                                            <th>Unidade</th>
                                            <th>Quantidade</th>
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

                <div class="form-group text-right pb-2">
                    <a href="{{ route('instituicao.solicitacoes-estoque.index') }}">
                        <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i
                                class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                    </a>
                    <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i>
                        Salvar</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
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
    <script>
        const produto_container = $('#produtos-container')
        const protuto_template = $('#produto-template')
        const produto_select = $('#produto-select')
        let agendamentos_search = Array();
        let produto_next_id = 0;
        let produtos_selecionados = Array();
        var produtos_disponiveis = Array.from({!!  json_encode($produtos) !!});

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

        function selectForm(form = null)
        {
            if(!form)
                form = $('#select-destino').val()
            $('.form-switcher').hide()
            $(`#form-destino-${form}`).show()
        }

        $(document).ready(function() {
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
                escapeMarkup: function(m) {
                    return m;
                }
            });
            
            $('#agendamento-select').select2({
                placeholder: "Busque a partir da data ou paciente",
                ajax: {
                    url: "{{ route('instituicao.ajax.buscaagendamentos') }}",
                    type: 'post',
                    dataType: 'json',
                    quietMillis: 20,
                    data: function(params) {
                        return {
                            search: params.term,
                            status: 1,
                            '_token': '{{ csrf_token() }}'
                        };
                    },
                    processResults: function(data, params) {
                        agendamentos_search = Array()
                        return {
                            results: $.map(data.results, function(obj) {
                                const data = new Date(obj.data_hora);
                                let time = data.toLocaleString("default", {
                                    hour: "2-digit"
                                }) + ':' + data.toLocaleString("default", {
                                    minute: "2-digit"
                                });
                                if (time.length == 4) {
                                    time = time.split(':');
                                    time = `${time[0]}:0${time[1]}`;
                                }
                                let date = `${data.toLocaleString("default", { day: "2-digit" })}/${data.toLocaleString("default", { month: "2-digit" })}/${data.toLocaleString("default", { year: "numeric" })}`;
                                const option = {
                                    id: obj.id,
                                    text: `#${obj.agendamento_id} ${date} - ${time} - ${obj.pessoa.nome} - ${obj.agendamento.instituicoes_prestadores.prestador.nome}`,
                                    nome: obj.pessoa.nome
                                }
                                // cria uma lista com os resultados para reutilizar os nomes
                                agendamentos_search.push(option)
                                return option;
                            }),
                            pagination: {
                                more: data.pagination.more
                            }
                        }
                    }
                },
                minimumInputLength: 3,
                language: {
                    searching: function() {
                        return 'Buscando agendamento';
                    },

                    noResults: function() {
                        return 'Nenhum resultado encontrado';
                    },

                    inputTooShort: function(input) {
                        return "Digite " + (input.minimum - input.input.length) +
                            " caracteres para pesquisar";
                    },
                },
                escapeMarkup: function(m) {
                    return m;
                }
            }).on('select2:select', function (e) {
                // busca o nome do resultado
                let selected = $(e.target).val()
                $('#paciente-agendamento').val(agendamentos_search.find(el => el.id == selected).nome)
            });
            $('[name="estoque_destino_id"]').select2()
            $('[name="estoque_origem_id"]').select2()
            $('[name="unidades_internacoes_id"]').select2()
            $('.checkbox').iCheck({
                checkboxClass: 'icheckbox_square',
                radioClass: 'iradio_square',
                increaseArea: '90%'
            })
            selectForm()

            const produtos_a_inserir = {!! json_encode($produtos) !!};
            if(produtos_a_inserir.length > 0) {
                produtos_a_inserir.forEach(item => {
                    addProduct(item, true);
                });
            }
        })
    </script>
@endpush
