@extends('instituicao.layout')

@section('conteudo')

    @component('components/page-title', [
        'titulo' => 'Editar Fornecedores',
        'breadcrumb' => [
            'Fornecedores' => route('instituicao.fornecedores.index'),
            'Atualizar',
        ],
    ])
    @endcomponent


    <div class="card">
        <div class="card-body ">
            <form action="{{ route('instituicao.fornecedores.update', [$fornecedor]) }}" method="post" enctype="multipart/form-data">
                @method('put')
                @csrf

                <div class="row">

                    <div class="col-sm-12">
                        <div class="form-group @if($errors->has('personalidade')) has-danger @endif">
                            <label class="form-control-label p-0 m-0">Personalidade <span class="text-danger">*</span></label>
                            <select name="personalidade"
                                class="form-control campo @if($errors->has('personalidade')) form-control-danger @endif">
                                <option selected disabled>Personalidade</option>
                                @foreach ($personalidades as $personalidade)
                                    <option value="{{ $personalidade }}" @if ($fornecedor->personalidade==$personalidade)
                                        selected
                                    @endif @if(old('personalidade')==$personalidade) selected @endif>{{ App\Pessoa::getPersonalidadeTexto($personalidade) }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('personalidade'))
                                <small class="form-text text-danger">{{ $errors->first('personalidade') }}</small>
                            @endif
                        </div>
                    </div>

                </div>

                <div class="card shadow-none col-sm-12 m-0 p-0 mb-3" style="display: none"      id="campos-fisico-juridico">

                    <div class="row m-0 mb-3 p-3 bg-light border-bottom">
                        <div class="col-sm-12">
                            <label class="form-control-label p-0 m-0" id="personalidade-selecionada"></label>
                        </div>
                    </div>

                    <div class="col-sm-12 m-0">

                        <div class="col-sm-12 p-0 m-0" style="display: none" id="campos-pessoa-fisica">

                            <div class="row">

                                <div class="col-sm-2">
                                    <div class="form-group cpf-campo @if($errors->has('cpf')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0 cpf">CPF <span class="text-primary">*</span></label>
                                        <input type="text" name="cpf" data-prev="{{ $fornecedor->cpf }}" alt="cpf" value="{{ old('cpf', $fornecedor->cpf) }}"
                                            class="form-control campo @if($errors->has('cpf')) form-control-danger @endif">
                                        @if($errors->has('cpf'))
                                            <small class="form-text text-danger">{{ $errors->first('cpf') }}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group @if($errors->has('nome')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0 nome">Nome <span class="text-primary">*</span></label>
                                        <input type="text" name="nome" value="{{ old('nome', $fornecedor->nome) }}"
                                            class="form-control campo @if($errors->has('nome')) form-control-danger @endif">
                                        @if($errors->has('nome'))
                                            <small class="form-text text-danger">{{ $errors->first('nome') }}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-sm-2">
                                    <div class="form-group @if($errors->has('telefone1')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">Telefone 1 <span class="text-danger">*</span></label>
                                        <input type="text" name="telefone1" value="{{ old('telefone1', $fornecedor->telefone1) }}"
                                            class="form-control campo telefone @if($errors->has('telefone1')) form-control-danger @endif">
                                        @if($errors->has('telefone1'))
                                            <small class="form-text text-danger">{{ $errors->first('telefone1') }}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-sm-2">
                                    <div class="form-group @if($errors->has('telefone2')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">Telefone 2</label>
                                        <input type="text" name="telefone2" value="{{ old('telefone2', $fornecedor->telefone2) }}"
                                            class="form-control campo telefone @if($errors->has('telefone2')) form-control-danger @endif">
                                        @if($errors->has('telefone2'))
                                            <small class="form-text text-danger">{{ $errors->first('telefone2') }}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-sm-2">
                                    <div class="form-group @if($errors->has('telefone3')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">Telefone 3</label>
                                        <input type="text" name="telefone3" value="{{ old('telefone3', $fornecedor->telefone3) }}"
                                            class="form-control campo telefone @if($errors->has('telefone3')) form-control-danger @endif">
                                        @if($errors->has('telefone3'))
                                            <small class="form-text text-danger">{{ $errors->first('telefone3') }}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="form-group @if($errors->has('email')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">Email</label>
                                        <input type="text" name="email" value="{{ old('email', $fornecedor->email) }}"
                                            class="form-control campo @if($errors->has('email')) form-control-danger @endif">
                                        @if($errors->has('email'))
                                            <small class="form-text text-danger">{{ $errors->first('email') }}</small>
                                        @endif
                                    </div>
                                </div>

                            </div>

                            <div class="row">

                                <div class="col-sm-4">
                                    <div class="form-group @if($errors->has('cep')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">CEP </label>
                                        <input type="text" name="cep" alt="cep" id="cep" value="{{ old('cep', $fornecedor->cep) }}"
                                            class="form-control campo @if($errors->has('cep')) form-control-danger @endif">
                                        @if($errors->has('cep'))
                                            <div class="form-control-feedback">{{ $errors->first('cep') }}</div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="form-group @if($errors->has('estado')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">Estado </label>
                                        <select class="form-control campo select2-average @if($errors->has('estado')) form-control-danger  @endif" name="estado">
                                            <option value="" selected>Selecione uma UF</option>

                                            <option value="AC" @if ($fornecedor->estado=="AC")
                                                selected
                                            @endif @if (old('estado') == 'AC')
                                                selected="selected"
                                            @endif>Acre</option>
                                            <option value="AL" @if ($fornecedor->estado=="AL")
                                                selected
                                            @endif @if (old('estado') == 'AL')
                                                selected="selected"
                                            @endif>Alagoas</option>
                                            <option value="AP" @if ($fornecedor->estado=="AP")
                                                selected
                                            @endif @if (old('estado') == 'AP')
                                                selected="selected"
                                            @endif>Amapá</option>
                                            <option value="AM" @if ($fornecedor->estado=="AM")
                                                selected
                                            @endif @if (old('estado') == 'AM')
                                                selected="selected"
                                            @endif>Amazonas</option>
                                            <option value="BA" @if ($fornecedor->estado=="BA")
                                                selected
                                            @endif @if (old('estado') == 'BA')
                                                selected="selected"
                                            @endif>Bahia</option>
                                            <option value="CE" @if ($fornecedor->estado=="CE")
                                                selected
                                            @endif @if (old('estado') == 'CE')
                                                selected="selected"
                                            @endif>Ceará</option>
                                            <option value="DF" @if ($fornecedor->estado=="DF")
                                                selected
                                            @endif @if (old('estado') == 'DF')
                                                selected="selected"
                                            @endif>Distrito Federal</option>
                                            <option value="GO" @if ($fornecedor->estado=="GO")
                                                selected
                                            @endif @if (old('estado') == 'GO')
                                                selected="selected"
                                            @endif>Goiás</option>
                                            <option value="ES" @if ($fornecedor->estado=="ES")
                                                selected
                                            @endif @if (old('estado') == 'ES')
                                                selected="selected"
                                            @endif>Espírito Santo</option>
                                            <option value="MA" @if ($fornecedor->estado=="MA")
                                                selected
                                            @endif @if (old('estado') == 'MA')
                                                selected="selected"
                                            @endif>Maranhão</option>
                                            <option value="MT" @if ($fornecedor->estado=="MT")
                                                selected
                                            @endif @if (old('estado') == 'MT')
                                                selected="selected"
                                            @endif>Mato Grosso</option>
                                            <option value="MS" @if ($fornecedor->estado=="MS")
                                                selected
                                            @endif @if (old('estado') == 'MS')
                                                selected="selected"
                                            @endif>Mato Grosso do Sul</option>
                                            <option value="MG" @if ($fornecedor->estado=="MG")
                                                selected
                                            @endif @if (old('estado') == 'MG')
                                                selected="selected"
                                            @endif>Minas Gerais</option>
                                            <option value="PA" @if ($fornecedor->estado=="PA")
                                                selected
                                            @endif @if (old('estado') == 'PA')
                                                selected="selected"
                                            @endif>Pará</option>
                                            <option value="PB" @if ($fornecedor->estado=="PB")
                                                selected
                                            @endif @if (old('estado') == 'PB')
                                                selected="selected"
                                            @endif>Paraiba</option>
                                            <option value="PR" @if ($fornecedor->estado=="PR")
                                                selected
                                            @endif @if (old('estado') == 'PR')
                                                selected="selected"
                                            @endif>Paraná</option>
                                            <option value="PE" @if ($fornecedor->estado=="PE")
                                                selected
                                            @endif @if (old('estado') == 'PE')
                                                selected="selected"
                                            @endif>Pernambuco</option>
                                            <option value="PI" @if ($fornecedor->estado=="PI")
                                                selected
                                            @endif @if (old('estado') == 'PI')
                                                selected="selected"
                                            @endif>Piauí­</option>
                                            <option value="RJ" @if ($fornecedor->estado=="RJ")
                                                selected
                                            @endif @if (old('estado') == 'RJ')
                                                selected="selected"
                                            @endif>Rio de Janeiro</option>
                                            <option value="RN" @if ($fornecedor->estado=="RN")
                                                selected
                                            @endif @if (old('estado') == 'RN')
                                                selected="selected"
                                            @endif>Rio Grande do Norte</option>
                                            <option value="RS" @if ($fornecedor->estado=="RS")
                                                selected
                                            @endif @if (old('estado') == 'RS')
                                                selected="selected"
                                            @endif>Rio Grande do Sul</option>
                                            <option value="RO" @if ($fornecedor->estado=="RO")
                                                selected
                                            @endif @if (old('estado') == 'RO')
                                                selected="selected"
                                            @endif>Rondônia</option>
                                            <option value="RR" @if ($fornecedor->estado=="RR")
                                                selected
                                            @endif @if (old('estado') == 'RR')
                                                selected="selected"
                                            @endif>Roraima</option>
                                            <option value="SP" @if ($fornecedor->estado=="SP")
                                                selected
                                            @endif @if (old('estado') == 'SP')
                                                selected="selected"
                                            @endif>São Paulo</option>
                                            <option value="SC" @if ($fornecedor->estado=="SC")
                                                selected
                                            @endif @if (old('estado') == 'SC')
                                                selected="selected"
                                            @endif>Santa Catarina</option>
                                            <option value="SE" @if ($fornecedor->estado=="SE")
                                                selected
                                            @endif @if (old('estado') == 'SE')
                                                selected="selected"
                                            @endif>Sergipe</option>
                                            <option value="TO" @if ($fornecedor->estado=="TO")
                                                selected
                                            @endif @if (old('estado') == 'TO')
                                                selected="selected"
                                            @endif>Tocantins</option>
                                        </select>
                                        @if($errors->has('estado'))
                                            <small class="form-text text-danger">{{ $errors->first('estado') }}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="form-group @if($errors->has('cidade')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">Cidade </label>
                                        <input id="cidade" type="text" name="cidade" value="{{ old('cidade', $fornecedor->cidade) }}"
                                            class="form-control campo @if($errors->has('cidade')) form-control-danger @endif">
                                        @if($errors->has('cidade'))
                                            <small class="form-text text-danger">{{ $errors->first('cidade') }}</small>
                                        @endif
                                    </div>
                                </div>

                            </div>

                            <div class="row">

                                <div class="col-sm-2">
                                    <div class="form-group @if($errors->has('bairro')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">Bairro</label>
                                        <input id="bairro" type="text" name="bairro" value="{{ old('bairro', $fornecedor->bairro) }}"
                                            class="form-control campo @if($errors->has('bairro')) form-control-danger @endif">
                                        @if($errors->has('bairro'))
                                            <small class="form-text text-danger">{{ $errors->first('bairro') }}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="form-group @if($errors->has('rua')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">Rua</label>
                                        <input type="text" name="rua" id="rua" value="{{ old('rua', $fornecedor->rua) }}"
                                            class="form-control campo @if($errors->has('rua')) form-control-danger @endif">
                                        @if($errors->has('rua'))
                                            <small class="form-text text-danger">{{ $errors->first('rua') }}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-sm-2">
                                    <div class="form-group @if($errors->has('numero')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">Numero</label>
                                        <input type="text" name="numero" id="numero" value="{{ old('numero', $fornecedor->numero) }}"
                                            class="form-control @if($errors->has('numero')) form-control-danger campo @endif">
                                        @if($errors->has('numero'))
                                            <small class="form-text text-danger">{{ $errors->first('numero') }}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="form-group @if($errors->has('complemento')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">Complemento</label>
                                        <input type="text" name="complemento" id="complemento" value="{{ old('complemento', $fornecedor->complemento) }}"
                                            class="form-control @if($errors->has('complemento')) form-control-danger campo @endif">
                                        @if($errors->has('complemento'))
                                            <small class="form-text text-danger">{{ $errors->first('complemento') }}</small>
                                        @endif
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="col-sm-12 p-0 m-0" style="display: none" id="campos-pessoa-juridica">

                            <div class="row">

                                <div class="col-sm-2">
                                    <div class="form-group cnpj-campo @if($errors->has('cnpj')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">CNPJ</label>
                                        <input type="text" name="cnpj" data-prev="{{ $fornecedor->cnpj }}" alt="cnpj" value="{{ old('cnpj', $fornecedor->cnpj) }}"
                                            class="form-control campo @if($errors->has('cnpj')) form-control-danger @endif">
                                        @if($errors->has('cnpj'))
                                            <small class="form-text text-danger">{{ $errors->first('cnpj') }}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group @if($errors->has('nome_fantasia')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">Nome Fantasia<span class="text-danger">*</span></label>
                                        <input type="text" name="nome_fantasia" value="{{ old('nome_fantasia', $fornecedor->nome_fantasia) }}"
                                            class="form-control campo @if($errors->has('nome_fantasia')) form-control-danger @endif">
                                        @if($errors->has('nome_fantasia'))
                                            <small class="form-text text-danger">{{ $errors->first('nome_fantasia') }}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-sm-7">
                                    <div class="form-group @if($errors->has('razao_social')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">Razão Social <span class="text-danger">*</span></label>
                                        <input type="text" name="razao_social" value="{{ old('razao_social', $fornecedor->razao_social) }}"
                                            class="form-control campo @if($errors->has('razao_social')) form-control-danger @endif">
                                        @if($errors->has('razao_social'))
                                            <small class="form-text text-danger">{{ $errors->first('razao_social') }}</small>
                                        @endif
                                    </div>
                                </div>

                            </div>

                            <div class="row">

                                <div class="col-sm-3">
                                    <div class="form-group site-campo @if($errors->has('site')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">Site </label>
                                        <input type="text" name="site" value="{{ old('site', $fornecedor->site) }}"
                                            class="form-control campo @if($errors->has('site')) form-control-danger @endif">
                                        @if($errors->has('site'))
                                            <small class="form-text text-danger">{{ $errors->first('site') }}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group @if($errors->has('banco')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">Banco</label>
                                        <input type="text" name="banco" value="{{ old('banco', $fornecedor->banco) }}"
                                            class="form-control campo @if($errors->has('banco')) form-control-danger @endif">
                                        @if($errors->has('banco'))
                                            <small class="form-text text-danger">{{ $errors->first('banco') }}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group @if($errors->has('agencia')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">Agencia</label>
                                        <input type="text" name="agencia" value="{{ old('agencia', $fornecedor->agencia) }}"
                                            class="form-control campo @if($errors->has('agencia')) form-control-danger @endif">
                                        @if($errors->has('agencia'))
                                            <small class="form-text text-danger">{{ $errors->first('agencia') }}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group @if($errors->has('conta_corrente')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">Conta Corrente</label>
                                        <input type="text" name="conta_corrente" value="{{ old('conta_corrente', $fornecedor->conta_corrente) }}"
                                            class="form-control campo @if($errors->has('conta_corrente')) form-control-danger @endif">
                                        @if($errors->has('conta_corrente'))
                                            <small class="form-text text-danger">{{ $errors->first('conta_corrente') }}</small>
                                        @endif
                                    </div>
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="col-sm-12 p-0 m-0">

                    <div class="card col-sm-12">

                        <div class="row mb-3">
                            <div class="col-sm-12 border-bottom bg-light p-3">
                                <label class="form-control-label p-0 m-0">Contato de Referencia</label>
                            </div>
                        </div>

                        <div class="row">

                            <div class="col-sm-4">
                                <div class="form-group @if($errors->has('referencia_relacao')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Relação/Parentesco </label>
                                    <select name="referencia_relacao"
                                        class="form-control campo select2-simples @if($errors->has('referencia_relacao')) form-control-danger @endif">
                                        <option selected disabled>Selecione</option>
                                        @foreach ($referencia_relacoes as $relacao)
                                            <option value="{{ $relacao }}" @if ($fornecedor->referencia_relacao==$relacao)
                                                selected
                                            @endif @if(old('referencia_relacao')==$relacao) selected @endif>{{ $relacao }}</option>
                                        @endforeach
                                        @if (!in_array($fornecedor->referencia_relacao, $referencia_relacoes))
                                            <option value="{{ $fornecedor->referencia_relacao }}">{{ $fornecedor->referencia_relacao }}</option>
                                        @endif
                                    </select>
                                    @if($errors->has('referencia_relacao'))
                                        <small class="form-text text-danger">{{ $errors->first('referencia_relacao') }}</small>
                                    @endif
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group @if($errors->has('referencia_nome')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Nome</label>
                                    <input type="text" name="referencia_nome" value="{{ old('referencia_nome', $fornecedor->referencia_nome) }}"
                                        class="form-control campo @if($errors->has('referencia_nome')) form-control-danger @endif">
                                    @if($errors->has('referencia_nome'))
                                        <small class="form-text text-danger">{{ $errors->first('referencia_nome') }}</small>
                                    @endif
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group @if($errors->has('referencia_telefone')) has-danger @endif">
                                    <label class="form-control-label p-0 m-0">Telefone</label>
                                    <input type="text" name="referencia_telefone" value="{{ old('referencia_telefone', $fornecedor->referencia_telefone) }}"
                                        class="form-control campo telefone @if($errors->has('referencia_telefone')) form-control-danger @endif">
                                    @if($errors->has('referencia_telefone'))
                                        <small class="form-text text-danger">{{ $errors->first('referencia_telefone') }}</small>
                                    @endif
                                </div>
                            </div>

                        </div>

                    </div>

                </div>

                <div class="form-group text-right pb-2">
                    <a href="{{ route('instituicao.fornecedores.index') }}">
                        <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                    </a>
                    <button type="submit" id="salvar" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
                </div>
            </form>
        </div>
    </div>
@endsection


@push('scripts')

    <script type="text/template" id="fornecedor-nao-associado">
        <small class="form-text text-primary fornecedor-nao-registrado fornecedor-alerta">
            <i class="ti-check"></i> Disponível
        </small>
    </script>

    <script type="text/template" id="fornecedor-associado">
        <small class="form-text text-danger fornecedor-registrado fornecedor-alerta">
            <i class="ti-close"></i> Proibido
        </small>
    </script>

    <script>
        $(document).ready(function(){

            function blockButtons() {
                $('button[id="salvar"]').prop('disabled', true);
                $('button[id="adiciona-documento"]').prop('disabled', true);
            }

            function desblockButtons() {
                $('button[id="salvar"]').prop('disabled', false);
                $('button[id="adiciona-documento"]').prop('disabled', false);
            }

            function requestFornecedor(doc) {
                $(`input[name="${doc}"]`).on('change',function (e) {
                    if($(this).data('prev')!=$(this).val()) {
                        if( ($(this).val()).length == 18 || ($(this).val()).length == 14 ) {
                            $.ajax({
                                url: '{{ route("instituicao.fornecedores.getFornecedor") }}',
                                method: 'POST', dataType: 'json',
                                data: { valor: $(this).val(), documento: doc, '_token': '{{ csrf_token() }}' },
                                success: function (response) {
                                    console.log(response);
                                    if (response.status==0) {
                                        /* Se o fornecedor já estiver associada à esta instituição */
                                        $(`.${doc}-campo .fornecedor-alerta`).remove();
                                        $(`.${doc}-campo`).append($($('#fornecedor-associado').html()));
                                        blockButtons()
                                    }
                                    if (response.status==1) {
                                        /* Se o fornecedor não estiver associada à esta instituição */
                                        $(`.${doc}-campo .fornecedor-alerta`).remove();
                                        $(`.${doc}-campo`).append($($('#fornecedor-nao-associado').html()));
                                        desblockButtons()
                                    }
                                }
                            })
                        }
                    } else {
                        $(`.${doc}-campo .fornecedor-alerta`).remove();
                    }
                });
            }

            function personalidade() {
                let personalidade = $('select[name="personalidade"]').val();
                if(personalidade) {
                    $('#campos-fisico-juridico').show();
                    if(personalidade == 1) {
                        $('#campos-pessoa-juridica').hide();
                        $('#personalidade-selecionada').text('Pessoa Física');
                        $('#campos-pessoa-fisica').show();
                        $('.cpf').text('CPF *');
                        $('.nome').text('Nome *')
                    }
                    if(personalidade == 2) {
                        $('#personalidade-selecionada').text('Pessoa Jurídica');
                        $('#campos-pessoa-fisica').show();
                        $('#campos-pessoa-juridica').show();
                        $('.cpf').text('CPF Responsável');
                        $('.nome').text('Nome Responsável')
                    }
                }
            }

            $('.telefone').each(function(){
                $(this).setMask('(99) 99999-9999', {
                    translation: { '9': { pattern: /[0-9]/, optional: false} }
                })
            });

            $('select[name="personalidade"]').on('change', function(){
                personalidade();
            });

            personalidade();
            requestFornecedor('cpf');
            requestFornecedor('cnpj');


            $(".select2-simples").each(function(){
                $(this).select2({
                    tags: true
                });
            });

        });
    </script>
@endpush
