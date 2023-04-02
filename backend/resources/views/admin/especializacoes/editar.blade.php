
@extends('admin.layout')

@section('conteudo')
    @component('components/page-title', [
        'titulo' => "Editar especialização #{$especializacao->id} {$especializacao->descricao}",
        'breadcrumb' => [
            'Especializações' => route('especializacoes.index'),
            'Editar',
        ],
    ])
    @endcomponent


    <div class="card">
        <div class="card-body">
            <form action="{{ route('especializacoes.update', [$especializacao]) }}" method="post">
                @method('put')
                @csrf

                <div class="row p-0 m-0">
                    <div class="col-sm-8 p-1 m-0">
                        <div class="form-group @if($errors->has('descricao')) has-danger @endif">
                            <label class="form-control-label p-0 m-0">Descrição</label>
                            <input type="text" name="descricao" value="{{ old('descricao', $especializacao->descricao) }}"
                                class="form-control @if($errors->has('descricao')) form-control-danger @endif">
                            @if($errors->has('descricao'))
                                <small class="form-control-feedback">{{ $errors->first('descricao') }}</small>
                            @endif
                        </div>
                    </div>
                </div>


                <div class="form-group text-right">
                    <a href="{{ route('especializacoes.index') }}">
                        <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                    </a>
                    <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
                </div>
            </form>
        </div>
    </div>
@endsection
