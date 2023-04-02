@extends('instituicao.layout')

@section('conteudo')

    @component('components/page-title', [
        'titulo' => "Editar fila {$fila->identificador}",
        'breadcrumb' => [
            'Filas para triagem' => route('instituicao.triagem.filas.index'),
            'Atualização',
        ],
    ])
    @endcomponent


    <div class="card">
        <div class="card-body ">
            <form action="{{ route('instituicao.triagem.filas.update', $fila) }}" method="post">
                @method('put')
                @csrf

                <div class="row">
                    <div class=" col-md-8 form-group @if ($errors->has('descricao')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Descrição</label>
                        <input type="text" name="descricao" value="{{ old('descricao', $fila->descricao) }}"
                            class="form-control @if ($errors->has('descricao')) form-control-danger @endif">
                        @if ($errors->has('descricao'))
                            <small class="form-control-feedback">{{ $errors->first('descricao') }}</small>
                        @endif
                    </div>
                    <div class="col-md-2 col-sm-3 form-group @if ($errors->has('identificador')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Identificador</label>
                        <select name="identificador" id="select-identificadores"
                            class="form-control  @if ($errors->has('identificador')) form-control-danger @endif">
                            @foreach ($identificadores as $identificador)
                                <option @if (old('identificador', $fila->identificador) == $identificador) selected="selected" @endif value="{{ $identificador }}">{{ $identificador }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('identificador'))
                            <small class="form-control-feedback">{{ $errors->first('identificador') }}</small>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-sm-8 form-group @if ($errors->has('origens_id')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Origem</label>
                        <select name="origens_id" id="select-origem"
                            class="form-control  @if ($errors->has('origens_id')) form-control-danger @endif">
                            <option class="disabled" hidden disabled selected>Selecione ...</option>
                            @foreach ($origens as $origem)
                                <option @if (old('origens_id', $fila->origens_id) == $origem->id) selected="selected" @endif value="{{ $origem->id }}">{{ $origem->descricao }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('origens_id'))
                            <small class="form-control-feedback">{{ $errors->first('origens_id') }}</small>
                        @endif
                    </div>
                    <div class="px-3 form-group  d-flex align-items-end pb-2 @if ($errors->has('ativo')) has-danger @endif">
                        <div class="d-flex align-items-baseline">
                            <input type="checkbox" name="ativo" id="check-ativo" @if(old('ativo', $fila->ativo) == 1) checked="checked" @endif>
                            <label for="check-ativo" class="my-0 ml-3">Ativo</label>
                        </div>
                        @if ($errors->has('ativo'))
                            <small class="form-control-feedback">{{ $errors->first('ativo') }}</small>
                        @endif
                    </div>
                    <div class="px-3 form-group  d-flex align-items-end pb-2 @if ($errors->has('prioridade')) has-danger @endif">
                        <div class="d-flex align-items-baseline">
                            <input type="checkbox" name="prioridade" id="check-prioridade" @if(old('prioridade', $fila->prioridade) == 1) checked="checked" @endif>
                            <label for="check-prioridade" class="my-0 ml-3">Prioridade</label>
                        </div>
                        @if ($errors->has('prioridade'))
                            <small class="form-control-feedback">{{ $errors->first('prioridade') }}</small>
                        @endif
                    </div>
                </div>
                {{-- LISTA DE PROCESSOS DA TRIAGEM --}}
                <div class="card py-2">
                    <div class="p-0 d-flex flex-wrap flex-row default-checkboxes">
                        <div class=" col-md-5 form-group">
                            <label class="form-control-label p-0 m-0">Processos disponíveis</label>
                            <table class="table table-bordered" style="overflow-y: scroll">
                                <colgroup>
                                    <col class="col-1">
                                    <col class="col-11">
                                </colgroup>
                                <tbody id="processos-disponiveis-container">
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-2 py-4 bg-light d-flex align-items-center" style="flex-wrap: wrap">
                            <div class="form-group col-md-12 p-0">
                                <button type="button" id="add-fila-button"  style="width: 100%" class="btn btn-secondary" onclick="addProcess()">Adicionar</button>
                            </div>
                            <div class="form-group col-md-12 p-0">
                                <button type="button" id="add-fila-all-button" style="width: 100%" class="btn btn-secondary" onclick="addProcess(true)">Adicionar todos</button>
                            </div>
                            <div class="form-group col-md-12 p-0">
                                <button type="button" id="remove-fila-button" style="width: 100%" class="btn btn-secondary" onclick="removeProcess()">Remover</button>
                            </div>
                            <div class="form-group col-md-12 p-0 m-0">
                                <button type="button" id="remove-fila-all-button" style="width: 100%" class="btn btn-secondary" onclick="removeProcess(true)">Remover todos</button>
                            </div>
                        </div>
                        <div class=" col-md-5 form-group">
                            <label class="form-control-label p-0 m-0">Processos escolhidos</label>
                            <table class="table table-bordered" style="width: 100%" style="overflow-y: scroll">
                                <colgroup>
                                    <col class="col-1">
                                    <col class="col-6">
                                    <col class="col-1">
                                    <col class="col-4">
                                </colgroup>
                                <tbody id="processos-escolhidos-container">
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if ($errors->has('processos.*'))
                        <div class="col-8 mt-2 mb-0 ml-2 mr-0 alert alert-danger">
                            <small>{{ $errors->first('processos.*') }}</small>
                        </div>
                    @endif
                </div>

                <div class="form-group text-right pb-2">
                    <a href="{{ route('instituicao.triagem.filas.index') }}">
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
    <script type="text/template" id="processo-disponivel-input-template">
        <tr class="processo-disponivel-input">
            <input type="hidden" class="value-input">
            <td class="col-2"><input type="checkbox" class="mx-auto my-0"></td>
            <td class="text"></td>
        </tr>
    </script>

    <script type="text/template" id="processo-escolhido-input-template">
        <tr class="processo-escolhido-input">
            <input data-name="id-input" name="processos[#][processos_triagem_id]" type="hidden" class="value-input"/>
            <input data-name="ordem-input" name="processos[#][ordem]" type="hidden" class="ordem-input"/>
            <td class="align-middle col-2"><input type="checkbox" class="mx-auto my-0"/></td>
            <td data-name="descricao-display" class="align-middle text"></td>
            <td data-name="ordem-display" class="align-middle order"></td>
            <td class="align-middle text-right">
                <div class="d-flex flex-wrap align-items-center justify-content-center">
                    <button type="button" data-name="up-button" class="up btn btn-secondary btn-sm mx-1"><i class="fas fa-chevron-up"></i></button>
                    <button type="button" data-name="down-button" class="down btn btn-secondary btn-sm mx-1"><i class="fas fa-chevron-down"></i></button>
                </div>
            </td>
        </tr>
    </script>
    <script>
        let   processos_disponiveis = Array.from({!! json_encode($processos_disponiveis->toArray()) !!});
        let   processos_selecionados = Array.from({!! json_encode($processos_selecionados) !!});
        const container_available = $('#processos-disponiveis-container');
        const container_selected = $('#processos-escolhidos-container');
        const template_available = $('#processo-disponivel-input-template');
        const template_selected = new HtmlTemplate('#processo-escolhido-input-template');
        let min_ordem = 0;
        let max_ordem = 0;

        /**
        *   Renderiza os items das tabelas
        **/
        function renderProcessos()
        {
            // Limpar contêiners
            container_available.empty()
            container_selected.empty()
            // Renderizar container de processos disponíveis
            processos_disponiveis.forEach(item => {
                const template = $(template_available.html())
                template.find('.value-input').val(item.id)
                template.find('.text').text(item.descricao)
                container_available.append(template)
            })
            // Renderiza os processos selecionados se necessário
            if(processos_selecionados.length > 0) {
                // Ordernar a lista de processos escolhidos
                processos_selecionados = processos_selecionados.sort((prev, next) => { return prev.ordem - next.ordem })
                // Renderizar container de processos escolhidos
                processos_selecionados.forEach(item => {
                    const element = template_selected.create({
                        'id-input': item.id,
                        'ordem-input': item.ordem,
                        'descricao-display': item.descricao,
                        'ordem-display': item.ordem + 'º',
                        'up-button': {
                            onclick: (e) => moveUp($(e.target).parents('.processo-escolhido-input'))
                        },
                        'down-button': {
                            onclick: (e) => moveDown($(e.target).parents('.processo-escolhido-input'))
                        }
                    }, item.id);
                    container_selected.append(element);
                })
            } else {
                // Resetando as ordens
                min_ordem = 0
                max_ordem = -1
            }
        }

        /**
        *   Move um item para a posicao de cima se possível
        **/
        function moveUp(element)
        {
            // Pega a instância do item
            const item = processos_selecionados.find(el => el.id == (element.find('.value-input').val()[0] ?? -1))
            // Impedir de ficar com ordem negativa
            if(item == undefined || item.ordem == min_ordem)
                return

            // Sobe o elemento atual
            const index = processos_selecionados.indexOf(item)
            processos_selecionados[index - 1].ordem ++
            processos_selecionados[index].ordem --
            // Renderiza novamente
            renderProcessos()
        }

        /**
        *   Move um item para a posicao de baixo se possível
        **/
        function moveDown(element)
        {
            // Pega a instância do item
            const item = processos_selecionados.find(el => el.id == (element.find('.value-input').val()[0] ?? -1))
            // Impedir de ficar com ordem negativa
            if(item == undefined || item.ordem == max_ordem)
                return

            // Desce o elemento atual
            const index = processos_selecionados.indexOf(item)
            processos_selecionados[index + 1].ordem --
            processos_selecionados[index].ordem ++
            // Renderiza novamente
            renderProcessos()
        }

        /**
        *   Adiciona um item caso o parametro all seja false, ou todos
        *   caso ele seja true
        *   @param bool all Define se serão removidos os marcados ou todos os itens
        **/
        function addProcess(all = false)
        {
            if(all) {
                while(processos_disponiveis.length > 0) {
                    const item = processos_disponiveis.pop()
                    // Caso não esteja na lista de disponíveis, insira na de disponíveis
                    if(processos_selecionados.find(el => el.id == item.id) == undefined) {
                        max_ordem ++
                        item.ordem = max_ordem
                        processos_selecionados.push(item)
                    }
                }
            } else {
                const items_to_add = Array()
                // Localizar os items a serem removidos
                container_available.find('[type="checkbox"]').each(function (key, item) {
                    if($(item).prop('checked')) {
                        items_to_add.push(parseInt($($(item).parents()[1]).find('.value-input').val()))
                    }
                })
                // Adicionar itens
                items_to_add.forEach(function(id) {
                    const item = processos_disponiveis.find(el => el.id === id)
                    const index = processos_disponiveis.indexOf(item)
                    if(item !== undefined) {
                        const removed = processos_disponiveis.splice(index, 1)[0]
                        max_ordem ++
                        removed.ordem = max_ordem
                        processos_selecionados.push(removed)
                    }
                })
                // Refazer ordem da lista de processos disponíveis
                if(processos_disponiveis.length > 0) {
                    processos_disponiveis = processos_disponiveis.sort((prev, next) => { return prev.id - next.id })
                }
            }
            renderProcessos()
        }

        /**
        *   Remove um item caso o parametro all seja false, ou todos
        *   caso ele seja true
        *   @param bool all Define se serão removidos os marcados ou todos os itens
        **/
        function removeProcess(all = false)
        {
            if(all) {
                while(processos_selecionados.length > 0) {
                    const item = processos_selecionados.pop()
                    // Caso não esteja na lista de disponíveis, insira na de disponíveis
                    if(processos_disponiveis.find(el => el.id == item.id) == undefined) {
                        processos_disponiveis.push(item)
                    }
                }
            } else {
                const items_to_remove = Array()
                // Localizar os items a serem removidos
                container_selected.find('[type="checkbox"]').each(function (key, item) {
                    if($(item).prop('checked')) {
                        items_to_remove.push(parseInt($($(item).parents()[1]).find('.value-input').val()))
                    }
                })
                // Remover itens
                items_to_remove.forEach(function(id) {
                    const item = processos_selecionados.find(el => el.id === id)
                    const index = processos_selecionados.indexOf(item)
                    if(item !== undefined) {
                        processos_disponiveis.push(processos_selecionados.splice(index, 1)[0])
                    }
                })
                // Refazer ordem da lista de processos escolhidos
                if(processos_selecionados.length > 0) {
                    processos_selecionados = processos_selecionados.sort((prev, next) => { return prev.ordem - next.ordem })
                    processos_selecionados = processos_selecionados.map(function(item, index) {
                        item.ordem = index;
                        return item;
                    })
                    max_ordem = processos_selecionados[processos_selecionados.length - 1].ordem
                    min_ordem = 0
                }
            }
            renderProcessos()
        }


        $(document).ready(function() {
            $('#select-identificadores').select2()
            $('#select-origem').select2()
            renderProcessos()
        });
    </script>
@endpush
