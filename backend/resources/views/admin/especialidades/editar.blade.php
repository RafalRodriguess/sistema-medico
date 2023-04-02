
@extends('admin.layout')

@section('conteudo')
    @component('components/page-title', [
        'titulo' => "Editar especialidade #{$especialidade->id} {$especialidade->descricao}",
        'breadcrumb' => [
            'Especialidades' => route('especialidades.index'),
            'Editar',
        ],
    ])
    @endcomponent


    <div class="card">
        <div class="card-body">
            <form action="{{ route('especialidades.update', [$especialidade]) }}" method="post">
                @method('put')
                @csrf

                <div class="row p-0 m-0">
                    <div class="col-sm-8 p-1 m-0">
                        <div class="form-group @if($errors->has('descricao')) has-danger @endif">
                            <label class="form-control-label p-0 m-0">Descrição <span class="text-danger">*</span></label>
                            <input type="text" name="descricao" value="{{ old('descricao', $especialidade->descricao) }}"
                                class="form-control @if($errors->has('descricao')) form-control-danger @endif">
                            @if($errors->has('descricao'))
                                <small class="form-control-feedback">{{ $errors->first('descricao') }}</small>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card card-body">
                            <div class="col-sm-6">
                                <div class=" @if($errors->has('especializacoes')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Especializações</label>
                                    <div class="input-group">
                                    <select id="especializacoes-select" name="especializacoes" type="text"
                                        class="form-control @if($errors->has('especializacoes')) form-control-danger @endif">
                                        <option selected disabled>Selecione ...</option>
                                        @foreach ($especializacoes as $especializacao)
                                            <option value="{{ $especializacao->id }}">{{ $especializacao->descricao }}</option>
                                        @endforeach
                                    </select>
                                    <button onclick="addEspecializacao()" type="button" class="btn btn-primary"><i class="fas fa-plus"></i></button>
                                </div>

                                    @if($errors->has('especializacoes'))
                                        <small class="form-control-feedback">{{ $errors->first('especializacoes') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-8 mt-2">
                                <table class="table table-bordered table-stripped">
                                    <tbody id="selected-especializacoes-container" class="bg-light">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="form-group text-right">
                    <a href="{{ route('especialidades.index') }}">
                        <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                    </a>
                    <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        var selected_especializacoes = Array()
        // Pega os ids das especialidades selecionadas
        const loaded_especializacoes = {!! json_encode(old('especializacoes', $especialidade->especializacoes()->get()->pluck('id'))) !!};

        function renderEspecializacao(value) {
            const container = $('#selected-especializacoes-container')
            container.append($(`
            <tr>
                <td style="vertical-align: middle">
                    <h4 class="m-0">${value.descricao}</h4>
                    <input value="${value.id}" type="hidden" name="especializacoes[][especializacoes_id]">
                </td>
                <td>
                    <div id="${value.id}__button_slot" class="d-flex justify-content-end"></div>
                </td>
            </tr>
            `))
            // Inserindo o botão no slot de botão
            $(`#${value.id}__button_slot`).append($(`
            <button type="button" class="btn btn-danger" onclick="removeEspecializacao(this)">
                <i class="fas fa-trash-alt"></i>
            </button>
            `))
        }

        function loadEspecializacao(data) {
            data.forEach(value => {
                const item = {
                    id: value,
                    descricao: $(`#especializacoes-select [value="${value}"]`).text()
                }
                renderEspecializacao(item)
                selected_especializacoes.push(item.id)
            })
        }

        function addEspecializacao() {
            const selected = $('#especializacoes-select').val()
            const value = {
                id: selected,
                descricao: $('#especializacoes-select').find(`[value="${selected}"]`).text()
            }

            if(!selected_especializacoes.find(element => element == value.id) && value.id != null) {
                renderEspecializacao(value)
                selected_especializacoes.push(value.id)
            }
        }

        function removeEspecializacao(element) {
            const container = $($(element).parents()[2])
            const value = container.find('input').val()
            // Remove from array
            selected_especializacoes = selected_especializacoes.filter(element => element != value)
            // Clear html
            container.remove();
        }

        $(() => {
            $('[name="especializacoes"]').select2()

            if(loaded_especializacoes.length > 0) {
                loadEspecializacao(loaded_especializacoes)
            }
        })
    </script>
@endpush
