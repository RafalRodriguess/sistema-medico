@extends('instituicao.layout')

@section('conteudo')

    @component('components/page-title', [
        'titulo' => 'Editar Documento',
        'breadcrumb' => [
            "Documentos do paciente #$pessoa->id" => route('instituicao.pessoas.documentos.index', [$pessoa]),
            'Atualizar',
        ],
    ])
    @endcomponent


    <div class="card">
        <div class="card-body ">
            <form action="{{ route('instituicao.pessoas.documentos.update', [$pessoa, $documento]) }}" method="post" enctype="multipart/form-data">
                @method('put')
                @csrf
                <div class="row p-2">
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label class="form-control-label p-0 m-0">Tipo de Documento <span class="text-danger">*</span></label>
                            <select class="form-control" name="tipo">
                                <option selected disabled>Tipo</option>
                                @foreach ($tipos_documentos as $tipo)
                                    <option value="{{ $tipo }}" @if ($documento->tipo==$tipo)
                                        selected
                                    @endif @if (old('tipo')==$tipo)
                                        selected
                                    @endif >{{ App\PessoaDocumento::getTipoTexto($tipo) }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('tipo'))
                                <small class="form-text text-danger">{{ $errors->first('tipo') }}</small>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <label class="form-control-label p-0 m-0">Descrição<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" value="{{ old('descricao', $documento->descricao) }}" name="descricao">
                        @if($errors->has('descricao'))
                            <small class="form-text text-danger">{{ $errors->first('descricao') }}</small>
                        @endif
                    </div>
                    <div class="col-sm-7">
                        <div class="form-group">
                            <label class="p-0 m-0">Arquivo <span class="text-danger">*</span></label>
                            <div class="custom-file">
                                <input type="text" readonly value="{{ $documento->file_path_name }}" class="form-control custom-file-input">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group text-right pb-2">
                    <a href="{{ route('instituicao.pessoas.documentos.index', [$pessoa]) }}">
                        <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                    </a>
                    <button type="submit" id="salvar" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
                </div>
            </form>
        </div>
    </div>
@endsection


@push('scripts')
    <script>
    </script>
@endpush
