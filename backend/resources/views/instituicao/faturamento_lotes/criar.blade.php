@extends('instituicao.layout')

@section('conteudo')

    @component('components/page-title', [
        'titulo' => 'Cadastrar Lote / Protocolo',
        'breadcrumb' => [
            'Faturamento' => route('instituicao.faturamento.lotes.index'),
            'Novo lote',
        ],
    ])
    @endcomponent

    <div class="card">
        <div class="card-body ">
            <form action="{{ route('instituicao.faturamento.lotes.store') }}" method="post">
                @csrf
               <div class="row">
                    <div class=" col-md-6 form-group @if($errors->has('descricao')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Descrição</label>
                        <input type="text" name="descricao" value="{{ old('descricao') }}"
                        class="form-control @if($errors->has('descricao')) form-control-danger @endif">
                        @if($errors->has('descricao'))
                            <small class="form-control-feedback">{{ $errors->first('descricao') }}</small>
                        @endif
                    </div>

                    <div class=" col-md-3 form-group @if($errors->has('tipo')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Tipo*</label>
                        <select class="form-control @if($errors->has('tipo')) form-control-danger @endif" name="tipo" id="tipo" required>
                            <option value="1" @if(old('tipo') == 1) selected="selected" @endif>Manual</option>
                            <option value="2" @if(old('tipo') == 2) selected="selected" @endif>Sancoop</option>
                        </select>
                        @if($errors->has('tipo'))
                            <small class="form-control-feedback">{{ $errors->first('tipo') }}</small>
                        @endif
                    </div>

                    <div class="form-group col-md-3">
                        <label for="centro_cirurgico_editar" class="form-control-label p-0 m-0">Prestador:</label>
                        <select name="prestadores_id" id="prestadores_id" class="form-control select2 editar" style="width: 100%">
                            <option value="">Selecione um prestador</option>
                            @foreach ($prestadores as $item)
                                <option value="{{$item->id}}">{{$item->nome}}</option>
                            @endforeach
                        </select>
                    </div>

                </div>




                <div class="form-group text-right pb-2">
                    <a href="{{ route('instituicao.faturamento.lotes.index') }}">
                        <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                    </a>
                    <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
                </div>
            </form>
        </div>
    </div>
@endsection
