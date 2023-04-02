@extends('instituicao.layout')

@section('conteudo')

    @component('components/page-title', [
        'titulo' => "Editar painel: {$painel->descricao}",
        'breadcrumb' => [
            'Paineis de totem' => route('instituicao.totens.paineis.index'),
            'Editar painel',
        ],
    ])
    @endcomponent


    <div class="card">
        <div class="card-body ">
            <form action="{{ route('instituicao.totens.paineis.update', $painel) }}" method="post">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class=" col-md-6 form-group @if($errors->has('descricao')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Descrição</label>
                        <input type="text" name="descricao" value="{{ old('descricao', $painel->descricao) }}"
                        class="form-control @if($errors->has('descricao')) form-control-danger @endif">
                        @if($errors->has('descricao'))
                            <small class="form-control-feedback">{{ $errors->first('descricao') }}</small>
                        @endif
                    </div>

                    <div class=" col-md-6 form-group @if($errors->has('origens_id')) has-danger @endif">
                        <label class="form-control-label p-0 m-0">Origem</label>
                        <select name="origens_id" id="origens_id"
                        class="form-control @if($errors->has('origens_id')) form-control-danger @endif">
                            @foreach ($origens as $origem)
                                <option value="{{ $origem->id }}" @if(old('origens_id', $painel->origens_id) == $origem->id) selected @endif>{{ $origem->descricao }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('origens_id'))
                            <small class="form-control-feedback">{{ $errors->first('origens_id') }}</small>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card shadow-none bordered">
                            <div class="card-header">
                                <label>Opções do totem</label>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered">
                                    <colgroup>
                                        <col style="width: 30%">
                                        <col style="width: 35%">
                                        <col style="width: 35%">
                                    </colgroup>
                                    <thead>
                                        <tr>
                                            <th>Tipo</th>
                                            <th>Título</th>
                                            <th>Local</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($opcoes as $key => $opcao)
                                            @php
                                                $escolha = !empty($opcao->tiposChamada) ? $opcao->tiposChamada[0] ?? null : null;
                                            @endphp
                                            <tr>
                                                <input type="hidden" name="opcoes[{{ $key }}][tipos_chamada_id]" value="{{ $opcao->id }}">
                                                <td><input type="checkbox" name="opcoes[{{ $key }}][ativo]" class="checkbox-opcao" @if(!empty(old("opcoes.{$key}.ativo", !empty($escolha) ? $escolha->ativo : null))) checked @endif> {{ $opcao->descricao }}</td>
                                                <td>
                                                    <div class="d-flex">
                                                        <span class="text-danger mr-2">*</span> <input type="text" name="opcoes[{{ $key }}][titulo]" class="form-control" value="{{ old("opcoes.{$key}.titulo", !empty($escolha) ? $escolha->titulo : null) }}">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex">
                                                        <span class="text-danger mr-2">*</span> <input type="text" name="opcoes[{{ $key }}][local]" class="form-control" value="{{ old("opcoes.{$key}.local",!empty($escolha) ? $escolha->local : null) }}">
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group text-right pb-2">
                    <a href="{{ route('instituicao.totens.paineis.index') }}">
                        <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                    </a>
                    <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('.checkbox-opcao').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green'
            })
            $('#origens_id').select2()
        })
    </script>
@endpush
