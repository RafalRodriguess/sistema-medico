@extends('instituicao.layout')

@section('conteudo')

    @component('components/page-title', [
        'titulo' => 'Cadastrar Tipo de anestesia',
        'breadcrumb' => [
            'Tipo de anestesisas' => route('instituicao.tiposAnestesia.index'),
            'Novo',
        ],
    ])
    @endcomponent


    <div class="card">
        <div class="card-body ">
            <form action="{{ route('instituicao.tiposAnestesia.store') }}" method="post">
                @csrf
               <div class="row">
                    <div class=" col-md-8 form-group @if($errors->has('descricao')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Descrição</label>
                        <input type="text" name="descricao" value="{{ old('descricao') }}"
                        class="form-control @if($errors->has('descricao')) form-control-danger @endif">
                        @if($errors->has('descricao'))
                            <small class="form-control-feedback">{{ $errors->first('descricao') }}</small>
                        @endif
                    </div>

                    <div class=" col-md-4 form-group @if($errors->has('cobranca_aih')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Cobrança AIH</label>
                        <select class="form-control @if($errors->has('cobranca_aih')) form-control-danger @endif" name="cobranca_aih" id="cobranca_aih" required>
                            <option value="0">Não</option>
                            <option value="1">Sim</option>
                        </select>
                        @if($errors->has('cobranca_aih'))
                            <small class="form-control-feedback">{{ $errors->first('cobranca_aih') }}</small>
                        @endif
                    </div>
                </div>

            </div>

                <div class="form-group text-right pb-2">
                    <a href="{{ route('instituicao.tiposAnestesia.index') }}">
                        <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                    </a>
                    <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
                </div>
            </form>
        </div>
    </div>
@endsection
