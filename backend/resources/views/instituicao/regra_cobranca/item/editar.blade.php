@extends('instituicao.layout')

    @section('conteudo')
    @component('components/page-title', [
        'titulo' => "Editar Item regra de cobrança",
        'breadcrumb' => [
            'Regra de Cobrança' => route('instituicao.regrasCobranca.index'),
            "{$regra->descricao}",
            'Itens' => route('instituicao.regrasCobrancaItens.index', [$regra]),
            'Editar item',
            ],
        ])
    @endcomponent

    <div class="card">
        <div class="card-body ">
            <form action="{{ route('instituicao.regrasCobrancaItens.update', [$regra, $item]) }}" method="post">
                @method('put')
                @csrf
                <div class="row">
                    <div class="form-group col-md-3 @if($errors->has("grupo_procedimento_id")) has-danger @endif">
                        <label class="form-control-label">Gurpo procedimento *</span></label>
                        <select name="grupo_procedimento_id" class="form-control select2 @if($errors->has("grupo_procedimento_id")) form-control-danger @endif" name="grupo_procedimento_id">
                            @foreach ($grupos as $grupo)
                                <option value="{{$grupo->id}}" @if (old("grupo_procedimento_id", $item->grupo_procedimento_id) == $grupo->id)
                                    selected
                                @endif>{{$grupo->nome}}</option>
                            @endforeach
                        </select>
                        @if($errors->has("grupo_procedimento_id"))
                            <div class="form-control-feedback">{{ $errors->first("grupo_procedimento_id") }}</div>
                        @endif
                    </div>
                    <div class="form-group col-md-3 @if($errors->has("faturamento_id")) has-danger @endif">
                        <label class="form-control-label">Faturamento *</span></label>
                        <select name="faturamento_id" class="form-control select2 @if($errors->has("faturamento_id")) form-control-danger @endif" name="faturamento_id">
                            @foreach ($faturamentos as $faturamento)
                                <option value="{{$faturamento->id}}" @if (old("faturamento_id", $item->faturamento_id) == $faturamento->id)
                                    selected
                                @endif>{{$faturamento->descricao}}</option>
                            @endforeach
                        </select>
                        @if($errors->has("faturamento_id"))
                            <div class="form-control-feedback">{{ $errors->first("faturamento_id") }}</div>
                        @endif
                    </div>
                    <div class="form-group col-md-2 @if($errors->has("pago")) has-danger @endif">
                        <label class="form-control-label">Pago % *</span></label>
                        <input type="text" alt="porcentagem" class="form-control @if($errors->has("pago")) form-control-danger @endif" name="pago" id="pago" value="{{old("pago", $item->pago)}}">
                        @if($errors->has("pago"))
                            <div class="form-control-feedback">{{ $errors->first("pago") }}</div>
                        @endif
                    </div>
                    <div class="form-group col-md-3 @if($errors->has("base")) has-danger @endif">
                        <label class="form-control-label">Base *</span></label>
                        <select name="base" class="form-control select2 @if($errors->has("base")) form-control-danger @endif" name="base">
                            @foreach ($bases as $base)
                                <option value="{{$base}}" @if (old("base", $item->base) == $base)
                                    selected
                                @endif>{{App\RegraCobrancaItem::baseTexto($base)}}</option>
                            @endforeach
                        </select>
                        @if($errors->has("base"))
                            <div class="form-control-feedback">{{ $errors->first("base") }}</div>
                        @endif
                    </div>
                </div>
                <div class="form-group text-right">
                    <a href="{{ route('instituicao.regrasCobrancaItens.index', [$regra]) }}">
                    <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                    </a>
                    <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
                </div>
            </form>
        </div>
    </div>
@endsection
