@extends('instituicao.layout')

@section('conteudo')
    @component('components/page-title', [
        'titulo' => 'Cadastrar Procedimentos',
        'breadcrumb' => [
        'Procedimentos' => route('instituicao.procedimentos.index'),
        'Novo',
        ],
        ])
    @endcomponent

    <div class="card">
        <div class="card-body">
            <form action="{{ route('instituicao.procedimentos.store') }}" method="post">
                @csrf

                <input type="hidden" name="id" value="">

                <div class="row">
                    <div class="col-md-3 form-group @error('grupo_id') has-danger @enderror">
                        <label class="form-control-label">Grupo *</label>
                        <select required id='grupo' name="grupo_id"
                            class="form-control @error('grupo_id') form-control-danger @enderror">
                            <option value="">Selecione</option>
                            @foreach ($grupos as $grupo)
                                <option value="{{ $grupo->id }}">
                                    {{ $grupo->nome }}
                                </option>
                            @endforeach
                        </select>
                        @error('grupo_id')
                            <div class="form-control-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 form-group @error('procedimentos_id') has-danger @enderror">
                        <label class="form-control-label">Procedimento *</label>
                        <select required name="procedimentos_id" id="procedimentos_id"
                            placeholder="Selecione o procedimentos_id"
                            class="form-control @error('procedimentos_id') form-control-danger @enderror">

                        </select>
                        @error('procedimentos_id')
                            <div class="form-control-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div style="display:none" id='div-atendimento' class="form-group">
                    <div class="col-md-12 @error('tipo') has-danger @enderror">
                        <label class="control-label">Forma de atendimento</label>
                    </div>
                    <div class="col-md-3">
                        <label class="i-checks">
                            <input type="radio" name="tipo" checked value="unico"> Hora marcada
                        </label>
                    </div>
                    <div class="col-md-3">
                        <label class="i-checks">
                            <input type="radio" name="tipo" value="avulso"> Check-in
                        </label>
                    </div>
                    <div class="col-md-3">
                        <label class="i-checks">
                            <input type="radio" name="tipo" value="ambos"> Ambos
                        </label>
                    </div>
                    @error('tipo')
                        <div class="form-control-feedback">{{ $message }}</div>
                    @enderror
                </div>


                <div class="row">

                    <div id="modalidade_exame_select" style="display: none" class="col-md-6 form-group @if($errors->has('modalidades_exame_id')) has-danger @endif">
                            <label class="form-control-label">Modalidade: *</label>
                            <select name="modalidades_exame_id" class="form-control
                            @if($errors->has('modalidades_exame_id')) form-control-danger @endif
                            " id="">
                            <option value="">Nenhuma</option>
                            @foreach ($modalidades as $modalidade)
                                <option value="{{ $modalidade->id }}" @if(old('modalidades_exame_id') == $modalidade->id) selected="selected" @endif>{{ $modalidade->sigla }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('modalidades_exame_id'))
                        <div class="form-control-feedback">{{ $errors->first('modalidades_exame_id') }}</div>
                        @endif
                    </div>
                </div>





                <div class="form-group text-right">
                    <hr>
                    <a href="{{ route('instituicao.procedimentos.index') }}">
                        <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i
                                class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                    </a>
                    <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i
                            class="mdi mdi-plus"></i> Adicionar Procedimento</button>
                </div>
            </form>


        </div>
    </div>
@endsection


@push('scripts')
    <script>
        $('input[type="radio"]').iCheck({
            checkboxClass: 'icheckbox_square-green',
            radioClass: 'iradio_square-green'
        })

        $(document).ready(function() {

            $('#grupo').select2({
                language: "pt-BR"
            }).on('change', function() {
                $('#procedimento').val(null).trigger("change");
            })


            $('#procedimentos_id').select2({
                tags: false,
                language: "pt-BR",
                placeholder: "Selecione os procedimentos",
                ajax: {
                    url: '{{ route('instituicao.getprocedimentosbygrupo') }}',
                    type: 'post',
                    dataType: 'json',
                    data: function(params) {
                        return {
                            nome: params.term,
                            '_token': '{{ csrf_token() }}',
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(obj) {
                                return {
                                    id: obj.id,
                                    text: obj.descricao,
                                    tipo: obj.tipo
                                };
                            })
                        }
                    }
                },
            }).on('select2:select', function(e) {
                if (e.params.data.tipo == 'exame') {
                    $('#div-atendimento').show()
                    $('#modalidade_exame_select').show();
                } else {
                    $('#modalidade_exame_select').val(null);
                    $('#modalidade_exame_select').hide();
                    $('#div-atendimento').hide()
                }
                console.log(e.params.data.tipo)
            })


        })
    </script>
@endpush
