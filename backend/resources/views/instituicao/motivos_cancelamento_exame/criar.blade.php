@extends('instituicao.layout')

@section('conteudo')
    @component('components/page-title', [
        'titulo' => 'Cadastrar novo motivo de cancelamento',
        'breadcrumb' => [
        'Motivos de cancelamento' => route('instituicao.motivoscancelamentoexame.index'),
        'Novo',
        ],
        ])
    @endcomponent


    <div class="card">
        <div class="card-body ">
            <form action="{{ route('instituicao.motivoscancelamentoexame.store') }}" method="post">
                @csrf

                <div class="row">
                    <div class=" col-md-6 form-group @if ($errors->has('descricao')) has-danger @endif">
                        <label class="form-control-label">Descricao: *</span></label>
                        <textarea rows="3" required type="text" name="descricao"
                            class="form-control @if ($errors->has('descricao')) form-control-danger @endif">{{ old('descricao') }}</textarea>
                        @if ($errors->has('descricao'))
                            <div class="form-control-feedback">{{ $errors->first('descricao') }}</div>
                        @endif
                    </div>
                    <div class=" col-md-6 form-group @if ($errors->has('tipo')) has-danger @endif">
                        <label class="form-control-label">Tipo: *</span></label>
                        <select required name="tipo" placeholder="Selecione o tipo de setor"
                            class="form-control @error('tipo') form-control-danger @enderror">
                            @foreach ($tipos as $tipo)
                                <option @if (old('tipo') == $tipo) selected="selected" @endif value="{{ $tipo }}">{{ $tipo }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('tipo'))
                            <div class="form-control-feedback">{{ $errors->first('tipo') }}</div>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class=" col-md-6 form-group @if ($errors->has('procedimento_instituicao_id')) has-danger @endif">
                        <label class="form-control-label">Procedimento: *</span></label>
                        <select required name="procedimento_instituicao_id" placeholder="Selecione o procedimento que foi cancelado"
                            class="form-control @error('procedimento_instituicao_id') form-control-danger @enderror">
                            @foreach ($procedimentos_instituicoes as $procedimento)
                                <option @if (old('procedimento_instituicao_id') == $procedimento->id) selected="selected" @endif value="{{ $procedimento->id }}">{{ "#{$procedimento->id} {$procedimento->procedimento->descricao}" }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('procedimento_instituicao_id'))
                            <div class="form-control-feedback">{{ $errors->first('procedimento_instituicao_id') }}</div>
                        @endif
                    </div>
                    <div class="col-md-6 form-group">
                        <label class="form-control-label">Status: *</span></label>
                        <select required name="ativo"
                            class="form-control @error('ativo') form-control-danger @enderror">
                            <option @if(old('ativo', 1) == 1) selected="selected" @endif value="1">Ativo</option>
                            <option @if(old('ativo', 1) == 0) selected="selected" @endif value="0">Inativo</option>
                        </select>
                        @if ($errors->has('ativo'))
                            <div class="form-control-feedback">{{ $errors->first('ativo') }}</div>
                        @endif
                    </div>
                </div>
                <div class="form-group text-right">
                    <hr>
                    <a href="{{ route('instituicao.motivoscancelamentoexame.index') }}">
                        <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i
                                class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                    </a>
                    <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i
                            class="mdi mdi-check"></i>
                        Salvar</button>
                </div>
            </form>
        </div>
    </div>
@endsection
