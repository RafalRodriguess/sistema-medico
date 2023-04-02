@extends('instituicao.layout')

@section('conteudo')

    @component('components/page-title', [
        'titulo' => 'Cadastrar Modelo de conclusão',
        'breadcrumb' => [
            'Modelo de conclusão' => route('instituicao.modeloConclusao.index'),
            'Novo',
        ],
    ])
    @endcomponent

    <div class="card">
        <div class="card-body ">
            <form action="{{ route('instituicao.modeloConclusao.store') }}" method="post">
                @csrf
               <div class="row">
                    <div class=" col-md-6 form-group @if($errors->has('instituicao_prestador_id')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Prestador</label>
                        <select class="form-control select2 @if($errors->has('instituicao_prestador_id')) form-control-danger @endif" name="instituicao_prestador_id" id="instituicao_prestador_id" style="width: 100%">
                            @foreach ($prestadores as $item)
                                <option value="{{$item->id}}" @if (old('instituicao_prestador_id') == $item->id)
                                    selected
                                @endif>
                                    {{$item->prestador->nome}} ({{($item->especialidade) ? $item->especialidade->descricao : ""}})
                                </option>
                            @endforeach
                        </select>
                        @if($errors->has('instituicao_prestador_id'))
                            <small class="form-control-feedback">{{ $errors->first('instituicao_prestador_id') }}</small>
                        @endif
                    </div>
                    <div class=" col-md-6 form-group @if($errors->has('motivo_conclusao_id')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Motivo</label>
                        <select class="form-control select2 @if($errors->has('motivo_conclusao_id')) form-control-danger @endif" name="motivo_conclusao_id" id="motivo_conclusao_id" style="width: 100%">
                            @foreach ($motivos as $item)
                                <option value="{{$item->id}}" @if (old('motivo_conclusao_id') == $item->id)
                                    selected
                                @endif>
                                    {{$item->descricao}}
                                </option>
                            @endforeach
                        </select>
                        @if($errors->has('motivo_conclusao_id'))
                            <small class="form-control-feedback">{{ $errors->first('motivo_conclusao_id') }}</small>
                        @endif
                    </div>
                    <div class=" col-md-12 form-group @if($errors->has('descricao')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Descrição *</label>
                        <input type="text" name="descricao" value="{{ old('descricao') }}"
                        class="form-control @if($errors->has('descricao')) form-control-danger @endif">
                        @if($errors->has('descricao'))
                            <small class="form-control-feedback">{{ $errors->first('descricao') }}</small>
                        @endif
                    </div>
                    <div class=" col-md-12 form-group @if($errors->has('texto')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Texto *</label>
                        <textarea class="form-control summernote @if($errors->has('texto')) form-control-danger @endif" name="texto" id="texto" cols="30" rows="10">
                            {{ old('texto') }}</textarea>
                        @if($errors->has('texto'))
                            <small class="form-control-feedback">{{ $errors->first('texto') }}</small>
                        @endif
                    </div>
               </div>

                <div class="form-group text-right pb-2">
                    <a href="{{ route('instituicao.modeloConclusao.index') }}">
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
            $('.summernote').summernote({
                height: 350,
                lineHeights: ['0.2', '0.3', '0.4', '0.5', '0.6', '0.8', '1.0', '1.2', '1.4', '1.5', '2.0', '3.0'],
            });
        })
    </script>
@endpush
