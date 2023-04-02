@extends('instituicao.layout')

@section('conteudo')
    @component('components/page-title', [
        'titulo' => 'Cadastrar Plano Convênio',
        'breadcrumb' => [
            'Plano' => route('instituicao.convenios.planos.index', [$convenio]),
            'Novo',
        ],
    ])
    @endcomponent


    <div class="card">
        <div class="card-body">
            <form action="{{ route('instituicao.convenios.planos.store', [$convenio]) }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row">

                    <div class="col-md-8 form-group @if($errors->has('nome')) has-danger @endif">
                        <label class="form-control-label">Nome <span class="text-danger">*</span></span></label>
                        <input type="text" name="nome" value="{{ old('nome') }}"
                            class="form-control @if($errors->has('nome')) form-control-danger @endif">
                        @if($errors->has('nome'))
                            <div class="form-control-feedback">{{ $errors->first('nome') }}</div>
                        @endif
                    </div>

                    <div class="col-md-4 col-sm-6 form-group d-flex flex-column justify-content-end @if($errors->has('paga_acompanhante')) has-danger @endif">
                        <div class="d-flex flex-wrap-revert align-items-center">
                            <input type="checkbox" name="paga_acompanhante" @if(old('paga_acompanhante')) checked="checked" @endif"
                            class="form-control checkbox @if($errors->has('paga_acompanhante')) form-control-danger @endif">
                            <label class="form-control-label ml-2 mb-0">Paga acompanhante</label>
                        </div>
                        @if($errors->has('paga_acompanhante'))
                            <div class="form-control-feedback">{{ $errors->first('paga_acompanhante') }}</div>
                        @endif
                    </div>

                    <div class="col-md-4 col-sm-6 form-group d-flex flex-column justify-content-end @if($errors->has('validade_indeterminada')) has-danger @endif">
                        <div class="d-flex flex-wrap-revert align-items-center">
                            <input type="checkbox" name="validade_indeterminada" @if(old('validade_indeterminada')) checked="checked" @endif"
                            class="form-control checkbox @if($errors->has('validade_indeterminada')) form-control-danger @endif">
                            <label class="form-control-label ml-2 mb-0">Validade indeterminada</label>
                        </div>
                        @if($errors->has('validade_indeterminada'))
                            <div class="form-control-feedback">{{ $errors->first('validade_indeterminada') }}</div>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8 form-group @if($errors->has('descricao')) has-danger @endif">
                        <label class="form-control-label">Detalhes do plano</span></label>
                        <textarea type="text" name="descricao" rows="4"
                            class="form-control @if($errors->has('descricao')) form-control-danger @endif">{{ old('descricao') }}</textarea>
                        @if($errors->has('descricao'))
                            <div class="form-control-feedback">{{ $errors->first('descricao') }}</div>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 col-sm-6 form-group @if($errors->has('senha_guia_obrigatoria')) has-danger @endif">
                        <label class="form-control-label">Senha da guia obrigatória</span></label>
                        <select name="senha_guia_obrigatoria"
                            class="form-control @if($errors->has('senha_guia_obrigatoria')) form-control-danger @endif">
                            <option value="1" @if(old('senha_guia_obrigatoria') == true) selected="selected" @endif>Sim</option>
                            <option value="0" @if(old('senha_guia_obrigatoria') == false) selected="selected" @endif>Não</option>
                        </select>
                        @if($errors->has('senha_guia_obrigatoria'))
                            <div class="form-control-feedback">{{ $errors->first('senha_guia_obrigatoria') }}</div>
                        @endif
                    </div>

                    <div class="col-md-4 col-sm-6 form-group @if($errors->has('valida_guia')) has-danger @endif">
                        <label class="form-control-label">Valida guia</span></label>
                        <select name="valida_guia"
                            class="form-control @if($errors->has('valida_guia')) form-control-danger @endif">
                            <option value="1" @if(old('valida_guia') == true) selected="selected" @endif>Sim</option>
                            <option value="0" @if(old('valida_guia') == false) selected="selected" @endif>Não</option>
                        </select>
                        @if($errors->has('valida_guia'))
                            <div class="form-control-feedback">{{ $errors->first('valida_guia') }}</div>
                        @endif
                    </div>
                    
                    <div class="col-md-4 col-sm-6 form-group @if($errors->has('regra_cobranca_id')) has-danger @endif">
                        <label class="form-control-label">Regra de cobrança</span></label>
                        <select name="regra_cobranca_id"
                            class="form-control @if($errors->has('regra_cobranca_id')) form-control-danger @endif">
                            <option value="">Selecione...</option>
                            @foreach ($regrasCobranca as $item)
                                <option value="{{$item->id}}" @if(old('regra_cobranca_id') == $item->id) selected="selected" @endif>{{$item->descricao}}</option>
                            @endforeach
                        </select>
                        @if($errors->has('regra_cobranca_id'))
                            <div class="form-control-feedback">{{ $errors->first('regra_cobranca_id') }}</div>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card shadow-none pt-2 px-3">
                            <div class="form-group col-12 p-0">
                                <h3>Permissões</h3>
                            </div>
                            <div class="input-group">
                                <div class="pl-0 pr-4 col-md-4 col-sm-6 form-group d-flex flex-column justify-content-end @if($errors->has('permissao_internacao')) has-danger @endif">
                                    <div class="d-flex flex-wrap-revert align-items-center">
                                        <input type="checkbox" name="permissao_internacao" @if(old('permissao_internacao')) checked="checked" @endif"
                                        class="form-control checkbox @if($errors->has('permissao_internacao')) form-control-danger @endif">
                                        <label class="form-control-label ml-2 mb-0">Internação</label>
                                    </div>
                                    @if($errors->has('permissao_internacao'))
                                        <div class="form-control-feedback">{{ $errors->first('permissao_internacao') }}</div>
                                    @endif
                                </div>

                                <div class="pl-0 pr-4 col-md-4 col-sm-6 form-group d-flex flex-column justify-content-end @if($errors->has('permissao_emergencia')) has-danger @endif">
                                    <div class="d-flex flex-wrap-revert align-items-center">
                                        <input type="checkbox" name="permissao_emergencia" @if(old('permissao_emergencia')) checked="checked" @endif"
                                        class="form-control checkbox @if($errors->has('permissao_emergencia')) form-control-danger @endif">
                                        <label class="form-control-label ml-2 mb-0">Urgência e Emergência</label>
                                    </div>
                                    @if($errors->has('permissao_emergencia'))
                                        <div class="form-control-feedback">{{ $errors->first('permissao_emergencia') }}</div>
                                    @endif
                                </div>

                                <div class="pl-0 pr-4 col-md-4 col-sm-6 form-group d-flex flex-column justify-content-end @if($errors->has('permissao_home_care')) has-danger @endif">
                                    <div class="d-flex flex-wrap-revert align-items-center">
                                        <input type="checkbox" name="permissao_home_care" @if(old('permissao_home_care')) checked="checked" @endif"
                                        class="form-control checkbox @if($errors->has('permissao_home_care')) form-control-danger @endif">
                                        <label class="form-control-label ml-2 mb-0">Home care</label>
                                    </div>
                                    @if($errors->has('permissao_home_care'))
                                        <div class="form-control-feedback">{{ $errors->first('permissao_home_care') }}</div>
                                    @endif
                                </div>

                                <div class="pl-0 pr-4 col-md-4 col-sm-6 form-group d-flex flex-column justify-content-end @if($errors->has('permissao_ambulatorio')) has-danger @endif">
                                    <div class="d-flex flex-wrap-revert align-items-center">
                                        <input type="checkbox" name="permissao_ambulatorio" @if(old('permissao_ambulatorio')) checked="checked" @endif"
                                        class="form-control checkbox @if($errors->has('permissao_ambulatorio')) form-control-danger @endif">
                                        <label class="form-control-label ml-2 mb-0">Ambulatório</label>
                                    </div>
                                    @if($errors->has('permissao_ambulatorio'))
                                        <div class="form-control-feedback">{{ $errors->first('permissao_ambulatorio') }}</div>
                                    @endif
                                </div>

                                <div class="pl-0 pr-4 col-md-4 col-sm-6 form-group d-flex flex-column justify-content-end @if($errors->has('permissao_externo')) has-danger @endif">
                                    <div class="d-flex flex-wrap-revert align-items-center">
                                        <input type="checkbox" name="permissao_externo" @if(old('permissao_externo')) checked="checked" @endif"
                                        class="form-control checkbox @if($errors->has('permissao_externo')) form-control-danger @endif">
                                        <label class="form-control-label ml-2 mb-0">Externo</label>
                                    </div>
                                    @if($errors->has('permissao_externo'))
                                        <div class="form-control-feedback">{{ $errors->first('permissao_externo') }}</div>
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="form-group text-right">
                        <a href="{{ route('instituicao.convenios.planos.index', [$convenio ]) }}">
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
            $('.checkbox').iCheck({
                checkboxClass: 'icheckbox_square',
                radioClass: 'iradio_square',
                increaseArea: '90%'
            })
        })
    </script>
@endpush
