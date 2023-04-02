@extends('instituicao.layout')

@section('conteudo')

    @component('components/page-title', [
        'titulo' => 'Cadastrar Estoques',
        'breadcrumb' => [
            'Estoque' => route('instituicao.estoques.index'),
            'Novo',
        ],
    ])
    @endcomponent


    <div class="card">
        <div class="card-body ">
            <form action="{{ route('instituicao.estoques.store') }}" method="post">
                @csrf
               <div class="row">
                    <div class=" col-md-12 form-group @if($errors->has('descricao')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Descrição<span class="text-danger">*</span></label>
                        <input type="text" name="descricao" required value="{{ old('descricao') }}"
                        class="form-control @if($errors->has('descricao')) form-control-danger @endif">
                        @if($errors->has('descricao'))
                            <small class="form-control-feedback">{{ $errors->first('descricao') }}</small>
                        @endif
                    </div>

                    <div class=" col-md-5 form-group @if($errors->has('tipo')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Tipo<span class="text-danger">*</span></label>
                        <select class="form-control @if($errors->has('tipo')) form-control-danger @endif" name="tipo" id="tipo" required>
                            <option value="">Selecione</option>
                            <option value="estoque">Estoque</option>
                            <option value="sub_estoque">Sub Estoque</option>
                        </select>
                        @if($errors->has('tipo'))
                            <small class="form-control-feedback">{{ $errors->first('tipo') }}</small>
                        @endif
                    </div>

                    <div class=" col-md-7 form-group @if($errors->has('centro_custo_id')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Centro de Custo<span class="text-danger">*</span></label>
                        <select class="form-control select2 @if($errors->has('centro_custo_id')) form-control-danger @endif" name="centro_custo_id" id="centro_custo_id" required style="width: 100%">
                            <option value="">Selecione</option>
                            @foreach($centro_custos as $centro_custo)
                                <option value="{{ $centro_custo->id }}">{{ $centro_custo->descricao }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('centro_custo_id'))
                            <small class="form-control-feedback">{{ $errors->first('centro_custo_id') }}</small>
                        @endif
                    </div>

                </div>

            </div>

                <div class="form-group text-right pb-2">
                    <a href="{{ route('instituicao.estoques.index') }}">
                        <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                    </a>
                    <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
                </div>
            </form>
        </div>
    </div>
@endsection
