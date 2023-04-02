@extends('instituicao.layout')

@section('conteudo')

    @component('components/page-title', [
        'titulo' => 'Cadastrar Sangue e Derivados',
        'breadcrumb' => [
            'Sangue e Derivados' => route('instituicao.sanguesDerivados.index'),
            'Novo',
        ],
    ])
    @endcomponent


    <div class="card">
        <div class="card-body ">
            <form action="{{ route('instituicao.sanguesDerivados.store') }}" method="post">
                @csrf

                <div class="row">

                    <div class=" col-md-12 form-group @if($errors->has('descricao')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Descrição *</label>
                        <input type="text" name="descricao" value="{{ old('descricao') }}"
                        class="form-control @if($errors->has('descricao')) form-control-danger @endif">
                        @if($errors->has('descricao'))
                            <small class="form-control-feedback">{{ $errors->first('descricao') }}</small>
                        @endif
                    </div>
                    <div class=" col-md-2 form-group @if($errors->has('qtd')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Quantidade *</label>
                        <input type="number" name="qtd" value="{{ old('qtd') }}"
                        class="form-control @if($errors->has('qtd')) form-control-danger @endif">
                        @if($errors->has('qtd'))
                            <small class="form-control-feedback">{{ $errors->first('qtd') }}</small>
                        @endif
                    </div>
                </div>

            </div>

                <div class="form-group text-right pb-2">
                    <a href="{{ route('instituicao.sanguesDerivados.index') }}">
                        <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                    </a>
                    <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
                </div>
            </form>
        </div>
    </div>
@endsection
