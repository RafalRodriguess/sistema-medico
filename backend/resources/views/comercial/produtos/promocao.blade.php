
@extends('comercial.layout')

@section('conteudo')
    @component('components/page-title', [
        'titulo' => "Editar produto #{$produto->id} {$produto->nome}",
        'breadcrumb' => [
            'Produtos' => route('comercial.produtos.index'),
            'Editar',
        ],
    ])
    @endcomponent


    <div class="card">
        <div class="card-body">
            <form action="{{ route('comercial.produtos.promocao', [$produto]) }}" method="post">
                @method('put')
                @csrf

                <div class="form-group">
                    <input type="checkbox" id="promocao" class="filled-in" name="promocao" @if ($produto->promocao == 1)
                        checked
                    @endif/>
                    <label for="promocao">Promoção</label>
                </div>

                <div class="form-group @if($errors->has('preco_promocao')) has-danger @endif">
                    <label class="form-control-label">Preço promoção *</span></label>
                    <input type="text" name="preco_promocao" alt="money" value="{{ old('preco_promocao', $produto->preco_promocao ?: $produto->preco) }}"
                        class="form-control @if($errors->has('preco_promocao')) form-control-danger @endif">
                    @if($errors->has('preco_promocao'))
                        <div class="form-control-feedback">{{ $errors->first('preco_promocao') }}</div>
                    @endif

                    <span class="text-right d-block">Preço original do produto:
                        R$ {{ number_format($produto->preco, 2, ',', '.') }}
                    </span>
                </div>

                <div class="form-group @if($errors->has('promocao_inicio')) has-danger @endif">
                    <label class="form-control-label">Data inicial *</span></label>
                    <input type="date" name="promocao_inicio"
                           value="{{ old('promocao_inicio',
                                with($produto->promocao_inicio ?: \Carbon\Carbon::now())->toDateString()
                            ) }}"
                        class="form-control @if($errors->has('promocao_inicio')) form-control-danger @endif">
                    @if($errors->has('promocao_inicio'))
                        <div class="form-control-feedback">{{ $errors->first('promocao_inicio') }}</div>
                    @endif
                </div>

                <div class="form-group @if($errors->has('promocao_final')) has-danger @endif">
                    <label class="form-control-label">Data final *</span></label>
                    <input type="date" name="promocao_final"
                    value="{{ old('promocao_final',
                         with($produto->promocao_final ?: \Carbon\Carbon::now()->addWeek())->toDateString()
                     ) }}"
                 class="form-control @if($errors->has('promocao_final')) form-control-danger @endif">
                    @if($errors->has('promocao_final'))
                        <div class="form-control-feedback">{{ $errors->first('promocao_final') }}</div>
                    @endif
                </div>



                <div class="form-group text-right">
                        <a href="{{ route('comercial.produtos.index') }}">
                                <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                        </a>
                        <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
                </div>
            </form>
        </div>
    </div>
@endsection
