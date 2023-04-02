@extends('instituicao.layout')

@section('conteudo')

    @component('components/page-title', [
        'titulo' => "Editar Prestador #{$origem->id} {$origem->descricao}",
        'breadcrumb' => [
            'Origem' => route('instituicao.origem.index'),
            'Atualizar',
        ],
    ])
    @endcomponent


    <div class="card">
        <div class="card-body ">
            <form action="{{ route('instituicao.origem.update', [$origem]) }}" method="post">
                @method('put')
                @csrf

                <div class="row">

                    <div wire:ignore class="col-md-6 form-group @if($errors->has('descricao')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Descrição <span class="text-danger">*</span></label>
                        <input type="text" class="form-control"
                            name="descricao" value="{{ old('descricao', $origem->descricao) }}">
                        @if($errors->has('descricao'))
                            <small class="form-control-feedback">{{ $errors->first('descricao') }}</small>
                        @endif
                    </div>

                    <div class="col-md-6 form-group @if($errors->has('cc_id')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Centro de Custo <span class="text-danger">*</span></label>
                        <select class="form-control p-0 m-0" name="cc_id">
                            <option selected disabled>Selecione</option>
                            @foreach ($centros_custos as $centro_custo)
                                <option value="{{ $centro_custo->id }}" @if ($origem->cc_id==$centro_custo->id)
                                    selected
                                @endif @if (old('cc_id')==$centro_custo->id)
                                    selected
                                @endif>{{ $centro_custo->codigo }} {{ $centro_custo->descricao }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('cc_id'))
                            <small class="form-control-feedback">{{ $errors->first('cc_id') }}</small>
                        @endif
                    </div>

                </div>

                <div class="row">

                    <div class="col-md-4 form-group @if($errors->has('tipo_id')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Tipo de Unidade <span class="text-danger">*</span></label>
                        <select class="form-control p-0 m-0" name="tipo_id">
                            <option selected disabled>Selecione</option>
                            @foreach ($tipos as $tipo)
                                <option value="{{ $tipo }}" @if ($origem->tipo_id==$tipo)
                                    selected
                                @endif @if (old('tipo_id')==$tipo)
                                    selected
                                @endif>{{ App\Origem::getTipoTexto($tipo) }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('tipo_id'))
                            <small class="form-control-feedback">{{ $errors->first('tipo_id') }}</small>
                        @endif
                    </div>

                    <div class="col-sm-3 p-0 pt-4 m-0">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="ativo" value="1"
                                @if ($origem->ativo=="1")
                                    checked
                                @endif
                                @if(old('ativo')=="1")
                                    checked
                                @endif id="ativoCheck">
                            <label class="form-check-label" for="ativoCheck">Ativo</label>
                        </div>
                    </div>
                </div>


                <div class="form-group text-right pb-2">
                    <a href="{{ route('instituicao.origem.index') }}">
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
