<div id="modalAddPaciente" class="modal fade bs-example-modal-lg" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <span>Ficha Paciente</span>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
           
            <div class="modal-body">
                <div class="card-body">
                    <form action="javascript:void(0)" id="formPaciente">
                        @csrf
                        <input type="hidden" value="1" name="personalidade">
                        <input type="hidden" value="2" name="tipo">
                        @if(!empty($paciente))
                            <input type="hidden" value="{{$paciente->id}}" name="id">
                        @endif
                        <div class="col-sm-12 m-0">
                            <div class="row">    
                                <div class="col-sm-3">
                                    <div class="form-group cpf-campo @if($errors->has('cpf')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">CPF @if(!empty($campos_obg->cpf)) * @endif</label>
                                        <input type="text" name="cpf" alt="cpf" value="{{ old('cpf', $paciente ? $paciente->cpf : '') }}" class="form-control campo @if($errors->has('cpf')) form-control-danger @endif">
                                        @if($errors->has('cpf'))
                                            <small class="form-text text-danger">{{ $errors->first('cpf') }}</small>
                                        @endif
                                        <small class="form-text text-primary pessoa-nao-registrada" style="display: none;">
                                            <i class="ti-check"></i> Disponível
                                        </small>
                                        <small class="form-text text-danger pessoa-registrada" style="display: none;">
                                            <i class="ti-close"></i> Proibido
                                        </small>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group @if($errors->has('nome')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">Nome <span class="text-danger">*</span></label>
                                        <input type="text" name="nome" value="{{ old('nome', $paciente ? $paciente->nome : '') }}" class="form-control campo @if($errors->has('nome')) form-control-danger @endif">
                                        @if($errors->has('nome'))
                                            <small class="form-text text-danger">{{ $errors->first('nome') }}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group @if($errors->has('nascimento')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">Dt Nascimento @if(!empty($campos_obg->nascimento)) <span class="text-danger">*</span> @endif</label>
                                        <input type="date" name="nascimento" value="{{ old('nascimento', $paciente ? $paciente->nascimento : '') }}" class="form-control campo @if($errors->has('nascimento')) form-control-danger @endif" >
                                        @if($errors->has('nascimento'))
                                            <small class="form-text text-danger">{{ $errors->first('nascimento') }}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group @if($errors->has('telefone1')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">Telefone 1 @if(!empty($campos_obg->telefone1)) <span class="text-danger">*</span> @endif</label>
                                        <input type="text" name="telefone1" value="{{ old('telefone1', $paciente ? $paciente->telefone1 : '') }}"
                                            class="form-control campo telefone @if($errors->has('telefone1')) form-control-danger @endif">
                                        @if($errors->has('telefone1'))
                                            <small class="form-text text-danger">{{ $errors->first('telefone1') }}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group @if($errors->has('telefone2')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">Telefone 2 @if(!empty($campos_obg->telefone2)) <span class="text-danger">*</span> @endif</label>
                                        <input type="text" name="telefone2" value="{{ old('telefone2', $paciente ? $paciente->telefone2 : '') }}"
                                            class="form-control campo telefone @if($errors->has('telefone2')) form-control-danger @endif">
                                        @if($errors->has('telefone2'))
                                            <small class="form-text text-danger">{{ $errors->first('telefone2') }}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group @if($errors->has('telefone3')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">Telefone 3 @if(!empty($campos_obg->telefone3)) <span class="text-danger">*</span> @endif</label>
                                        <input type="text" name="telefone3" value="{{ old('telefone3', $paciente ? $paciente->telefone3 : '') }}"
                                            class="form-control campo telefone @if($errors->has('telefone3')) form-control-danger @endif">
                                        @if($errors->has('telefone3'))
                                            <small class="form-text text-danger">{{ $errors->first('telefone3') }}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group @if($errors->has('sexo')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">Sexo @if(!empty($campos_obg->sexo)) <span class="text-danger">*</span> @endif</label>
                                        <select name="sexo" class="form-control campo @if($errors->has('sexo')) form-control-danger @endif">
                                            <option value="">Selecione um sexo</option>
                                            @foreach ($sexo as $item)
                                                <option value="{{ $item }}" @if(old('sexo', $paciente ? $paciente->sexo : '')==$item) selected @endif>{{ App\Pessoa::getSexoTexto($item) }}</option>
                                            @endforeach
                                        </select>
                                        @if($errors->has('sexo'))
                                            <small class="form-text text-danger">{{ $errors->first('sexo') }}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group @if($errors->has('email')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">Email</label>
                                        <input type="text" name="email" value="{{ old('email', $paciente ? $paciente->email : '') }}"
                                            class="form-control campo @if($errors->has('email')) form-control-danger @endif">
                                        @if($errors->has('email'))
                                            <small class="form-text text-danger">{{ $errors->first('email') }}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-sm-2">
                                    <div class="form-group @if($errors->has('identidade')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">Identidade</label>
                                        <input type="text" name="identidade" id="identidade" value="{{ old('identidade', $paciente ? $paciente->identidade : '') }}" class="form-control campo @if($errors->has('identidade')) form-control-danger @endif">
                                        @if($errors->has('identidade'))
                                            <small class="form-text text-danger">{{ $errors->first('identidade') }}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-sm-2">
                                    <div class="form-group @if($errors->has('orgao_expedidor')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">Orgão expedidor</label>
                                        <input type="text" name="orgao_expedidor" value="{{ old('orgao_expedidor', $paciente ? $paciente->orgao_expedidor : '') }}" class="form-control campo @if($errors->has('orgao_expedidor')) form-control-danger @endif">
                                        @if($errors->has('orgao_expedidor'))
                                            <small class="form-text text-danger">{{ $errors->first('orgao_expedidor') }}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-sm-2">
                                    <div class="form-group @if($errors->has('data_emissao')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">Emissão</label>
                                        <input type="date" name="data_emissao" value="{{ old('data_emissao', $paciente ? $paciente->data_emissao : '') }}" class="form-control campo @if($errors->has('data_emissao')) form-control-danger @endif" >
                                        @if($errors->has('data_emissao'))
                                            <small class="form-text text-danger">{{ $errors->first('data_emissao') }}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group @if($errors->has('nome_mae')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">Nome da mãe</label>
                                        <input type="text" name="nome_mae" value="{{ old('nome_mae', $paciente ? $paciente->nome_mae : '') }}" class="form-control campo @if($errors->has('nome_mae')) form-control-danger @endif">
                                        @if($errors->has('nome_mae'))
                                            <small class="form-text text-danger">{{ $errors->first('nome_mae') }}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group @if($errors->has('nome_pai')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">Nome do pai</label>
                                        <input type="text" name="nome_pai" value="{{ old('nome_pai', $paciente ? $paciente->nome_pai : '') }}"
                                            class="form-control campo @if($errors->has('nome_pai')) form-control-danger @endif">
                                        @if($errors->has('nome_pai'))
                                            <small class="form-text text-danger">{{ $errors->first('nome_pai') }}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group @if($errors->has('estado_civil')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">Estado civil</label>
                                        <select name="estado_civil" class="form-control campo @if($errors->has('estado_civil')) form-control-danger @endif">
                                            <option value="">Selecione um estado civil</option>
                                            @foreach ($estado_civil as $item)
                                                <option value="{{ $item }}" @if(old('estado_civil', $paciente ? $paciente->estado_civil : '')==$item) selected @endif>{{ $item }}</option>
                                            @endforeach
                                        </select>
                                        @if($errors->has('estado_civil'))
                                            <small class="form-text text-danger">{{ $errors->first('estado_civil') }}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group @if($errors->has('naturalidade')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">Naturalidade</label>
                                        <input type="text" name="naturalidade" value="{{ old('naturalidade', $paciente ? $paciente->naturalidade : '') }}"
                                            class="form-control campo @if($errors->has('naturalidade')) form-control-danger @endif">
                                        @if($errors->has('naturalidade'))
                                            <small class="form-text text-danger">{{ $errors->first('naturalidade') }}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group @if($errors->has('profissao')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">Profissão</label>
                                        <input type="text" name="profissao" value="{{ old('profissao', $paciente ? $paciente->profissao : '') }}"
                                            class="form-control campo @if($errors->has('profissao')) form-control-danger @endif">
                                        @if($errors->has('profissao'))
                                            <small class="form-text text-danger">{{ $errors->first('profissao') }}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group @if($errors->has('indicacao_descricao')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">Indicação</label>
                                        <input type="text" name="indicacao_descricao" value="{{ old('indicacao_descricao', $paciente ? $paciente->indicacao_descricao : '') }}"
                                            class="form-control campo @if($errors->has('indicacao_descricao')) form-control-danger @endif">
                                        @if($errors->has('indicacao_descricao'))
                                            <small class="form-text text-danger">{{ $errors->first('indicacao_descricao') }}</small>
                                        @endif
                                    </div>
                                </div>

                            </div>

                            <div class="row">

                                <div class="col-sm-3">
                                    <div class="form-group @if($errors->has('cep')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">CEP @if(!empty($campos_obg->endereco)) <span class="text-danger">*</span> @endif</label>
                                        <input type="text" name="cep" alt="cep" value="{{ old('cep', $paciente ? $paciente->cep : '') }}"
                                            class="form-control campo @if($errors->has('cep')) form-control-danger @endif" onblur="buscaCep(this.value)">
                                        @if($errors->has('cep'))
                                            <div class="form-control-feedback">{{ $errors->first('cep') }}</div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group @if($errors->has('estado')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">Estado @if(!empty($campos_obg->endereco)) <span class="text-danger">*</span> @endif</label>
                                        <select class="form-control campo select2-average @if($errors->has('estado')) form-control-danger  @endif" name="estado" id="estado">
                                            <option value="">Selecione</option>
                                            <option value="AC" @if (old('estado', $paciente ? $paciente->estado : '') == 'AC')
                                                selected="selected"
                                            @endif>Acre</option>
                                            <option value="AL" @if (old('estado', $paciente ? $paciente->estado : '') == 'AL')
                                                selected="selected"
                                            @endif>Alagoas</option>
                                            <option value="AP" @if (old('estado', $paciente ? $paciente->estado : '') == 'AP')
                                                selected="selected"
                                            @endif>Amapá</option>
                                            <option value="AM" @if (old('estado', $paciente ? $paciente->estado : '') == 'AM')
                                                selected="selected"
                                            @endif>Amazonas</option>
                                            <option value="BA" @if (old('estado', $paciente ? $paciente->estado : '') == 'BA')
                                                selected="selected"
                                            @endif>Bahia</option>
                                            <option value="CE" @if (old('estado', $paciente ? $paciente->estado : '') == 'CE')
                                                selected="selected"
                                            @endif>Ceará</option>
                                            <option value="DF" @if (old('estado', $paciente ? $paciente->estado : '') == 'DF')
                                                selected="selected"
                                            @endif>Distrito Federal</option>
                                            <option value="GO" @if (old('estado', $paciente ? $paciente->estado : '') == 'GO')
                                                selected="selected"
                                            @endif>Goiás</option>
                                            <option value="ES" @if (old('estado', $paciente ? $paciente->estado : '') == 'ES')
                                                selected="selected"
                                            @endif>Espírito Santo</option>
                                            <option value="MA" @if (old('estado', $paciente ? $paciente->estado : '') == 'MA')
                                                selected="selected"
                                            @endif>Maranhão</option>
                                            <option value="MT" @if (old('estado', $paciente ? $paciente->estado : '') == 'MT')
                                                selected="selected"
                                            @endif>Mato Grosso</option>
                                            <option value="MS" @if (old('estado', $paciente ? $paciente->estado : '') == 'MS')
                                                selected="selected"
                                            @endif>Mato Grosso do Sul</option>
                                            <option value="MG" @if (old('estado', $paciente ? $paciente->estado : '') == 'MG')
                                                selected="selected"
                                            @endif>Minas Gerais</option>
                                            <option value="PA" @if (old('estado', $paciente ? $paciente->estado : '') == 'PA')
                                                selected="selected"
                                            @endif>Pará</option>
                                            <option value="PB" @if (old('estado', $paciente ? $paciente->estado : '') == 'PB')
                                                selected="selected"
                                            @endif>Paraiba</option>
                                            <option value="PR" @if (old('estado', $paciente ? $paciente->estado : '') == 'PR')
                                                selected="selected"
                                            @endif>Paraná</option>
                                            <option value="PE" @if (old('estado', $paciente ? $paciente->estado : '') == 'PE')
                                                selected="selected"
                                            @endif>Pernambuco</option>
                                            <option value="PI" @if (old('estado', $paciente ? $paciente->estado : '') == 'PI')
                                                selected="selected"
                                            @endif>Piauí­</option>
                                            <option value="RJ" @if (old('estado', $paciente ? $paciente->estado : '') == 'RJ')
                                                selected="selected"
                                            @endif>Rio de Janeiro</option>
                                            <option value="RN" @if (old('estado', $paciente ? $paciente->estado : '') == 'RN')
                                                selected="selected"
                                            @endif>Rio Grande do Norte</option>
                                            <option value="RS" @if (old('estado', $paciente ? $paciente->estado : '') == 'RS')
                                                selected="selected"
                                            @endif>Rio Grande do Sul</option>
                                            <option value="RO" @if (old('estado', $paciente ? $paciente->estado : '') == 'RO')
                                                selected="selected"
                                            @endif>Rondônia</option>
                                            <option value="RR" @if (old('estado', $paciente ? $paciente->estado : '') == 'RR')
                                                selected="selected"
                                            @endif>Roraima</option>
                                            <option value="SP" @if (old('estado', $paciente ? $paciente->estado : '') == 'SP')
                                                selected="selected"
                                            @endif>São Paulo</option>
                                            <option value="SC" @if (old('estado', $paciente ? $paciente->estado : '') == 'SC')
                                                selected="selected"
                                            @endif>Santa Catarina</option>
                                            <option value="SE" @if (old('estado', $paciente ? $paciente->estado : '') == 'SE')
                                                selected="selected"
                                            @endif>Sergipe</option>
                                            <option value="TO" @if (old('estado', $paciente ? $paciente->estado : '') == 'TO')
                                                selected="selected"
                                            @endif>Tocantins</option>
                                        </select>
                                        @if($errors->has('estado'))
                                            <small class="form-text text-danger">{{ $errors->first('estado') }}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group @if($errors->has('cidade')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">Cidade @if(!empty($campos_obg->endereco)) <span class="text-danger">*</span> @endif</label>
                                        <input id="cidade" type="text" name="cidade" value="{{ old('cidade', $paciente ? $paciente->cidade : '') }}"
                                            class="form-control campo @if($errors->has('cidade')) form-control-danger @endif">
                                        @if($errors->has('cidade'))
                                            <small class="form-text text-danger">{{ $errors->first('cidade') }}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group @if($errors->has('bairro')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">Bairro @if(!empty($campos_obg->endereco)) <span class="text-danger">*</span> @endif</label>
                                        <input id="bairro" type="text" name="bairro" value="{{ old('bairro', $paciente ? $paciente->bairro : '') }}"
                                            class="form-control campo @if($errors->has('bairro')) form-control-danger @endif">
                                        @if($errors->has('bairro'))
                                            <small class="form-text text-danger">{{ $errors->first('bairro') }}</small>
                                        @endif
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group @if($errors->has('rua')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">Rua @if(!empty($campos_obg->endereco)) <span class="text-danger">*</span> @endif</label>
                                        <input type="text" name="rua" id="rua" value="{{ old('rua', $paciente ? $paciente->rua : '') }}"
                                            class="form-control campo @if($errors->has('rua')) form-control-danger @endif">
                                        @if($errors->has('rua'))
                                            <small class="form-text text-danger">{{ $errors->first('rua') }}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-sm-2">
                                    <div class="form-group @if($errors->has('numero')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">numero @if(!empty($campos_obg->endereco)) <span class="text-danger">*</span> @endif</label>
                                        <input type="text" name="numero" id="numero" value="{{ old('numero', $paciente ? $paciente->numero : '') }}"
                                            class="form-control @if($errors->has('numero')) form-control-danger campo @endif">
                                        @if($errors->has('numero'))
                                            <small class="form-text text-danger">{{ $errors->first('numero') }}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="form-group @if($errors->has('complemento')) has-danger @endif">
                                        <label class="form-control-label p-0 m-0">Complemento</label>
                                        <input type="text" name="complemento" id="complemento" value="{{ old('complemento', $paciente ? $paciente->complemento : '') }}"
                                            class="form-control @if($errors->has('complemento')) form-control-danger campo @endif">
                                        @if($errors->has('complemento'))
                                            <small class="form-text text-danger">{{ $errors->first('complemento') }}</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
        
                        </div>        
                    </form>

                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Fechar</button>
                <button type="button" id="salvar" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
            </div>
        </div>
    </div>
</div>

<script>
    $( document ).ready(function() {
        $('input').setMask();

        function blockButtons() {
            $('#salvar').prop('disabled', true);
        }

        function desblockButtons() {
            $('#salvar').prop('disabled', false);
        }

        $("input[name='cpf']").on('change', function(){
            requestPessoa('cpf');
        })

        function requestPessoa(doc) {
            if($("input[name='"+doc+"'").val().length == 14 ) {
                $.ajax({
                    url: '{{ route("instituicao.pessoas.getPessoa") }}',
                    method: 'POST', dataType: 'json',
                    data: { valor:$("input[name='"+doc+"'").val(), documento: doc, '_token': '{{ csrf_token() }}' },
                    success: function (response) {
                        if (response.status==0) {
                            /* Se a pessoa já estiver associada à esta instituição */
                            $('.pessoa-registrada').css('display', 'block');
                            $('.pessoa-nao-registrada').css('display', 'none');
                            blockButtons()
                        }else if (response.status==1) {
                            /* Se a pessoa não estiver associada à esta instituição */
                            $('.pessoa-registrada').css('display', 'none');
                            $('.pessoa-nao-registrada').css('display', 'block');
                            desblockButtons()
                        }
                    }
                })
            }else{
                $('.pessoa-registrada').css('display', 'none');
                $('.pessoa-nao-registrada').css('display', 'none');
            }
        }

        $('#salvar').on('click', function(e){
            e.preventDefault()
            var formData = new FormData($('#formPaciente')[0]);
            var paciente_id = $("input[name='id']").val() ? $("input[name='id']").val() : 0

            $.ajax("{{route('instituicao.PreInternacoes.salvarPaciente', ['paciente_id' => 'pacienteId'])}}".replace('pacienteId', paciente_id), {
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: () => {
                    $('.loading').css('display', 'block');
                    $('.loading').find('.class-loading').addClass('loader')
                },
                success: function (response) {
                    $.toast({
                        heading: response.title,
                        text: response.text,
                        position: 'top-right',
                        loaderBg: '#ff6849',
                        icon: response.icon,
                        hideAfter: 3000,
                        stack: 10
                    });
                    if(response.icon=="success"){
                        $('#paciente_id').append("<option value = "+ response.dados.id +" selected>" + response.dados.nome +" - "+ response.dados.cpf +"</option>");
                        $('#paciente_id').change();
                        $("#modalAddPaciente").modal('hide');
                        console.log(response.dados);
                    }
                },
                complete: () => {
                    $('.loading').css('display', 'none');
                    $('.loading').find('.class-loading').removeClass('loader') ;
                },
                error: function (response) {
                    if(response.responseJSON.errors){
                        Object.keys(response.responseJSON.errors).forEach(function(key) {
                            $.toast({
                                heading: 'Erro',
                                text: response.responseJSON.errors[key][0],
                                position: 'top-right',
                                loaderBg: '#ff6849',
                                icon: 'error',
                                hideAfter: 9000,
                                stack: 10
                            });

                        });
                    }
                }
            })
        })
    });

    function buscaCep(cep) {

        //Nova variável "cep" somente com dígitos.
        var cep = cep.replace(/\D/g, '');
        console.log(cep);

        //Verifica se campo cep possui valor informado.
        if (cep != "") {

            //Expressão regular para validar o CEP.
            var validacep = /^[0-9]{8}$/;

            console.log(validacep);

            //Valida o formato do CEP.
            if(validacep.test(cep)) {

                //Preenche os campos com "..." enquanto consulta webservice.
                $("#rua").val("");
                $("#bairro").val("");
                $("#cidade").val("");
                $("#estado").val("");

                //Consulta o webservice viacep.com.br/
                $.getJSON("https://viacep.com.br/ws/"+ cep +"/json/?callback=?", function(dados) {

                if (!("erro" in dados)) {
                        console.log(dados)
                        //Atualiza os campos com os valores da consulta.
                        $("#rua").val(dados.logradouro);
                        $("#bairro").val(dados.bairro);
                        $("#cidade").val(dados.localidade);
                        $("#estado").val(dados.uf).change();

                    } //end if.
                    else {
                        //CEP pesquisado não foi encontrado.
                        // limpa_formulário_cep();
                        // $('#fretes_input').css('display', 'none');
                        swal("CEP não encontrado.");

                    }
                    });
            } //end if.
            else {
                //cep é inválido.
                // limpa_formulário_cep();
                swal("Formato de CEP inválido.");
            }
        } //end if.
        else {
            //cep sem valor, limpa formulário.
            // limpa_formulário_cep();
        }
    }

    
    
    
</script>