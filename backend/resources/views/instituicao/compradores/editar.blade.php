@extends('instituicao.layout')

@section('conteudo')

    @component('components/page-title', [
        'titulo' => "Editar comprador #{$comprador->id} {$comprador->descricao}",
        'breadcrumb' => [
            'Comprador' => route('instituicao.compradores.index'),
            'Atualização',
        ],
    ])
    @endcomponent


    <div class="card">
        <div class="card-body ">
            <form action="{{ route('instituicao.compradores.update', [$comprador]) }}" method="post">
                @method('put')
                @csrf

                <div class="row">
                    <div class=" col-md-12 form-group @if($errors->has('descricao')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Descrição<span class="text-danger">*</span></label>
                        <input type="text" name="descricao" value="{{ old('descricao', $comprador->descricao) }}"
                        class="form-control @if($errors->has('descricao')) form-control-danger @endif">
                        @if($errors->has('descricao'))
                            <small class="form-control-feedback">{{ $errors->first('descricao') }}</small>
                        @endif
                    </div>

                    <div class=" col-md-6 form-group @if($errors->has('email')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">E-mail<span class="text-danger">*</span></label>
                        <input type="email" name="email" required value="{{ old('email', $comprador->email) }}"
                        class="form-control @if($errors->has('email')) form-control-danger @endif">
                        @if($errors->has('email'))
                            <small class="form-control-feedback">{{ $errors->first('email') }}</small>
                        @endif
                    </div>

                    <div class=" col-md-6 form-group @if($errors->has('usuario_id')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Usuario<span class="text-danger">*</span></label>
                        <select class="form-control select2 @if($errors->has('usuario_id')) form-control-danger @endif" name="usuario_id" id="usuario_id" required style="width: 100%">
                            <option value="">Selecione</option>
                            @foreach($usuarios as $usuario)
                                <option value="{{ $usuario->id }}" @if(old('tipo', $usuario->id)==$comprador->usuario_id ) selected @endif >{{ $usuario->nome }} </option>
                            @endforeach
                        </select>
                        @if($errors->has('usuario_id'))
                            <small class="form-control-feedback">{{ $errors->first('usuario_id') }}</small>
                        @endif
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <input type="checkbox" id="urgente" @if(old('urgente', $comprador->ativo)==true) checked @endif   value="1" name="urgente" class="filled-in" />
                            <label for="urgente">Urgente<label>
                        </div>
                    </div>

                </div>

            </div>

                <div class="form-group text-right pb-2">
                    <a href="{{ route('instituicao.compradores.index') }}">
                        <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                    </a>
                    <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
                </div>
            </form>
        </div>
    </div>
@endsection
