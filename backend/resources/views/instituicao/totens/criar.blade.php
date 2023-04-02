@extends('instituicao.layout')

@section('conteudo')

    @component('components/page-title', [
        'titulo' => 'Cadastrar Totens',
        'breadcrumb' => [
            'Totens' => route('instituicao.triagem.totens.index'),
            'Novo',
        ],
    ])
    @endcomponent


    <div class="card">
        <div class="card-body ">
            <form action="{{ route('instituicao.triagem.totens.store') }}" method="post">
                @csrf

                <div class="row">
                    <div class="col-md-4 form-group @if($errors->has('nome')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">
                            Nome do Totem <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control @if($errors->has('nome')) form-control-danger @endif" name="nome"
                            value="{{ old('nome') }}">
                        @if($errors->has('nome'))
                            <small class="form-control-feedback">{{ $errors->first('nome') }}</small>
                        @endif
                    </div>


                    <div class=" col-md-8 form-group @if($errors->has('descricao')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Descrição</label>
                        <input type="text" name="descricao" value="{{ old('descricao') }}"
                        class="form-control @if($errors->has('descricao')) form-control-danger @endif">
                        @if($errors->has('descricao'))
                            <small class="form-control-feedback">{{ $errors->first('descricao') }}</small>
                        @endif
                    </div>
                </div>
                {{-- LISTA DE FILAS DO TOTEM --}}
                <div class="card py-2 d-flex flex-wrap flex-row default-checkboxes">

                    <div class=" col-md-5 form-group">
                        <label class="form-control-label p-0 m-0">Filas disponíveis</label>
                        <table class="table table-bordered" style="overflow-y: scroll">
                            <tbody id="filas-disponiveis-container">
                                @foreach ($filas_disponiveis as $fila)
                                    <tr class="fila-disponivel-input">
                                        <input value="{{ $fila->id }}" type="hidden" class="value-input">
                                        <td class="col-2"><input type="checkbox" class="mx-auto my-0"></td>
                                        <td class="text">{{ $fila->descricao }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-2 py-4 bg-light d-flex align-items-center" style="flex-wrap: wrap">
                        <div class="form-group col-md-12 p-0">
                            <button type="button" id="add-fila-button"  style="width: 100%" class="btn btn-secondary">Adicionar</button>
                        </div>
                        <div class="form-group col-md-12 p-0">
                            <button type="button" id="add-fila-all-button" style="width: 100%" class="btn btn-secondary">Adicionar todos</button>
                        </div>
                        <div class="form-group col-md-12 p-0">
                            <button type="button" id="remove-fila-button" style="width: 100%" class="btn btn-secondary">Remover</button>
                        </div>
                        <div class="form-group col-md-12 p-0 m-0">
                            <button type="button" id="remove-fila-all-button" style="width: 100%" class="btn btn-secondary">Remover todos</button>
                        </div>
                    </div>
                    <div class=" col-md-5 form-group">
                        <label class="form-control-label p-0 m-0">Filas escolhidas</label>
                        <table class="table table-bordered" style="overflow-y: scroll">
                            <tbody id="filas-escolhidas-container">
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

                <div class="form-group text-right pb-2">
                    <a href="{{ route('instituicao.triagem.totens.index') }}">
                        <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                    </a>
                    <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    <script type="text/template" id="fila-disponivel-input-template">
        <tr class="fila-disponivel-input">
            <input type="hidden" class="value-input">
            <td class="col-2"><input type="checkbox" class="mx-auto my-0"></td>
            <td class="text"></td>
        </tr>
    </script>
    <script type="text/template" id="fila-escolhida-input-template">
        <tr class="fila-escolhida-input">
            <input default_name="filas[][filas_triagem_id]" type="hidden" class="value-input">
            <td class="col-2"><input type="checkbox" class="mx-auto my-0"></td>
            <td class="text"></td>
        </tr>
    </script>
    <script>
        const container_selected = '#filas-escolhidas-container'
        const container_available = '#filas-disponiveis-container'

        function insertSelected(item)
        {
            const template = $($('#fila-escolhida-input-template').html())
            const input = template.find('.value-input')
            template.find('.text').text(item.text)
            input.val(item.id)
            input.attr('name', input.attr('default_name'))
            $(container_selected).append(template);
        }

        function insertAvailable(item)
        {
            const template = $($('#fila-disponivel-input-template').html())
            template.find('.text').text(item.text)
            template.find('.value-input').val(item.id)
            $(container_available).append(template);
        }

        function addFila(checked_only = true)
        {
            const values = Array.from($(`${container_available} tr`))
            values.forEach(function (row) {
                if(checked_only && !$(row).find('[type="checkbox"]').prop('checked'))
                    return

                insertSelected({
                    id: $(row).find('.value-input').val(),
                    text: $(row).find('.text').text()
                });
                $(row).remove()
            })
        }

        function removeFila(checked_only = true)
        {
            const values = Array.from($(`${container_selected} tr`))
            values.forEach(function (row) {
                if(checked_only && !$(row).find('[type="checkbox"]').prop('checked'))
                    return

                insertAvailable({
                    id: $(row).find('.value-input').val(),
                    text: $(row).find('.text').text()
                });
                $(row).remove()
            })
        }

        $(document).ready(function() {
            $('#add-fila-button').on('click', function() { addFila() })
            $('#add-fila-all-button').on('click', function() { addFila(false) })
            $('#remove-fila-button').on('click', function() { removeFila() })
            $('#remove-fila-all-button').on('click', function() { removeFila(false) })
        })
    </script>
@endpush
