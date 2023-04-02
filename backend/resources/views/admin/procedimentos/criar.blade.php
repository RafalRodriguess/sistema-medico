@extends('admin.layout')

@section('conteudo')
@component('components/page-title', [
    'titulo' => 'Cadastrar Procedimento',
    'breadcrumb' => [
        'Procedimentos' => route('procedimentos.index'),
        'Novo',
    ],
    ])
    @endcomponent


    <div class="card">
        <div class="card-body ">
            <form action="{{ route('procedimentos.store') }}" method="post">
                @csrf

                <div class="row">
                    <div class=" col-md-6 form-group @if($errors->has('descricao')) has-danger @endif">
                        <label class="form-control-label">Descrição: *</span></label>
                        <input type="text" name="descricao" value="{{ old('descricao') }}"
                        class="form-control @if($errors->has('descricao')) form-control-danger @endif">
                        @if($errors->has('descricao'))
                        <div class="form-control-feedback">{{ $errors->first('descricao') }}</div>
                        @endif
                    </div>


                    <div class=" col-md-6 form-group @if($errors->has('tipo')) has-danger @endif">
                        <label class="form-control-label">Tipo: *</label>
                        <select name="tipo" class="form-control
                        @if($errors->has('tipo')) form-control-danger @endif
                        " id="">
                            <option value="consulta" @if (old('tipo') == 'consulta') selected="selected" @endif>Consulta</option>
                            <option value="exame" @if (old('tipo') == 'exame') selected="selected" @endif>Exame</option>
                        </select>
                        @if($errors->has('tipo'))
                        <div class="form-control-feedback">{{ $errors->first('tipo') }}</div>
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
                    <a href="{{ route('procedimentos.index') }}">
                        <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                    </a>
                    <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
                </div>
            </form>
        </div>
    </div>
    @endsection
