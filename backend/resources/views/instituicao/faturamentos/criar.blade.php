@extends('instituicao.layout')

    @section('conteudo')
    @component('components/page-title', [
        'titulo' => 'Cadastrar Faturamento',
        'breadcrumb' => [
            'Faturamento' => route('instituicao.faturamento.index'),
            'Novo',
        ],
        ])
    @endcomponent


    <div class="card">
        <div class="card-body ">
            <form action="{{ route('instituicao.faturamento.store') }}" method="post">
                @csrf

                <div class="row">
                    <div class=" col-md-12 form-group @if($errors->has('descricao')) has-danger @endif">
                        <label class="form-control-label">Descrição: *</span></label>
                        <input type="text" name="descricao" value="{{ old('descricao') }}"
                        class="form-control @if($errors->has('descricao')) form-control-danger @endif">
                        @if($errors->has('descricao'))
                        <div class="form-control-feedback">{{ $errors->first('descricao') }}</div>
                        @endif
                    </div>
                    <div class=" col-md-6 form-group @if($errors->has('tipo')) has-danger @endif">
                        <label class="form-control-label">Tipo: *</span></label>
                        <select class="form-control select2 @if($errors->has('tipo')) form-control-danger @endif" name="tipo" id="tipo" style="width: 100%">
                            @foreach ($tipo as $item)
                                <option value="{{$item}}" @if (old("tipo") == $item)
                                    selected
                                @endif>{{App\Faturamento::tipoValorTexto($item)}}</option>
                            @endforeach
                        </select>
                        @if($errors->has('tipo'))
                        <div class="form-control-feedback">{{ $errors->first('tipo') }}</div>
                        @endif
                    </div>
                    <div class=" col-md-6 form-group @if($errors->has('tipo_tiss')) has-danger @endif">
                        <label class="form-control-label">Tipo TISS: *</span></label>
                        <select class="form-control select2 @if($errors->has('tipo_tiss')) form-control-danger @endif" name="tipo_tiss" id="tipo_tiss" style="width: 100%">
                            @foreach ($tipo_tiss as $item)
                                <option value="{{$item}}" @if (old("tipo_tiss") == $item)
                                    selected
                                @endif>{{App\Faturamento::tipoTISSValorTexto($item)}}</option>
                            @endforeach
                        </select>
                        @if($errors->has('tipo_tiss'))
                        <div class="form-control-feedback">{{ $errors->first('tipo_tiss') }}</div>
                        @endif
                    </div>
                </div>

                <div class="form-group text-right">
                    <hr>
                    <a href="{{ route('instituicao.faturamento.index') }}">
                        <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                    </a>
                    <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
                </div>
            </form>
        </div>
    </div>
@endsection
