@extends('instituicao.layout')

@section('conteudo')

    @component('components/page-title', [
        'titulo' => 'Cadastrar Modelo de termo e folha de sala',
        'breadcrumb' => [
            'Modelo de termo e folha de sala' => route('instituicao.modelosTermoFolhaSala.index'),
            'Novo',
        ],
    ])
    @endcomponent

    <div class="card">
        <div class="card-body ">
            <form action="{{ route('instituicao.modelosTermoFolhaSala.store') }}" method="post">
                @csrf
               <div class="row">
                    <div class=" col-md-6 form-group @if($errors->has('nome')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Nome *</label>
                        <input type="text" name="nome" value="{{ old('nome') }}"
                        class="form-control @if($errors->has('nome')) form-control-danger @endif">
                        @if($errors->has('nome'))
                            <small class="form-control-feedback">{{ $errors->first('nome') }}</small>
                        @endif
                    </div>
                    <div class="col-md-4" style="margin-top: 30px">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <input type="radio" id="termo_consentimento" name="tipo" class="filled-in" value="termo_consentimento" checked/>
                                <label for="termo_consentimento">Termo consentimento<label>
                            </div>
                            <div class="col-md-6 form-group">
                                <input type="radio" id="folha_sala" name="tipo" class="filled-in" value="folha_sala"/>
                                <label style="margin-left: 10px;" for="folha_sala">Folha de sala<label>
                            </div>
                        </div>
                    </div>
                    <div class=" col-md-12 form-group @if($errors->has('descricao')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Texto *</label>
                        <textarea class="form-control summernote @if($errors->has('descricao')) form-control-danger @endif" name="descricao" id="descricao" cols="30" rows="10">
                            {{ old('descricao') }}</textarea>
                        @if($errors->has('descricao'))
                            <small class="form-control-feedback">{{ $errors->first('descricao') }}</small>
                        @endif
                    </div>
               </div>

                <div class="form-group text-right pb-2">
                    <a href="{{ route('instituicao.modelosTermoFolhaSala.index') }}">
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
                toolbar: [
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['fontsize', ['fontsize']],
                    ['fontname', ['fontname']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['color', ['color']],
                    ['height', ['height']],
                    ['table', ['table']],
                    ['insert', ['hr']],
                    ['view', ['fullscreen']],
                    ['misc', ['codeview']]
                ],
            });
        })
    </script>
@endpush
