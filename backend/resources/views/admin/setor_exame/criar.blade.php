@extends('admin.layout')

@section('conteudo')
    @component('components/page-title', [
        'titulo' => 'Cadastrar Setores',
        'breadcrumb' => [
            'Setores' => route('setorExame.index'),
            'Novo',
        ],
    ])
    @endcomponent


    <div class="card">
        <div class="card-body">
            <form action="{{ route('setorExame.store') }}" method="post">
                @csrf

                <div class="form-group @if($errors->has('descricao')) has-danger @endif">
                    <label class="form-control-label">Descricao *</span></label>
                    <input type="text" name="descricao" value="{{ old('descricao') }}"
                        class="form-control @if($errors->has('descricao')) form-control-danger @endif">
                    @if($errors->has('descricao'))
                        <div class="form-control-feedback">{{ $errors->first('descricao') }}</div>
                    @endif
                </div>
                <div class="form-group @if($errors->has('instituicao_id')) has-danger @endif">
                    <label class="form-control-label">Instituição *</span></label>
                    <select class="form-control select2 @if($errors->has('instituicao_id')) form-control-danger @endif" name="instituicao_id" id="instituicao_id" style="width: 100%">
                        @foreach ($instituicoes as $item)
                            <option value="{{$item->id}}" @if (old('instituicao_id') == $item->id)
                                selected
                            @endif>{{$item->nome}}</option>
                        @endforeach
                    </select>
                    @if($errors->has('instituicao_id'))
                        <div class="form-control-feedback">{{ $errors->first('instituicao_id') }}</div>
                    @endif
                </div>
                <div class="form-group @if($errors->has('tipo')) has-danger @endif">
                    <label class="form-control-label">Instituição *</span></label>
                    <select class="form-control @if($errors->has('tipo')) form-control-danger @endif" name="tipo" id="tipo">
                        <option value='anatomia' @if (old('tipo') == 'anatomia')
                                selected
                            @endif>anatomia (padrão)</option>
                        <option value='banco de sangue' @if (old('tipo') == 'banco de sangue')
                                selected
                            @endif>banco de sangue</option>
                        <option value='diagnóstico por imagem' @if (old('tipo') == 'diagnóstico por imagem')
                                selected
                            @endif>diagnóstico por imagem</option>
                        <option value='laboratório SADT' @if (old('tipo') == 'laboratório SADT')
                                selected
                            @endif>laboratório SADT</option>
                    </select>
                    @if($errors->has('tipo'))
                        <div class="form-control-feedback">{{ $errors->first('tipo') }}</div>
                    @endif
                </div>

                <div class="form-groupn text-right">
                    <a href="{{ route('setorExame.index') }}">
                    <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                    </a>
                    <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
                </div>
            </form>
        </div>
    </div>
@endsection
