@extends('instituicao.layout')

@section('conteudo')

    @component('components/page-title', [
        'titulo' => "Editar Caixa cirúrgico #{$caixa_cirurgico->id} {$caixa_cirurgico->descricao_resumida}",
        'breadcrumb' => [
            'Caixas cirúrgicos' => route('instituicao.caixasCirurgicos.index'),
            'Editar',
        ],
    ])
    @endcomponent


    <div class="card">
        <div class="card-body ">
            <form action="{{ route('instituicao.caixasCirurgicos.update', [$caixa_cirurgico]) }}" method="post">
                @csrf
                @method('put')

                <div class="row">
                    <div class="col-md-6 form-group @if($errors->has('descricao')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">
                            Descrição<span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control @if($errors->has('descricao')) form-control-danger @endif" name="descricao"
                            value="{{ old('descricao', $caixa_cirurgico->descricao) }}">
                        @if($errors->has('descricao'))
                            <small class="form-control-feedback">{{ $errors->first('descricao') }}</small>
                        @endif
                    </div>

                    <div class="col-md-6 form-group @if($errors->has('descricao_resumida')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">
                            Descrição resumida<span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control @if($errors->has('descricao_resumida')) form-control-danger @endif" name="descricao_resumida"
                            value="{{ old('descricao_resumida', $caixa_cirurgico->descricao_resumida) }}">
                        @if($errors->has('descricao_resumida'))
                            <small class="form-control-feedback">{{ $errors->first('descricao_resumida') }}</small>
                        @endif
                    </div>

                    <div class="col-md-3 form-group @if($errors->has('qtd')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">
                            Quantidade<span class="text-danger">*</span>
                        </label>
                        <input type="number" class="form-control @if($errors->has('qtd')) form-control-danger @endif" name="qtd"
                            value="{{ old('qtd', $caixa_cirurgico->qtd) }}">
                        @if($errors->has('qtd'))
                            <small class="form-control-feedback">{{ $errors->first('qtd') }}</small>
                        @endif
                    </div>

                    <div class="col-md-3 form-group @if($errors->has('tempo_esterelizar')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">
                            Tempo esterelizar (min) <span class="text-danger">*</span>
                        </label>
                        <input type="number" class="form-control @if($errors->has('tempo_esterelizar')) form-control-danger @endif" name="tempo_esterelizar"
                            value="{{ old('tempo_esterelizar', $caixa_cirurgico->tempo_esterelizar) }}">
                        @if($errors->has('tempo_esterelizar'))
                            <small class="form-control-feedback">{{ $errors->first('tempo_esterelizar') }}</small>
                        @endif
                    </div>

                    <div class="form-group col-md-3" style="margin-top: 25px;">
                        <input type="checkbox" id="ativo" name="ativo" class="filled-in form-control" @if (old('ativo', $caixa_cirurgico->ativo) == 1)
                        checked
                    @endif/>
                        <label class="form-control-label" for="ativo">Ativo<label>
                    </div>

            </div>

                <div class="form-group text-right pb-2">
                    <a href="{{ route('instituicao.caixasCirurgicos.index') }}">
                        <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                    </a>
                    <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
                </div>
            </form>
        </div>
    </div>
@endsection
