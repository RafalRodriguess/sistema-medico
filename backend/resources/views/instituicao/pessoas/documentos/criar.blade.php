@extends('instituicao.layout')

@section('conteudo')

    @component('components/page-title', [
        'titulo' => 'Cadastrar Documento',
        'breadcrumb' => [
            "Documentos do paciente #$pessoa->id" => route('instituicao.pessoas.documentos.index', [$pessoa]),
            'Novo',
        ],
    ])
    @endcomponent


    <div class="card">
        <div class="card-body ">
            <form action="{{ route('instituicao.pessoas.documentos.store', [$pessoa]) }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row p-2">
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label class="form-control-label p-0 m-0">Tipo de Documento <span class="text-danger">*</span></label>
                            <select class="form-control" name="tipo">
                                <option selected disabled>Tipo</option>
                                @foreach ($tipos_documentos as $tipo)
                                    <option value="{{ $tipo }}">{{ App\PessoaDocumento::getTipoTexto($tipo) }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('tipo'))
                                <small class="form-text text-danger">{{ $errors->first('tipo') }}</small>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <label class="form-control-label p-0 m-0">Descrição<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="descricao">
                        @if($errors->has('descricao'))
                            <small class="form-text text-danger">{{ $errors->first('descricao') }}</small>
                        @endif
                    </div>
                    <div class="col-sm-7">
                        <div class="form-group">
                            <label class="p-0 m-0">Arquivo <span class="text-danger">*</span></label>
                            <div class="custom-file">
                                <input type="file" name="arquivo" class="form-control custom-file-input arquivo">
                                <label class="custom-file-label">Selecione o Arquivo</label>
                            </div>
                            @if($errors->has('arquivo'))
                                <small class="form-text text-danger">{{ $errors->first('arquivo') }}</small>
                            @endif
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
        $(document).ready(function(){
            $('.arquivo').each(function(){
                $(this).on('change',function(){
                    let fileName = $(this).val();
                    $(this).next('.custom-file-label').html(fileName);
                });
            });
        });
    </script>
@endpush
