@extends('admin.layout')

@section('conteudo')
    @component('components/page-title', [
        'titulo' => "Editar conta bancária #{$instituicao->id} {$instituicao->nome}",
        'breadcrumb' => [
            'Instituição' => route('instituicoes.index'),
            'Editar',
        ],
    ])
    @endcomponent


    <div class="card">
        <div class="card-body">
            <form action="{{ route('instituicoes.banco.update', [$instituicao]) }}" method="post">
                @method('put')
                @csrf
                @php
                $bancos = buscarBancos();
                @endphp
                <div class="form-group @error('banco_id') has-danger @enderror">
                    <label class="control-label">Banco</label>
                            <input type="hidden" name="banco_nome" id="banco_nome" value="{{ old('banco_nome', $instituicao->banco ? $instituicao->banco->bank_name : '') }}" />
                            <select class="select2 @error('banco_id') form-control-danger @enderror" id="banco_id" name="banco_id"  style="width: 100%">
                            <option value="">Selecione um banco</option>
                            @foreach ($bancos as $banco_id => $banco_nome)
                                <option value="{{ $banco_id }}"
                                    @if($instituicao->banco && $instituicao->banco->bank_code == $banco_id) selected="selected" @endif
                                    @if(old('banco_id') == $banco_id) selected="selected" @endif>
                                    {{ $banco_nome }}
                                </option>
                            @endforeach
                            </select>
                            @error('banco_id')
                            <div class="form-control-feedback">{{ $message }}</div>
                        @enderror

                </div>

                <div class="row">
                    <div class="col-md-9">
                        <div class="form-group @if($errors->has('agencia')) has-danger @endif">
                            <label class="form-control-label"> Agência </span></label>

                            <input type="text" name="agencia" value="{{ old('agencia', $instituicao->banco ? $instituicao->banco->agencia : '') }}"
                                class="form-control @if($errors->has('agencia')) form-control-danger @endif">
                            @if($errors->has('agencia'))
                                <div class="form-control-feedback">{{ $errors->first('agencia') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group @if($errors->has('agencia_dv')) has-danger @endif">
                            <label class="form-control-label"> Agência digito verificador </span></label>

                            <input type="text" name="agencia_dv" value="{{ old('agencia_dv', $instituicao->banco ? $instituicao->banco->agencia_dv : '') }}"
                                class="form-control @if($errors->has('agencia_dv')) form-control-danger @endif">
                            @if($errors->has('agencia_dv'))
                                <div class="form-control-feedback">{{ $errors->first('agencia_dv') }}</div>
                            @endif
                        </div>
                    </div>
                </div>




                <div class="row">
                    <div class="col-md-9">
                        <div class="form-group @if($errors->has('conta')) has-danger @endif">
                            <label class="form-control-label"> Conta </span></label>

                            <input type="text" name="conta" value="{{ old('conta', $instituicao->banco ? $instituicao->banco->conta : '') }}"
                                class="form-control @if($errors->has('conta')) form-control-danger @endif">
                            @if($errors->has('conta'))
                                <div class="form-control-feedback">{{ $errors->first('conta') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group @if($errors->has('conta_dv')) has-danger @endif">
                            <label class="form-control-label"> Conta digito verificador </span></label>

                            <input type="text" name="conta_dv" value="{{ old('conta_dv', $instituicao->banco ? $instituicao->banco->conta_dv : '') }}"
                                class="form-control @if($errors->has('conta_dv')) form-control-danger @endif">
                            @if($errors->has('conta_dv'))
                                <div class="form-control-feedback">{{ $errors->first('conta_dv') }}</div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row form-group">
                    <div class="col-md-12 @error('type') has-danger @enderror">
                    <label class="control-label">Tipo de conta</label>
                    </div>
                    <div class="col-md-3">
                        <label class="i-checks">
                            <input type="radio" name="type" @if(old('type') == 'conta_corrente') checked @elseif($instituicao->banco && $instituicao->banco->type == 'conta_corrente') checked @elseif(!$instituicao->banco) checked @endif  value="conta_corrente" > Conta corrente
                        </label>
                    </div>
                    <div class="col-md-3">
                        <label class="i-checks">
                            <input type="radio" name="type" value="conta_poupanca" @if(old('type') == 'conta_poupanca') checked @elseif($instituicao->banco && $instituicao->banco->type == 'conta_poupanca') checked  @endif > Conta poupança
                        </label>
                    </div>
                    <div class="col-md-3">
                        <label class="i-checks">
                            <input type="radio" name="type" value="conta_corrente_conjunta" @if(old('type') == 'conta_corrente_conjunta') checked @elseif($instituicao->banco && $instituicao->banco->type == 'conta_corrente_conjunta') checked  @endif> Conta corrente conjunta
                        </label>
                    </div>
                    <div class="col-md-3">
                        <label class="i-checks">
                            <input type="radio" name="type" value="conta_poupanca_conjunta" @if(old('type') == 'conta_poupanca_conjunta') checked @elseif($instituicao->banco && $instituicao->banco->type == 'conta_poupanca_conjunta') checked  @endif> Conta poupança conjunta
                        </label>
                    </div>
                    @error('type')
                        <div class="form-control-feedback">{{ $message }}</div>
                    @enderror
                </div>


                {{-- <div class="form-group @error('type') has-danger @enderror">
                    <label class="control-label">Tipo de conta</label>
                            <select class="select2 @error('type') form-control-danger @enderror" id="type" name="type"  style="width: 100%">
                            <option value="">Selecione um tipo de conta</option>

                                <option value="conta_corrente"
                                    @if(old('type') == 'conta_corrente') selected="selected" @endif>
                                    Conta corrente
                                </option>
                                <option value="conta_poupanca"
                                    @if(old('type') == 'conta_poupanca') selected="selected" @endif>
                                    Conta poupança
                                </option>
                                <option value="conta_corrente_conjunta"
                                    @if(old('type') == 'conta_corrente_conjunta') selected="selected" @endif>
                                    Conta corrente conjunta
                                </option>
                                <option value="conta_poupanca_conjunta"
                                    @if(old('type') == 'conta_poupanca_conjunta') selected="selected" @endif>
                                    Conta poupança conjunta
                                </option>

                            </select>
                            @error('type')
                            <div class="form-control-feedback">{{ $message }}</div>
                        @enderror

                </div> --}}

                <div class="form-group @if($errors->has('nome_titular')) has-danger @endif">
                    <label class="form-control-label"> Nome do titular </span></label>

                    <input type="text" name="nome_titular" value="{{ old('nome_titular', $instituicao->banco ? $instituicao->banco->nome_titular : '') }}"
                        class="form-control @if($errors->has('nome_titular')) form-control-danger @endif">
                    @if($errors->has('nome_titular'))
                        <div class="form-control-feedback">{{ $errors->first('nome_titular') }}</div>
                    @endif
                </div>

                <div class="form-group @if($errors->has('documento_titular')) has-danger @endif">
                    <label class="form-control-label"> Documento do titular (CPF ou CNPJ)</span></label>

                    <input type="text" name="documento_titular" value="{{ old('documento_titular', $instituicao->banco ? $instituicao->banco->documento_titular : '') }}"
                        class="form-control @if($errors->has('documento_titular')) form-control-danger @endif">
                    @if($errors->has('documento_titular'))
                        <div class="form-control-feedback">{{ $errors->first('documento_titular') }}</div>
                    @endif
                </div>



                <div class="form-group text-right">
                        <a href="{{ route('instituicoes.index') }}">
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

    $('input[type="radio"]').iCheck({
		checkboxClass: 'icheckbox_square-green',
		radioClass: 'iradio_square-green'
	})

    $('#banco_id').select2().on('select2:select', function (e) {
        $('#banco_nome').val(e.params.data.element.label);
    });
</script>
@endpush

