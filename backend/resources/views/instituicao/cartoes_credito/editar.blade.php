@extends('instituicao.layout')

@section('conteudo')

    @component('components/page-title', [
        'titulo' => "Editar Setor #{$cartaoCredito->id} {$cartaoCredito->nome}Cadastrar Setores",
        'breadcrumb' => [
            'Tipo de partos' => route('instituicao.cartoesCredito.index'),
            'Atualização',
        ],
    ])
    @endcomponent


    <div class="card">
        <div class="card-body ">
            <form action="{{ route('instituicao.cartoesCredito.update', [$cartaoCredito]) }}" method="post">
                @method('put')
                @csrf

                <div class="row">
                    <div class=" col-md-8 form-group @if($errors->has('descricao')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Descrição</label>
                        <input type="text" name="descricao" value="{{ old('descricao', $cartaoCredito->descricao) }}"
                        class="form-control @if($errors->has('descricao')) form-control-danger @endif">
                        @if($errors->has('descricao'))
                            <small class="form-control-feedback">{{ $errors->first('descricao') }}</small>
                        @endif
                    </div>

                    <div class=" col-md-4 form-group @if($errors->has('bandeira')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Bandeira</label>
                        <select class="form-control @if($errors->has('bandeira')) form-control-danger @endif" name="bandeira" id="bandeira" required>
                            @foreach ($opcoes_bandeira as $id => $opcao)
                                <option value="{{ $id }}" @if(old('bandeira', $cartaoCredito->bandeira) == $id) selected="selected" @endif>{{ $opcao }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('bandeira'))
                            <small class="form-control-feedback">{{ $errors->first('bandeira') }}</small>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 form-group @if($errors->has('limite')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">
                            Limite<span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control @if($errors->has('limite')) form-control-danger @endif" name="limite" id="limite" value="{{ old('limite', $cartaoCredito->limite) }}">
                        @if($errors->has('limite'))
                            <small class="form-control-feedback">{{ $errors->first('limite') }}</small>
                        @endif
                    </div>

                    <div class="col-md-4 form-group @if($errors->has('fechamento')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">
                            Dia de Fechamento<span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control @if($errors->has('fechamento')) form-control-danger @endif" name="fechamento" id="fechamento" value="{{ old('fechamento', $cartaoCredito->fechamento) }}">
                        @if($errors->has('fechamento'))
                            <small class="form-control-feedback">{{ $errors->first('fechamento') }}</small>
                        @endif
                    </div>

                    <div class="col-md-4 form-group @if($errors->has('vencimento')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">
                            Dia de Vencimento<span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control @if($errors->has('vencimento')) form-control-danger @endif" name="vencimento" id="vencimento" value="{{ old('vencimento', $cartaoCredito->vencimento) }}">
                        @if($errors->has('vencimento'))
                            <small class="form-control-feedback">{{ $errors->first('vencimento') }}</small>
                        @endif
                    </div>
                </div>

                <div class="form-group text-right pb-2">
                    <a href="{{ route('instituicao.cartoesCredito.index') }}">
                        <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                    </a>
                    <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
                </div>
            </form>
        </div>
    </div>
@endsection
