


@extends('instituicao.layout')

@section('conteudo')

    <div class="row page-titles">
        <div class="col-md-12 col-8 align-self-center">
            <h3 class="text-themecolor m-b-0 m-t-0">Documento</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('instituicao.prestadores.documentos.index', [$prestador]) }}">
                        Documentos do prestador #{{ $prestador->id }} {{ $prestador->nome }}
                    </a>
                </li>
                <li class="breadcrumb-item active">Atualizar documento #{{ $documento->id }}</li>
            </ol>
        </div>
    </div>


    <div class="card col-sm-12">
        <div class="card-body">
            <form action="{{ route('instituicao.prestadores.documentos.update', [$prestador, $documento]) }}" method="post" enctype="multipart/form-data">
                @method('put')
                @csrf

                <div class="col-sm-12 p-0 m-0 documento-item">
                    <div class="row p-0 m-0 mb-3">
                        <div class="col-xl-3 p-1 m-0">
                            <div class="form-group">
                                <label class="form-control-label p-0 m-0">Tipo de Documento <span class="text-danger">*</span></label>
                                <select class="form-control" name="tipo">
                                    <option selected disabled>Tipo</option>
                                    <?php $tipos_documentos_prestadores = App\DocumentoPrestador::getTiposDocumentos(); ?>
                                    @foreach ($tipos_documentos_prestadores as $tipo_documento_prestador)
                                        <option value="{{ $tipo_documento_prestador }}"
                                            @if ($documento->tipo==$tipo_documento_prestador) selected @endif
                                            @if (old('tipo')==$tipo_documento_prestador) selected @endif
                                        >{{ App\DocumentoPrestador::getTipoDocumentoTexto($tipo_documento_prestador) }}</option>
                                    @endforeach
                                </select>
                                @if($errors->get("tipo"))
                                    <small class="form-text text-danger">{{ $errors->first("tipo") }}</small>
                                @endif
                            </div>
                        </div>
                        <div class="col-xl-9 p-1 m-0">
                            <label class="form-control-label p-0 m-0">Descrição <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="descricao" value="{{ old('descricao', $documento->descricao) }}">
                            @if($errors->get("descricao"))
                                <small class="form-text text-danger">{{ $errors->first("descricao") }}</small>
                            @endif
                        </div>
                        <div class="col-xl-12 p-1 m-0">
                            <div class="card p-2 m-0 shadow-none">
                                <div class="row p-0 m-0 d-flex justify-content-center">
                                    <small class="text-muted">{{ $documento->file_path_name }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group text-right">
                    <a href="{{ route('instituicao.prestadores.documentos.index', [$prestador]) }}">
                    <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                    </a>
                    <button id='submit' type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
                </div>
            </form>
        </div>
    </div>
@endsection


@push('scripts');
@endpush
