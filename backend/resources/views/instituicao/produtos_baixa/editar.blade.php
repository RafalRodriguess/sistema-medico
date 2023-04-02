@extends('instituicao.layout')

@section('conteudo')
    @component('components/page-title',
        [
            'titulo' => "Editar produto da baixa de estoque #{$estoqueBaixa->id}",
            'breadcrumb' => [
                'Produtos da baixa de estoque' => route('instituicao.produtos_baixa.index', [$estoqueBaixa]),
                'Editar produto da baixa de estoque',
            ],
        ])
    @endcomponent

    <div class="card">
        <form action="{{ route('instituicao.produtos_baixa.update', [$estoqueBaixa, $produtoBaixa]) }}" method="POST">
            <div class="card-body">
                @method('post')
                @csrf
                <div class="row">
                    <div class="col-md-6 form-group @error('produto_id') has-danger @enderror">
                        <label class="form-control-label">Produtos *</label>
                        <select name="produto_id" class="form-control @error('produto_id') form-control-danger @enderror">
                            <option value="">Selecione um produto</option>
                            @foreach ($produtos as $key => $item)
                                <option value="{{ $item->id }}"
                                    {{ $item->id == old('id',$produtoBaixa->produto_id) ? "selected='selected'" : '' }}>
                                    {{ $item->descricao }}
                                </option>
                            @endforeach
                        </select>
                        @error('produto_id')
                            <div class="form-control-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class=" col-md-3 col-sm-6 form-group @if ($errors->has('quantidade')) has-danger @endif">
                        <label class="form-control-label">Quantidade</label>
                        <input type="number" min="0" name="quantidade" value="{{ old('quantidade', $produtoBaixa->quantidade) }}"
                            class="form-control @if ($errors->has('quantidade')) form-control-danger @endif">
                        @if ($errors->has('quantidade'))
                            <small class="form-control-feedback">{{ $errors->first('quantidade') }}</small>
                        @endif
                    </div>

                    <div class="col-md-3 col-sm-6 form-group @if ($errors->has('lote')) has-danger @endif">
                        <label class="form-control-label">Lote</label>
                        <input type="text" name="lote" value="{{ old('lote', $produtoBaixa->lote) }}"
                            class="form-control @if ($errors->has('lote')) form-control-danger @endif">
                        @if ($errors->has('lote'))
                            <small class="form-control-feedback">{{ $errors->first('lote') }}</small>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card-footer text-right">
                <a href="{{ route('instituicao.produtos_baixa.index', [$estoqueBaixa]) }}">
                    <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i
                            class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                </a>
                <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i>
                    Salvar</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {

        });
    </script>
@endpush
