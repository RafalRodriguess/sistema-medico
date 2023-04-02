@extends('instituicao.layout')

@section('conteudo')

    @component('components/page-title', [
        'titulo' => 'Editar Modelo de arquivo',
        'breadcrumb' => [
            'Modelo de arquivo' => route('instituicao.modeloArquivo.index'),
            'Editar',
        ],
    ])
    @endcomponent

    <div class="card">
        <div class="card-body ">
            <form action="{{ route('instituicao.modeloArquivo.update', [$arquivo]) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('put')
               <div class="row">
                    <div class=" col-md-12 form-group @if($errors->has('descricao')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Descrição *</label>
                        <input type="text" name="descricao" value="{{ old('descricao', $arquivo->descricao) }}"
                        class="form-control @if($errors->has('descricao')) form-control-danger @endif">
                        @if($errors->has('descricao'))
                            <small class="form-control-feedback">{{ $errors->first('descricao') }}</small>
                        @endif
                    </div>
                    <div class="col-lg-12 col-md-12 @if($errors->has('arquivo_upload')) has-danger @endif">
                        <label for="arquivo_upload">Arquivo *</label>
                        <input type="file" name="arquivo_upload" id="arquivo_upload" class="dropifyUpload @if($errors->has('arquivo_upload')) form-control-danger @endif" />
                        @if($errors->has('arquivo_upload'))
                            <small class="form-control-feedback">{{ $errors->first('arquivo_upload') }}</small>
                        @endif
                    </div>
               </div>

                <div class="form-group text-right pb-2">
                    <a href="{{ route('instituicao.modeloArquivo.index') }}">
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
            $(".dropifyUpload").dropify()
        })
    </script>
@endpush
