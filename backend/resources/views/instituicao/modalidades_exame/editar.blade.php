@extends('instituicao.layout')

@section('conteudo')
@component('components/page-title', [
    'titulo' => "Editar modalidade #{$modalidade->id} {$modalidade->descricao}",
    'breadcrumb' => [
        'Modalidades exame' => route('instituicao.modalidades.index'),
        "Editar modalidade {$modalidade->sigla}",
    ],
    ])
    @endcomponent


    <div class="card">
        <div class="card-body">
            <form action="{{ route('instituicao.modalidades.update', [$modalidade]) }}" method="post">
                @method('put')
                @csrf
                <div class="row">
                    <div class="col-md-6 form-group @if($errors->has('sigla')) has-danger @endif">
                        <label class="form-control-label">Sigla: *</label>
                        <input type="text" name="sigla" value="{{ old('sigla', $modalidade->sigla) }}"
                        class="form-control @if($errors->has('sigla')) form-control-danger @endif">
                        @if($errors->has('sigla'))
                            <div class="form-control-feedback">{{ $errors->first('sigla') }}</div>
                        @endif
                    </div>

                    <div class="col-md-6 form-group @if($errors->has('descricao')) has-danger @endif">
                        <label class="form-control-label">Descrição: *</label>
                        <input type="text" name="descricao" value="{{ old('descricao', $modalidade->descricao) }}"
                        class="form-control @if($errors->has('descricao')) form-control-danger @endif">
                        @if($errors->has('descricao'))
                            <div class="form-control-feedback">{{ $errors->first('descricao') }}</div>
                        @endif
                    </div>
                </div>
            </div>

        </div>



            <div class="form-group text-right">
                <a href="{{ route('procedimentos.index') }}">
                    <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                </a>
                <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
            </div>

        </form>
    </div>
</div>
@endsection
