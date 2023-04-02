

@extends('instituicao.layout')

@section('conteudo')

    @component('components/page-title', [
        'titulo' => 'Cadastrar Acomodações',
        'breadcrumb' => [
            'Origem' => route('instituicao.internacao.acomodacoes.index'),
            'Novo',
        ],
    ])
    @endcomponent


    <div class="card">
        <div class="card-body ">
            <form action="{{ route('instituicao.internacao.acomodacoes.store') }}" method="post">
                @csrf

                <div class="row">
                    <div wire:ignore class="col-md-6 form-group @if($errors->has('descricao')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Descrição <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="descricao" value="{{ old('descricao') }}">
                        @if($errors->has('descricao'))
                            <small class="form-control-feedback">{{ $errors->first('descricao') }}</small>
                        @endif
                    </div>
                    <div class="col-md-6 form-group @if($errors->has('tipo_id')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Tipo <span class="text-danger">*</span></label>
                        <select class="form-control p-0 m-0" name="tipo_id">
                            <option selected disabled>Selecione</option>
                            @foreach ($tipos_acomodacoes as $tipo_id)
                                <option value="{{ $tipo_id }}" @if (old('tipo_id')==$tipo_id)
                                    selected
                                @endif>{{ App\Acomodacao::getTipoTexto($tipo_id) }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('tipo_id'))
                            <small class="form-control-feedback">{{ $errors->first('tipo_id') }}</small>
                        @endif
                    </div>

                </div>

                <div class="row">
                    <div class="ml-auto col-sm-6 p-0 pt-4 m-0">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="extra_virtual" value="1"
                                @if(old('extra_virtual')=="1")  checked @endif id="extraVirtualCheck">
                            <label class="form-check-label" for="extraVirtualCheck">Extra/Virtual</label>
                        </div>
                    </div>
                </div>


                <div class="form-group text-right pb-2">
                    <a href="{{ route('instituicao.internacao.acomodacoes.index') }}">
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
    </script>
@endpush
