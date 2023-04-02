@extends('instituicao.layout')

@section('conteudo')
    @component('components/page-title',
        [
            'titulo' => 'Importar vinculo tuss',
            'breadcrumb' => [
                'Vinculo tuss' => route('instituicao.vinculoTuss.index'),
                'Importar',
            ],
        ])
    @endcomponent

    <div class="card">
        <div class="card-body ">
            <form action="{{ route('instituicao.vinculoTuss.importar') }}" method="post" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="form-group col-md-5 {{ $errors->has('terminologia_id') ? 'has-danger' : '' }}">
                        <label class="form-control-label" for="terminologia_id">
                            Terminologia <span class="text-danger">*</span>
                        </label>
                        <select name="terminologia_id" id="terminologia_id"
                            class="form-control {{ $errors->has('terminologia_id') ? 'form-control-danger' : '' }}">
                            <option value="">Selecione...</option>
                            @foreach ($terminologias as $termo)
                                <option value="{{ $termo->id }}"
                                    {{ old('terminologia_id') == $termo->id ? 'selected="selected"' : '' }}>
                                    {{ $termo->cod_tabela }} - {{ $termo->descricao }}
                                </option>
                            @endforeach
                        </select>
                        @if ($errors->has('terminologia_id'))
                            <span class="text-danger">{{ $errors->first('terminologia_id') }}</span>
                        @endif
                    </div>

                    <div class="form-group col-md-7 {{ $errors->has('arquivo') ? 'has-danger' : '' }}">
                        <label class="form-control-label" for="arquivo">
                            Arquivo <span class="text-danger">*</span>
                        </label>
                        <div class="custom-file">
                            <input id="arquivo" name="arquivo" type="file"
                                class="form-control custom-file-input arquivo field {{ $errors->has('arquivo') ? 'form-control-danger' : '' }}">
                            <label for="arquivo" class="custom-file-label">Selecione o Arquivo</label>
                        </div>
                        @if ($errors->has('arquivo'))
                            <span class="text-danger">{{ $errors->first('arquivo') }}</span>
                        @endif
                    </div>
                </div>

                <div class="form-group text-right">
                    <hr>
                    <a href="{{ route('instituicao.vinculoTuss.index') }}"
                        class="btn btn-secondary waves-effect waves-light m-r-10 btn-left">
                        <i class="mdi mdi-arrow-left-bold"></i> Voltar
                    </a>
                    <button type="submit" class="btn btn-success waves-effect waves-light m-r-10 btn-save">
                        <i class="mdi mdi-check"></i> Salvar
                    </button>
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

            $('form').on('submit', (e) => {
                $('.btn-left').attr('disabled', 'disabled');
                $('.btn-save').attr('disabled', 'disabled');
                $('.btn-save').html('<i class="mdi mdi-cloud-upload"></i> Aguarde, processando arquivo...');
            });
        })
    </script>
@endpush
