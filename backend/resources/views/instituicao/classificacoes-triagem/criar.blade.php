@extends('instituicao.layout')

@section('conteudo')

    @component('components/page-title', [
        'titulo' => 'Cadastrar Classificação',
        'breadcrumb' => [
        'Classificações de triagem' => route('instituicao.triagem.classificacoes.index'),
        'Nova',
        ],
        ])
    @endcomponent


    <div class="card">
        <div class="card-body ">
            <form action="{{ route('instituicao.triagem.classificacoes.store') }}" method="post">
                @csrf

                <div class="row">
                    <div class=" col-md-6 form-group @if ($errors->has('descricao')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Descrição</label>
                        <input type="text" name="descricao" value="{{ old('descricao') }}"
                            class="form-control @if ($errors->has('descricao')) form-control-danger @endif">
                        @if ($errors->has('descricao'))
                            <small class="form-control-feedback">{{ $errors->first('descricao') }}</small>
                        @endif
                    </div>
                    <div class=" col-md-3 form-group @if ($errors->has('prioridade')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Prioridade</label>
                        <input type="number" min="0" max="100" step="1" name="prioridade" id="prioridade" value="{{ old('prioridade', 0) }}"
                            class="form-control @if ($errors->has('prioridade')) form-control-danger @endif">
                        <small>Um valor entre 0 e 100, onde <b>0 representa menor prioridade</b> e <b>100 prioridade máxima</b>.</small>
                        @if ($errors->has('prioridade'))
                            <small class="form-control-feedback">{{ $errors->first('prioridade') }}</small>
                        @endif
                    </div>
                    <div class=" col-md-3 form-group @if ($errors->has('cor')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Cor da classificação</label>
                        <div class="col-md-2 col-4 p-0">
                            <input type="color" name="cor" value="{{ old('cor') }}"
                                class="form-control @if ($errors->has('cor')) form-control-danger @endif">
                        </div>
                        @if ($errors->has('cor'))
                            <small class="form-control-feedback">{{ $errors->first('cor') }}</small>
                        @endif
                    </div>
                </div>

                <div class="form-group text-right pb-2">
                    <a href="{{ route('instituicao.triagem.classificacoes.index') }}">
                        <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i
                                class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                    </a>
                    <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i>
                        Salvar</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(() => {
            $('#prioridade').on('change', (e) => {
                const element = $(e.target);
                const value = parseInt(element.val());
                const min = parseInt(element.attr('min'));
                const max = parseInt(element.attr('max'));
                if (value < min) {
                    element.val(min);
                } else if (value > max) {
                    element.val(max);
                }
            });
        });
    </script>
@endpush