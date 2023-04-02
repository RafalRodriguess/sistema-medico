@extends('instituicao.layout')

@section('conteudo')

    @component('components/page-title', [
        'titulo' => 'Cadastrar Atendimentos',
        'breadcrumb' => [
            'Atendimentos' => route('instituicao.atendimentos.index'),
            'Novo',
        ],
    ])
    @endcomponent


    <div class="card">
        <div class="card-body ">
            <form action="{{ route('instituicao.atendimentos.store') }}" method="post">
                @csrf

                <div class="row">
                    <div class="col-md-6 form-group @if($errors->has('nome')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">
                            Descrição <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control @if($errors->has('nome')) form-control-danger @endif"
                            name="nome" value="{{ old('nome') }}">
                        @if($errors->has('nome'))
                            <small class="form-control-feedback">{{ $errors->first('nome') }}</small>
                        @endif
                    </div>
                </div>

            </div>

                <div class="form-group text-right pb-2">
                    <a href="{{ route('instituicao.atendimentos.index') }}">
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
        $(document).ready(function(){
            $("#nome_atendimento").select2({ tags: true });
        })
    </script>
@endpush
