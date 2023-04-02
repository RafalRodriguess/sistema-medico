@extends('instituicao.layout')

@section('conteudo')

    @component('components/page-title', [
        'titulo' => "Editar Unidade de Internação #{$unidade_internacao->id}",
        'breadcrumb' => [
            'Unidades de Internações' => route('instituicao.internacao.unidade-internacao.index'),
            'Atualizar',
        ],
    ])
    @endcomponent


    <div class="card">
        <div class="card-body ">
            <form action="{{ route('instituicao.internacao.unidade-internacao.update', [$unidade_internacao]) }}" method="post">
                @method('put')
                @csrf

                <div class="row">
                    <div wire:ignore class="col-md-5 form-group @if($errors->has('nome')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Nome da Unidade <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" value="{{ old('nome', $unidade_internacao->nome) }}" name="nome">
                        @if($errors->has('nome'))
                            <small class="form-control-feedback">{{ $errors->first('nome') }}</small>
                        @endif
                    </div>
                    <div class="col-md-5 form-group @if($errors->has('cc_id')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Centro de Custo <span class="text-danger">*</span></label>
                        <select class="form-control p-0 m-0" name="cc_id">
                            <option selected disabled>Selecione</option>
                            @foreach ($centros_custos as $centro_custo)
                                <option value="{{ $centro_custo->id }}"
                                    @if ($unidade_internacao->cc_id==$centro_custo->id)
                                        selected
                                    @endif
                                    @if (old('cc_id')==$centro_custo->id)
                                        selected
                                    @endif>{{ $centro_custo->codigo }} {{ $centro_custo->descricao }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('cc_id'))
                            <small class="form-control-feedback">{{ $errors->first('cc_id') }}</small>
                        @endif
                    </div>
                    <div wire:ignore class="col-md-2 form-group @if($errors->has('hospital_dia')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Hospital Dia <span class="text-danger">*</span></label>
                        <select class="form-control p-0 m-0" name="hospital_dia">
                            <option selected disabled>Selecione</option>
                            <option value="1" @if ($unidade_internacao->hospital_dia=='1')
                                selected
                            @endif @if (old('hospital_dia')=='1')
                                selected
                            @endif>Sim</option>
                            <option value="0" @if ($unidade_internacao->hospital_dia=='0')
                                selected
                            @endif @if (old('hospital_dia')=='2')
                                selected
                            @endif>Não</option>
                        </select>
                        @if($errors->has('hospital_dia'))
                            <small class="form-control-feedback">{{ $errors->first('hospital_dia') }}</small>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 form-group @if($errors->has('tipo_unidade')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Tipo de Unidade <span class="text-danger">*</span></label>
                        <select class="form-control p-0 m-0" name="tipo_unidade">
                            <option selected disabled>Selecione</option>
                            @foreach ($tipos_unidades as $tipo_unidade)
                                <option value="{{ $tipo_unidade }}"
                                @if ($unidade_internacao->tipo_unidade==$tipo_unidade)
                                    selected
                                @endif @if (old('tipo_unidade')==$tipo_unidade)
                                    selected
                                @endif>{{ App\UnidadeInternacao::getTipoUnidadeTexto($tipo_unidade) }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('tipo_unidade'))
                            <small class="form-control-feedback">{{ $errors->first('tipo_unidade') }}</small>
                        @endif
                    </div>
                    <div class="col-md-8 form-group @if($errors->has('localizacao')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Localização <span class="text-danger">*</span></label>
                        <input type="text" class="form-control"
                            value="{{ old('localizacao', $unidade_internacao->localizacao) }}" name="localizacao">
                        @if($errors->has('localizacao'))
                            <small class="form-control-feedback">{{ $errors->first('localizacao') }}</small>
                        @endif
                    </div>
                    <div class="col-sm-2 form-check pt-4 pl-3 m-0 mb-3">
                        <input type="checkbox" class="form-check-input p-0 m-0" name="ativo" value="1"
                            @if ($unidade_internacao->ativo=='1')
                                checked
                            @endif
                            @if(old('ativo')=="1")
                                checked
                            @endif id="ativoCheck">
                        <label class="form-check-label" for="ativoCheck">Ativo</label>
                    </div>
                </div>


                <div class="form-group text-right pb-2">
                    <a href="{{ route('instituicao.internacao.unidade-internacao.index') }}">
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
