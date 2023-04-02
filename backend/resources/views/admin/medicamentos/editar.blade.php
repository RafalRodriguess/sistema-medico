@extends('admin.layout')

@section('conteudo')
    @component('components/page-title', [
        'titulo' => "Editar Composição de Medicamento #{$medicamento->id} {$medicamento->componente}",
        'breadcrumb' => [
            'Medicamentos' => route('medicamentos.index'),
            'Novo',
        ],
    ])
    @endcomponent


    <div class="card">
        <div class="card-body">
            <form action="{{ route('medicamentos.update', [$medicamento]) }}" method="post">
                @method('put')
                @csrf

                <div class="form-group @if($errors->has('componente')) has-danger @endif">
                    <label class="form-control-label">Composição *</span></label>
                    <input type="text" name="componente" value="{{ old('componente', $medicamento->componente) }}"
                        class="form-control @if($errors->has('componente')) form-control-danger @endif">
                    @if($errors->has('componente'))
                        <div class="form-control-feedback">{{ $errors->first('componente') }}</div>
                    @endif
                </div>
                {{-- <div class="form-group @if($errors->has('quantidade')) has-danger @endif">
                    <label class="form-control-label">Quantidade *</label>
                    <input type="text" name="quantidade" alt="money" value="{{ old('quantidade',$medicamento->quantidade) }}"
                        class="form-control @if($errors->has('quantidade')) form-control-danger @endif">
                    @if($errors->has('quantidade'))
                        <div class="form-control-feedback">{{ $errors->first('quantidade') }}</div>
                    @endif
                </div>
                <div class="form-group @if($errors->has('unidade')) has-danger @endif">
                    <label class="form-control-label">Unidade *</label>
                    <input type="text" name="unidade" value="{{ old('unidade', $medicamento->unidade) }}"
                        class="form-control @if($errors->has('unidade')) form-control-danger @endif">
                    @if($errors->has('unidade'))
                        <div class="form-control-feedback">{{ $errors->first('unidade') }}</div>
                    @endif
                </div> --}}
                <div class="form-group @if($errors->has('codigo_externo')) has-danger @endif">
                    <label class="form-control-label">Código *</label>
                    <input type="text" name="codigo_externo" value="{{ old('codigo_externo', $medicamento->codigo_externo) }}"
                        class="form-control @if($errors->has('codigo_externo')) form-control-danger @endif">
                    @if($errors->has('codigo_externo'))
                        <div class="form-control-feedback">{{ $errors->first('unidade') }}</div>
                    @endif
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
                    <a href="{{ route('medicamentos.index') }}">
                    <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                    </a>
                    <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
                </div>
            </form>
        </div>
    </div>
@endsection
