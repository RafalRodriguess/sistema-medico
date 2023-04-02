@extends('instituicao.layout')

@section('conteudo')

    @component('components/page-title', [
        'titulo' => 'Cadastrar Modelo de recibo',
        'breadcrumb' => [
            'Modelo de recibo' => route('instituicao.modelosRecibo.index'),
            'Novo',
        ],
    ])
    @endcomponent

    <div class="card">
        <div class="card-body ">
            <form action="{{ route('instituicao.modelosRecibo.update', [$modelo]) }}" method="post">
                @method('put')
                @csrf
               <div class="row">
                    <div class=" col-md-9 form-group @if($errors->has('descricao')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Descrição *</label>
                        <input type="text" name="descricao" value="{{ old('descricao', $modelo->descricao) }}"
                        class="form-control @if($errors->has('descricao')) form-control-danger @endif">
                        @if($errors->has('descricao'))
                            <small class="form-control-feedback">{{ $errors->first('descricao') }}</small>
                        @endif
                    </div>

                    <div class=" col-md-3 form-group @if($errors->has('vias')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Vias *</label>
                        <input type="text" alt='numeric' name="vias" value="{{ old('vias', $modelo->vias) }}"
                        class="form-control @if($errors->has('vias')) form-control-danger @endif">
                        @if($errors->has('vias'))
                            <small class="form-control-feedback">{{ $errors->first('vias') }}</small>
                        @endif
                    </div>

                    <div class=" col-md-12 form-group @if($errors->has('texto')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Texto *</label>
                        <textarea class="form-control summernote @if($errors->has('texto')) form-control-danger @endif" name="texto" id="texto" cols="30" rows="10">{{ old('texto', $modelo->texto) }}</textarea>
                        @if($errors->has('texto'))
                            <small class="form-control-feedback">{{ $errors->first('texto') }}</small>
                        @endif
                    </div>
               </div>

                <div class="form-group text-right pb-2">
                    <a href="{{ route('instituicao.modelosRecibo.index') }}">
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
        // $(document).ready(function() {
            $('.summernote').summernote({
                height: 350,
                hint: {
                    words: {!! variaveisTexto() !!},
                    match: /\B{(\w*)$/,
                    // match: /{(\w*)$/,
                    search: function (keyword, callback) {
                        callback($.grep(this.words, function (item) {
                            return item.indexOf('{' + keyword) === 0;
                        }));
                    },
                    content: function (item) {
                        return item;
                    }, 
                },  
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
        // })
    </script>
@endpush
