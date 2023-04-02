

@extends('instituicao.layout')

@section('conteudo')
    @component('components/page-title', [
        'titulo' => 'Cadastrar Documentos',
        'breadcrumb' => [
            'Prestador' => route('instituicao.prestadores.documentos.index', [$prestador]),
            'Novo',
        ],
    ])
    @endcomponent


    <div class="card col-sm-12">
        <div class="card-body">
            <form action="{{ route('instituicao.prestadores.documentos.store', [$prestador]) }}" method="post" enctype="multipart/form-data">
                @csrf

                <div class="col-sm-12 p-0 m-0 pl-3 pr-3 pb-5">
                    <div class="card col-sm-12 p-0 m-0">
                        <div class="row d-flex justify-content-between p-3 m-0">
                            <div class="col-xl-3 p-1 m-0">
                                <span class="title">Documentos</span>
                            </div>
                            <div class="col-xl-1 d-flex p-1 m-0">
                                <div class="row col-sm-12 d-flex justify-content-end align-self-center p-0 m-0">
                                    <button type="button" class="btn btn-primary" id="adiciona-documento">+</button>
                                </div>
                            </div>
                        </div>
                        <div class="row p-3 m-0" id="documentos-lista">

                            @if(old('documentos'))
                                @for ($i = 0; $i < count(old('documentos')) ; $i ++)
                                    <div class="col-sm-12 p-0 m-0 documento-item" id="{{ $i }}">
                                        <div class="row p-0 m-0">
                                            <div class="col-xl-3 p-1 m-0">
                                                <div class="form-group">
                                                    <label class="form-control-label p-0 m-0">Tipo de Documento <span class="text-danger">*</span></label>
                                                    <select name="documentos[{{$i}}][tipo]" class="form-control tipo field">
                                                        <option selected disabled>Tipo</option>
                                                        <?php $tipos_documentos_prestadores = App\DocumentoPrestador::getTiposDocumentos(); ?>
                                                        @foreach ($tipos_documentos_prestadores as $tipo_documento_prestador)
                                                            <option value="{{ $tipo_documento_prestador }}"
                                                                @if(old("documentos.{$i}.tipo")==$tipo_documento_prestador)
                                                                    selected
                                                                @endif>{{ App\DocumentoPrestador::getTipoDocumentoTexto($tipo_documento_prestador) }}</option>
                                                        @endforeach
                                                    </select>
                                                    @if($errors->get("documentos.{$i}.tipo"))
                                                        <small class="form-text text-danger">{{ $errors->first("documentos.{$i}.tipo") }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-xl-4 p-1 m-0">
                                                <label class="form-control-label p-0 m-0">Descrição<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control descricao field" name="documentos[{{$i}}][descricao]"
                                                @if (old("documentos.{$i}.descricao"))
                                                    value='{{ old("documentos.{$i}.descricao") }}'
                                                @endif>
                                                @if($errors->get("documentos.{$i}.descricao"))
                                                    <small class="form-text text-danger">{{ $errors->first("documentos.{$i}.descricao") }}</small>
                                                @endif
                                            </div>
                                            <div class="col-xl-4 p-1 m-0">
                                                <label class="form-control-label p-0 m-0">Arquivo<span class="text-danger">*</span></label>
                                                <input type="file" class="form-control-file arquivo field" name="documentos[{{$i}}][arquivo]">
                                                @if($errors->get("documentos.{$i}.arquivo"))
                                                    <small class="form-text text-danger">{{ $errors->first("documentos.{$i}.arquivo") }}</small>
                                                @endif
                                            </div>
                                            <div class="col-xl-1 d-flex p-1 m-0">
                                                <div class="row col-sm-12 d-flex justify-content-end align-self-center p-0 m-0">
                                                    <button type="button" class="btn btn-danger remover field"
                                                    onclick="document.querySelector('#documentos-lista').removeChild(this.parentElement.parentElement.parentElement.parentElement)">-</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endfor
                            @endif

                        </div>

                    </div>
                </div>

                <div class="form-group text-right">
                    <a href="{{ route('instituicao.prestadores.index', [$prestador]) }}">
                    <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                    </a>
                    <button id='submit' type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
                </div>
            </form>
        </div>
    </div>
@endsection


@push('scripts');

    <script type="text/template" id="base-documento-item">
        <div class="col-sm-12 p-0 m-0 documento-item">
            <div class="row p-0 m-0">
                <div class="col-xl-3 p-1 m-0">
                    <div class="form-group">
                        <label class="form-control-label p-0 m-0">Tipo de Documento <span class="text-danger">*</span></label>
                        <select class="form-control tipo field">
                            <option selected disabled>Tipo</option>
                            <?php $tipos_documentos_prestadores = App\DocumentoPrestador::getTiposDocumentos(); ?>
                            @foreach ($tipos_documentos_prestadores as $tipo_documento_prestador)
                                <option value="{{ $tipo_documento_prestador }}">{{ App\DocumentoPrestador::getTipoDocumentoTexto($tipo_documento_prestador) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-xl-4 p-1 m-0">
                    <label class="form-control-label p-0 m-0">Descrição<span class="text-danger">*</span></label>
                    <input type="text" class="form-control descricao field">
                </div>
                <div class="col-xl-4 p-1 m-0">
                    <label class="form-control-label p-0 m-0">Arquivo<span class="text-danger">*</span></label>
                    <input type="file" class="form-control-file arquivo field">
                </div>
                <div class="col-xl-1 d-flex p-1 m-0">
                    <div class="row col-sm-12 d-flex justify-content-end align-self-center p-0 m-0">
                        <button type="button" class="btn btn-danger remover field"
                        onclick="document.querySelector('#documentos-lista').removeChild(this.parentElement.parentElement.parentElement.parentElement)">-</button>
                    </div>
                </div>
            </div>
        </div>
    </script>

    <script>
        $(document).ready(function() {

            function documentos() {
                function hasClass(elemento, classe) {
                    return (' ' + elemento.className + ' ').indexOf(' ' + classe + ' ') > -1;
                }
                document.querySelector('#adiciona-documento').addEventListener('click', ()=>{
                    let lista_documentos = document.querySelector('#documentos-lista')
                    let id = lista_documentos.querySelectorAll('.documento-item').length
                    let new_documento = $($('#base-documento-item').html())[0]
                    new_documento.setAttribute('id', `${id}`)
                    new_documento.querySelectorAll('.field').forEach((field)=>{
                        if(hasClass(field, 'tipo')) field.name = `documentos[${id}][tipo]`
                        if(hasClass(field, 'arquivo')) field.name = `documentos[${id}][arquivo]`
                        if(hasClass(field, 'descricao')) field.name = `documentos[${id}][descricao]`
                    })
                    lista_documentos.appendChild(new_documento)
                })
            }

            documentos()

        })
    </script>
@endpush
