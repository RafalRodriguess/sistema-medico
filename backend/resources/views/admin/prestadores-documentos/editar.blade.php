@extends('admin.layout')

@section('conteudo')
    @component('components/page-title', [
        'titulo' => "Documento #{$documento->id} de {$prestador->nome}",
        'breadcrumb' => [
            "Documentos de {$prestador->nome}" => route('prestadores.documentos.index', [$prestador]),
            'Editar',
        ],
    ])
    @endcomponent


    <div class="card">
        <div class="card-body">
            <form action="{{ route('prestadores.documentos.update', [$prestador, $documento]) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('put')

                <div class="row p-0 m-0">
                    <div class="col-sm-4 form-group @if($errors->has('tipo')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Tipo <span class="text-danger">*</span></label>
                        <select name="tipo" class="form-control tipo field">
                            @foreach ($tipos as $tipo)
                                <option value="{{ $tipo }}" @if ($documento->tipo==$tipo)
                                    selected
                                @endif @if(old("tipo")==$tipo) selected @endif>
                                    {{ App\DocumentoPrestador::getTipoDocumentoTexto($tipo) }}
                                </option>
                            @endforeach
                        </select>
                        @if($errors->has('tipo'))
                            <div class="form-control-feedback">{{ $errors->first('tipo') }}</div>
                        @endif
                    </div>

                    <div class="col-sm-8 form-group @if($errors->has('descricao')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Descrição <span class="text-danger">*</span></label>
                        <input type="text" name="descricao" value="{{ old('descricao', $documento->descricao) }}"
                            class="form-control @if($errors->has('descricao')) form-control-danger @endif">
                        @if($errors->has('descricao'))
                            <div class="form-control-feedback">{{ $errors->first('descricao') }}</div>
                        @endif
                    </div>
                </div>


                <div class="form-group text-right">
                    <a href="{{ route('prestadores.documentos.index', [$prestador]) }}">
                        <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                    </a>
                    <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
                </div>
            </form>
        </div>
    </div>
@endsection
