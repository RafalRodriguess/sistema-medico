@extends('instituicao.layout')

@section('conteudo')
    @component('components/page-title', [
        'titulo' => 'Importar Vinculo Brasíndice',
        'breadcrumb' => [
            'Vincular Brasíndice' => route('instituicao.vinculoBrasindice.index'),
            'Importar',
        ],
    ])
    @endcomponent

    <div class="card">
        <div class="card-body">
            <form action="{{ route('instituicao.vinculoBrasindice.importar') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="form-group col-md-2 {{ $errors->has('edicao') ? 'has-danger' : '' }}">
                        <label class="form-control-label" for="edicao">
                            Edição
                        </label>
                        <input type="text" name="edicao" id="edicao"
                            class="form-control {{ $errors->has('edicao') ? 'form-control-danger' : '' }}"
                            value="{{ old('edicao') ?? '' }}">
                        @if ($errors->has('edicao'))
                            <span class="text-danger">{{ $errors->first('edicao') }}</span>
                        @endif
                    </div>

                    <div class="form-group col-md-2 {{ $errors->has('vigencia') ? 'has-danger' : '' }}">
                        <label class="form-control-label" for="vigencia">
                            Vigencia
                        </label>
                        <input type="date" name="vigencia" id="vigencia"
                            class="form-control {{ $errors->has('vigencia') ? 'form-control-danger' : '' }}"
                            value="{{ old('vigencia') ?? '' }}">
                        @if ($errors->has('vigencia'))
                            <span class="text-danger">{{ $errors->first('vigencia') }}</span>
                        @endif
                    </div>

                    <div class="form-group col-md-3 {{ $errors->has('tipo_id') ? 'has-danger' : '' }}">
                        <label class="form-control-label" for="tipo_id">
                            Tipo de importação <span class="text-danger">*</span>
                        </label>
                        <select name="tipo_id" id="tipo_id"
                            class="form-control {{ $errors->has('tipo_id') ? 'form-control-danger' : '' }}">
                            <option value="">Selecione...</option>
                            @foreach ($tipos as $tipo)
                                <option value="{{ $tipo->id }}"
                                    {{ old('tipo_id') == $tipo->id ? 'selected="selected"' : '' }}>
                                    {{ $tipo->descricao }}
                                </option>
                            @endforeach
                        </select>
                        @if ($errors->has('tipo_id'))
                            <span class="text-danger">{{ $errors->first('tipo_id') }}</span>
                        @endif
                    </div>

                    <div class="form-group col-md-5 {{ $errors->has('arquivo') ? 'has-danger' : '' }}">
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
                    <a href="{{ route('instituicao.vinculoBrasindice.index') }}"
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
