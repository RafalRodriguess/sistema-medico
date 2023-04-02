





@extends('instituicao.layout')

@section('conteudo')

    @component('components/page-title', [
        'titulo' => 'Cadastrar Centro Cirúrgico',
        'breadcrumb' => [
            'Centros Cirúrgicos' => route('instituicao.centros.cirurgicos.index'),
            'Novo',
        ],
    ])
    @endcomponent


    <div class="card">

        <div class="card-body ">
            <form action="{{ route('instituicao.centros.cirurgicos.update', [$centro_cirurgico]) }}" method="post">
                @method('put')
                @csrf

                <div class="row">
                    <div wire:ignore class="col-md-6 form-group @if($errors->has('descricao')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Descrição <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="descricao"
                            value="{{ old('descricao', $centro_cirurgico->descricao) }}">
                        @if($errors->has('descricao'))
                            <small class="form-control-feedback">{{ $errors->first('descricao') }}</small>
                        @endif
                    </div>

                    <div class="col-md-6 form-group @if($errors->has('cc_id')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Centro de Custo <span class="text-danger">*</span></label>
                        <select class="form-control p-0 m-0" name="cc_id">
                            <option selected disabled>Selecione</option>
                            @foreach ($centros_custos as $centro_custo)
                                <option value="{{ $centro_custo->id }}"
                                    @if ($centro_cirurgico->cc_id==$centro_custo->id)
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

                <div class="row d-flex justify-content-center">
                    <div class="card col-sm-6 p-2 shadow-none">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group row m-1 d-flex justify-content-end">
                                    <label class="col-4 col-form-label">Início</label>
                                    <label class="col-4 col-form-label">Fim</label>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group row m-1">
                                    <label class="col-4 col-form-label pl-2">Segunda Feira</label>
                                    <div class="col-4 @if($errors->has('segunda_feira_inicio')) has-danger @endif">
                                        <input type="time" value="{{ old('segunda_feira_inicio', $centro_cirurgico->horarioFuncionamento()->get()[0]->segunda_feira_inicio) }}" name="segunda_feira_inicio" class="form-control">
                                    </div>
                                    <div class="col-4 @if($errors->has('segunda_feira_fim')) has-danger @endif">
                                        <input type="time" value="{{ old('segunda_feira_fim', $centro_cirurgico->horarioFuncionamento()->get()[0]->segunda_feira_fim) }}" name="segunda_feira_fim" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group row m-1">
                                    <label class="col-4 col-form-label pl-2">Terça Feira</label>
                                    <div class="col-4 @if($errors->has('terca_feira_inicio')) has-danger @endif">
                                        <input type="time" value="{{ old('terca_feira_inicio', $centro_cirurgico->horarioFuncionamento()->get()[0]->terca_feira_inicio) }}" name="terca_feira_inicio" class="form-control">
                                    </div>
                                    <div class="col-4 @if($errors->has('terca_feira_fim')) has-danger @endif">
                                        <input type="time" value="{{ old('terca_feira_fim', $centro_cirurgico->horarioFuncionamento()->get()[0]->terca_feira_fim) }}" name="terca_feira_fim" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group row m-1">
                                    <label class="col-4 col-form-label pl-2">Quarta Feira</label>
                                    <div class="col-4 @if($errors->has('quarta_feira_inicio')) has-danger @endif">
                                        <input type="time" value="{{ old('quarta_feira_inicio', $centro_cirurgico->horarioFuncionamento()->get()[0]->quarta_feira_inicio) }}" name="quarta_feira_inicio" class="form-control">
                                    </div>
                                    <div class="col-4 @if($errors->has('quarta_feira_fim')) has-danger @endif">
                                        <input type="time" value="{{ old('quarta_feira_fim', $centro_cirurgico->horarioFuncionamento()->get()[0]->quarta_feira_fim) }}" name="quarta_feira_fim" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group row m-1">
                                    <label class="col-4 col-form-label pl-2">Quinta Feira</label>
                                    <div class="col-4 @if($errors->has('quinta_feira_inicio')) has-danger @endif">
                                        <input type="time" value="{{ old('quinta_feira_inicio', $centro_cirurgico->horarioFuncionamento()->get()[0]->quinta_feira_inicio) }}" name="quinta_feira_inicio" class="form-control">
                                    </div>
                                    <div class="col-4 @if($errors->has('quinta_feira_fim')) has-danger @endif">
                                        <input type="time" value="{{ old('quinta_feira_fim', $centro_cirurgico->horarioFuncionamento()->get()[0]->quinta_feira_fim) }}" name="quinta_feira_fim" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group row m-1">
                                    <label class="col-4 col-form-label pl-2">Sexta Feira</label>
                                    <div class="col-4 @if($errors->has('sexta_feira_inicio')) has-danger @endif">
                                        <input type="time" value="{{ old('sexta_feira_inicio', $centro_cirurgico->horarioFuncionamento()->get()[0]->sexta_feira_inicio) }}" name="sexta_feira_inicio" class="form-control">
                                    </div>
                                    <div class="col-4 @if($errors->has('sexta_feira_fim')) has-danger @endif">
                                        <input type="time" value="{{ old('sexta_feira_fim', $centro_cirurgico->horarioFuncionamento()->get()[0]->sexta_feira_fim) }}" name="sexta_feira_fim" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group row m-1">
                                    <label class="col-4 col-form-label pl-2">Sábado</label>
                                    <div class="col-4 @if($errors->has('sabado_inicio')) has-danger @endif">
                                        <input type="time" value="{{ old('sabado_inicio', $centro_cirurgico->horarioFuncionamento()->get()[0]->sabado_inicio) }}" name="sabado_inicio" class="form-control">
                                    </div>
                                    <div class="col-4 @if($errors->has('sabado_fim')) has-danger @endif">
                                        <input type="time" value="{{ old('sabado_fim', $centro_cirurgico->horarioFuncionamento()->get()[0]->sabado_fim) }}" name="sabado_fim" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group row m-1">
                                    <label class="col-4 col-form-label pl-2">Domingo</label>
                                    <div class="col-4 @if($errors->has('domingo_inicio')) has-danger @endif">
                                        <input type="time" value="{{ old('domingo_inicio', $centro_cirurgico->horarioFuncionamento()->get()[0]->domingo_inicio) }}" name="domingo_inicio" class="form-control">
                                    </div>
                                    <div class="col-4 @if($errors->has('domingo_fim')) has-danger @endif">
                                        <input type="time" value="{{ old('domingo_fim', $centro_cirurgico->horarioFuncionamento()->get()[0]->domingo_fim) }}" name="domingo_fim" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group text-right pb-2">
                    <a href="{{ route('instituicao.centros.cirurgicos.index') }}">
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
