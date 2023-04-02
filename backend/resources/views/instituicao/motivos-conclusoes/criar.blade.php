@extends('instituicao.layout')

@section('conteudo')

    @component('components/page-title', [
        'titulo' => 'Cadastrar Motivo de Conclusão',
        'breadcrumb' => [
            'Motivo de Conclusão' => route('instituicao.motivoConclusao.index'),
            'Novo',
        ],
    ])
    @endcomponent


    <div class="card">
        <div class="card-body ">
            <form action="{{ route('instituicao.motivoConclusao.store') }}" method="post">
                @csrf
               <div class="row">
                    <div class=" col-md-12 form-group @if($errors->has('descricao')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Descrição<span class="text-danger">*</span></label>
                        <input type="text" name="descricao" required value="{{ old('descricao') }}"
                        class="form-control @if($errors->has('descricao')) form-control-danger @endif">
                        @if($errors->has('descricao'))
                            <small class="form-control-feedback">{{ $errors->first('descricao') }}</small>
                        @endif
                    </div>

                </div>

            </div>

                <div class="form-group text-right pb-2">
                    <a href="{{ route('instituicao.motivoConclusao.index') }}">
                        <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                    </a>
                    <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
                </div>
            </form>
        </div>
    </div>
@endsection
