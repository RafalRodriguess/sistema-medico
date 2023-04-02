@extends('admin.layout')

@section('conteudo')
    @component('components/page-title', [
        'titulo' => "Editar Administrador #{$usuario->id} {$usuario->nome}",
        'breadcrumb' => [
            'Usuarios' => route('usuarios.index'),
            'Editar',
        ],
    ])
    @endcomponent


    <div class="card">
        <div class="card-body">
            <form action="{{ route('usuarios.update', [$usuario]) }}" method="post">
                @method('put')
                @csrf

                <div class="form-group @if($errors->has('nome')) has-danger @endif">
                    <label class="form-control-label">Nome *</span></label>
                    <input type="text" name="nome" value="{{ old('nome', $usuario->nome) }}"
                        class="form-control @if($errors->has('nome')) form-control-danger @endif">
                    @if($errors->has('nome'))
                        <div class="form-control-feedback">{{ $errors->first('nome') }}</div>
                    @endif
                </div>
                <div class="form-group @if($errors->has('cpf')) has-danger @endif">
                    <label class="form-control-label">CPF *</label>
                    <input type="text" name="cpf" alt="cpf" value="{{ old('cpf', $usuario->cpf) }}"
                        class="form-control @if($errors->has('cpf')) form-control-danger @endif">
                    @if($errors->has('cpf'))
                        <div class="form-control-feedback">{{ $errors->first('cpf') }}</div>
                    @endif
                </div>
                <div class="form-group @if($errors->has('data_nascimento')) has-danger @endif">
                    <label class="form-control-label">Data Nascimento *</label>
                    <input type="date" name="data_nascimento" value="{{ old('data_nascimento', with($usuario->data_nascimento ?: \Carbon\Carbon::now())->toDateString()
                    ) }}"
                        class="form-control @if($errors->has('data_nascimento')) form-control-danger @endif">
                    @if($errors->has('data_nascimento'))
                        <div class="form-control-feedback">{{ $errors->first('data_nascimento') }}</div>
                    @endif
                </div>


                <div class="form-group @if($errors->has('telefone')) has-danger @endif">
                    <label class="form-control-label">Telefone *</label>
                    <input type="text" name="telefone" alt="phone" value="{{ old('telefone', $usuario->telefone) }}"
                        class="form-control  @if($errors->has('telefone')) form-control-danger @endif">
                    @if($errors->has('telefone'))
                        <div class="form-control-feedback">{{ $errors->first('telefone') }}</div>
                    @endif
                </div>

                <div class="form-group @if($errors->has('email')) has-danger @endif">
                    <label class="form-control-label">Email</label>
                    <input type="email" name="email" value="{{ old('email', $usuario->email)}}"
                        class="form-control  @if($errors->has('email')) form-control-danger @endif">
                    @if($errors->has('email'))
                        <div class="form-control-feedback">{{ $errors->first('email') }}</div>
                    @endif
                </div>

                {{-- <div class="form-group">
                    <label class="form-control-label">Mãe</label>
                    <input type="text" name="nome_mae" value="{{old('nome_mae', $usuario->nome_mae)}}"
                        class="form-control">
                </div>

                <div class="form-group @if($errors->has('data_nascimento_mae')) has-danger @endif">
                    <label class="form-control-label">Data nascimento mãe</label>
                    <input type="date" name="data_nascimento_mae" value="{{old('data_nascimento_mae', optional($usuario->data_nascimento_mae)->toDateString()
                    )}}"
                        class="form-control @if($errors->has('data_nascimento_mae')) form-control-danger @endif">

                    @if($errors->has('data_nascimento_mae'))
                        <div class="form-control-feedback">{{ $errors->first('data_nascimento_mae') }}</div>
                    @endif
                </div> --}}

                <div class="form-group text-right">
                        <a href="{{ route('usuarios.index') }}">
                                <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                        </a>
                        <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
                </div>
            </form>
        </div>
    </div>
@endsection
