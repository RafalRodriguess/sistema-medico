@extends('admin.layout')

@section('conteudo')
@component('components/page-title', [
    'titulo' => "Editar procedimento #{$procedimento->id} {$procedimento->descricao}",
    'breadcrumb' => [
        'procedimentos' => route('procedimentos.index'),
        'Novo',
    ],
    ])
    @endcomponent


    <div class="card">
        <div class="card-body">
            <form action="{{ route('procedimentos.update', [$procedimento]) }}" method="post">
                @method('put')
                @csrf
                <div class="row">

                    <div class="col-md-6 form-group @if($errors->has('descricao')) has-danger @endif">
                        <label class="form-control-label">Descrição: *</label>
                        <input type="text" name="descricao" value="{{ old('descricao', $procedimento->descricao) }}"
                        class="form-control @if($errors->has('descricao')) form-control-danger @endif">
                        @if($errors->has('descricao'))
                        <div class="form-control-feedback">{{ $errors->first('descricao') }}</div>
                        @endif
                    </div>

                    <div class=" col-md-6 form-group @if($errors->has('tipo')) has-danger @endif">
                        <label class="form-control-label">Tipo: *</label>
                        <script>
                            function onTipoExameChanged(select) {
                                if(select.value == 'exame') {
                                    $('#modalidade_exame_select').removeClass('d-none')
                                } else {
                                    $('#modalidade_exame_select').addClass('d-none')
                                    $('[name="modalidade_exame_id"]')[0].value = ''
                                }
                            }
                        </script>
                        <select name="tipo" onchange="onTipoExameChanged(this)" class="form-control
                        @if($errors->has('tipo')) form-control-danger @endif
                        " id="">
                        <option value="consulta" @if (empty(old('tipo')) && $procedimento->tipo == 'consulta' || old('tipo') == 'consulta') selected="selected" @endif>Consulta</option>
                        <option value="exame" @if (empty(old('tipo')) && $procedimento->tipo == 'exame' || old('tipo') == 'exame') selected="selected" @endif>Exame</option>
                    </select>
                </div>
            </div>
            <div class="row">

                <div id="modalidade_exame_select" class="col-md-6 form-group @if($errors->has('modalidade_exame_id')) has-danger @elseif(empty($procedimento->modalidade_exame_id)) d-none @endif">
                    <label class="form-control-label">Modalidade: *</label>
                    <select name="modalidade_exame_id" class="form-control
                    @if($errors->has('modalidade_exame_id')) form-control-danger @endif
                    " id="">
                    <option value="">Nenhuma</option>
                    @foreach ($modalidades as $modalidade)
                        <option value="{{ $modalidade->id }}" @if(($procedimento->modalidade_exame_id != null && $procedimento->modalidade_exame_id == $modalidade->id) || old('modalidade_exame_id') == $modalidade->id) selected="selected" @endif>{{ $modalidade->sigla }}</option>
                    @endforeach
                </select>
                @if($errors->has('modalidade_exame_id'))
                <div class="form-control-feedback">{{ $errors->first('modalidade_exame_id') }}</div>
                @endif
            </div>
        </div>



            <div class="form-group text-right">
                <a href="{{ route('procedimentos.index') }}">
                    <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                </a>
                <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
            </div>

        </form>
    </div>
</div>
@endsection
