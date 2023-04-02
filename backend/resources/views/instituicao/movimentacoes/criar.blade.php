@extends('instituicao.layout')

@section('conteudo')
    @component('components/page-title', [
        'titulo' => 'Cadastrar Movimentação',
        'breadcrumb' => [
            'Movimentações' => route('instituicao.movimentacoes.index'),
            'Novo',
        ],
    ])
    @endcomponent


    <div class="card">
        <div class="card-body">
            <form action="{{ route('instituicao.movimentacoes.store') }}" method="post">
                @csrf

                <div class="row">
                    <div class="form-group col-md-3 @if($errors->has('tipo_movimentacao')) has-danger @endif">
                        <label class="form-control-label">Tipo movimentação *</span></label>
                        <select class="form-control select2 @if ($errors->has('tipo_movimentacao')) form-control-danger @endif" name="tipo_movimentacao" id="tipo_movimentacao" style="width: 100%">
                            @foreach ($tipo_movimentacao as $item)
                                <option value="{{$item}}"  @if (old('tipo_movimentacao') == $item)
                                    selected="selected"
                                @endif >{{App\Movimentacao::natureza_para_texto($item)}}</option>
                            @endforeach
                        </select>
                        @if($errors->has('tipo_movimentacao'))
                            <div class="form-control-feedback">{{ $errors->first('tipo_movimentacao') }}</div>
                        @endif
                    </div>
                    <div class="form-group col-md-3 @if($errors->has('data')) has-danger @endif">
                        <label class="form-control-label">Data *</span></label>
                        <input type="date" name="data" value="{{ old('data') }}"
                        class="form-control @if($errors->has('data')) form-control-danger @endif">
                        @if($errors->has('data'))
                            <div class="form-control-feedback">{{ $errors->first('data') }}</div>
                        @endif
                    </div>

                    <div class="form-group col-md-3 @if($errors->has('conta_id_origem')) has-danger @endif">
                        <label class="form-control-label">Contas origem *</span></label>
                        <select class="form-control select2 @if ($errors->has('conta_id_origem')) form-control-danger @endif" name="conta_id_origem" id="conta_id_origem" style="width: 100%">
                            @foreach ($contas as $conta)
                                <option value="{{$conta->id}}"  @if (old('conta_id_origem') == $conta->id)
                                    selected="selected"
                                @endif >{{$conta->descricao}}</option>
                            @endforeach
                        </select>
                        @if($errors->has('conta_id_origem'))
                            <div class="form-control-feedback">{{ $errors->first('conta_id_origem') }}</div>
                        @endif
                    </div>

                    <div class="form-group col-md-3 @if($errors->has('conta_id_destino')) has-danger @endif">
                        <label class="form-control-label">Contas destino *</span></label>
                        <select class="form-control select2 @if ($errors->has('conta_id_destino')) form-control-danger @endif" name="conta_id_destino" id="conta_id_destino" style="width: 100%">
                            @foreach ($contas as $conta)
                                <option value="{{$conta->id}}"  @if (old('conta_id_destino') == $conta->id)
                                    selected="selected"
                                @endif >{{$conta->descricao}}</option>
                            @endforeach
                        </select>
                        @if($errors->has('conta_id_destino'))
                            <div class="form-control-feedback">{{ $errors->first('conta_id_destino') }}</div>
                        @endif
                    </div>

                    <div class="form-group col-md-3 @if($errors->has('valor')) has-danger @endif">
                        <label class="form-control-label">Valor *</span></label>
                        <input type="text" alt="decimal" name="valor" value="{{ old('valor') }}"
                        class="form-control @if($errors->has('valor')) form-control-danger @endif">
                        @if($errors->has('valor'))
                            <div class="form-control-feedback">{{ $errors->first('valor') }}</div>
                        @endif
                    </div>
                    <div class="form-group col-md-12">
                        <label class="form-control-label">Observação</span></label>
                        <textarea class="form-control" name="obs" id="obs" cols="5" rows="5">{{old('obs')}}</textarea>
                    </div>
                </div>

                <div class="form-group text-right">
                    <a href="{{ route('instituicao.movimentacoes.index') }}">
                    <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                    </a>
                    <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
                </div>
            </form>
        </div>
    </div>
@endsection
