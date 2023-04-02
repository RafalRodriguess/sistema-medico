@extends('admin.layout')

@section('conteudo')
@component('components/page-title', [
    'titulo' => 'Cadastrar Grupo',
    'breadcrumb' => [
        'Grupos' => route('grupos_procedimentos.index'),
        'Novo',
    ],
    ])
    @endcomponent


    <div class="card">
        <div class="card-body ">
            <form action="{{ route('grupos_procedimentos.store') }}" method="post">
                @csrf

                <div class="row">
                    <div class=" col-md-6 form-group @if($errors->has('nome')) has-danger @endif">
                        <label class="form-control-label">Nome: *</span></label>
                        <input type="text" name="nome" value="{{ old('nome') }}"
                        class="form-control @if($errors->has('nome')) form-control-danger @endif">
                        @if($errors->has('nome'))
                        <div class="form-control-feedback">{{ $errors->first('nome') }}</div>
                        @endif
                    </div>


            </div>

            {{-- So uma coisa que descobrir por agora, é do Laravel mais novo --}}
            {{-- @error('campo')  @enderror --}}
            {{-- @error('campo') {{ $message }}  @enderror --}}

                {{-- <div class="button-group">
                    <button type="button" class="btn waves-effect waves-light btn-primary">Primary</button>
                    <button type="button" class="btn waves-effect waves-light btn-secondary">Secondary</button>
                    <button type="button" class="btn waves-effect waves-light btn-success">Success</button>
                    <button type="button" class="btn waves-effect waves-light btn-info">Info</button>
                    <button type="button" class="btn waves-effect waves-light btn-warning">Warning</button>
                    <button type="button" class="btn waves-effect waves-light btn-danger">Danger</button>
                </div> --}}

                <div class="form-group text-right">
                    <hr>
                    <a href="{{ route('grupos_procedimentos.index') }}">
                        <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                    </a>
                    <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
                </div>
            </form>
        </div>
    </div>
    @endsection
