@extends('admin.layout')

@section('conteudo')

    @component('components/page-title', [
        'titulo' => "Editar Atendimento #{$atendimento->id} {$atendimento->nome}",
        'breadcrumb' => [
            'Atendimentos' => route('admin.atendimentos.index'),
            'Novo',
        ],
    ])
    @endcomponent


    <div class="card">
        <div class="card-body ">
            <form action="{{ route('admin.atendimentos.update', [$atendimento]) }}" method="post">
                @method('put')
                @csrf

                <div class="row">
                    <div class=" col-md-4 form-group @if($errors->has('nome')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">
                            Nome do Atendimento <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="nome" value="{{ old('nome', $atendimento->nome) }}"
                        class="form-control @if($errors->has('nome')) form-control-danger @endif">
                        @if($errors->has('nome'))
                            <small class="form-control-feedback">{{ $errors->first('nome') }}</small>
                        @endif
                    </div>


                    <div class=" col-md-8 form-group @if($errors->has('descricao')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Descrição</span></label>
                        <input type="text" name="descricao" value="{{ old('descricao', $atendimento->descricao) }}"
                        class="form-control @if($errors->has('descricao')) form-control-danger @endif">
                        @if($errors->has('descricao'))
                            <small class="form-control-feedback">{{ $errors->first('descricao') }}</small>
                        @endif
                    </div>
                </div>

            </div>

                <div class="form-group text-right pb-2">
                    <a href="{{ route('admin.atendimentos.index') }}">
                        <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                    </a>
                    <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
                </div>
            </form>
        </div>
    </div>
@endsection
