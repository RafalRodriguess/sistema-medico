@extends('instituicao.layout')

    @section('conteudo')
    @component('components/page-title', [
        'titulo' => "Importar procedimentos do faturamento #{$faturamento->id} {$faturamento->descricao}",
        'breadcrumb' => [
            'Faturamento' => route('instituicao.faturamento.index'),
            'Procedimentos' => route('instituicao.faturamento.procedimentos', $faturamento),
            'Importar',
        ],
        ])
    @endcomponent


    <div class="card">
        <div class="card-body ">
            <form action="{{ route('instituicao.faturamento.importarProcedimentos', [$faturamento]) }}" method="post" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-md-8 form-group">
                        <label class="form-control-label">Arquivo <span class="text-danger">*</span></label>
                        <div class="custom-file">
                            <input id="arquivo" name="arquivo" type="file" class="form-control custom-file-input arquivo field">
                            <label for="arquivo" class="custom-file-label">Selecione o Arquivo</label>
                        </div>
                        @if($errors->has('arquivo'))
                            <span class="text-danger">{{ $errors->first('arquivo') }}</span>
                        @endif
                    </div>
                </div>

                <div class="form-group text-right">
                    <hr>
                    <a href="{{ route('instituicao.faturamento.procedimentos', $faturamento) }}">
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
        $(() => {
            $('#arquivo').on('change', (e) => {
                let name = $(e.target).val().split('\\');
                name = name[name.length - 1].split('/');
                name = name[name.length - 1];
                $('.custom-file-label').text(name);
            });
        })
    </script>
@endpush
