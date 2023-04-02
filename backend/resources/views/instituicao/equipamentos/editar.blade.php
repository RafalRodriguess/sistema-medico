@extends('instituicao.layout')

@section('conteudo')

    @component('components/page-title', [
        'titulo' => "Editar Equipamento #{$equipamento->id} {$equipamento->descricao}",
        'breadcrumb' => [
            'Equipamento' => route('instituicao.equipamentos.index'),
            'Atualização',
        ],
    ])
    @endcomponent


    <div class="card">
        <div class="card-body ">
            <form action="{{ route('instituicao.equipamentos.update', [$equipamento]) }}" method="post">

                @method('put')
                @csrf

                <div class="row">
                    <div class=" col-md form-group @if($errors->has('descricao')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Descrição</label>
                        <input type="text" name="descricao" value="{{ old('descricao', $equipamento->descricao) }}"
                        class="form-control @if($errors->has('descricao')) form-control-danger @endif">
                        @if($errors->has('descricao'))
                            <small class="form-control-feedback">{{ $errors->first('descricao') }}</small>
                        @endif
                    </div>

                    <div class="col-md form-group @if($errors->has('procedimento_id')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Procedimentos</label>
                        <select class="form-control selectProcedimento @if($errors->has('procedimento_id')) form-control-danger @endif" name="procedimento_id" id="procedimento_id">
                            <option value="{{$equipamento->procedimento_id}}">{{$equipamento->procedimento->descricao}}</option>
                            {{-- @foreach ($procedimentos as $item)
                                <option {{(old('procedimento_id', $equipamento->procedimento_id) == $item->id) ? 'selected' : '' }} value="{{ $item->id }}">{{ $item->descricao }}</option>
                            @endforeach --}}
                        </select>
                        @if($errors->has('procedimento_id'))
                            <small class="form-control-feedback">{{ $errors->first('procedimento_id') }}</small>
                        @endif
                    </div>
                </div>

            </div>

                <div class="form-group text-right pb-2">
                    <a href="{{ route('instituicao.equipamentos.index') }}">
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
        $(document).ready(function() {

            $(".selectProcedimento").select2({
                placeholder: "Selecione o procedimento",
                ajax: {
                    url: '{{route("instituicao.getprocedimentos")}}',
                    type: 'post',
                    dataType: 'json',
                    quietMillis: 20,
                    data: function(params) {
                        return {
                            q: params.term,
                            '_token': '{{csrf_token()}}',
                        };
                    },
                    processResults: function(data) {
                        // console.log(data)
                        return {
                            results: $.map(data, function(obj) {
                                return {
                                    id: obj.id,
                                    text: obj.descricao
                                };
                            })
                        }
                    }
                },
                escapeMarkup: function(m) {
                    return m;
                }
            });

        })
    </script>
@endpush
