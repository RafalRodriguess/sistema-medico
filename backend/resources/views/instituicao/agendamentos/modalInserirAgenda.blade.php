<style>
  .desconto-group .btn{
    position: absolute;
    right: 0;
    height: 50%;
    padding: 0;
    width: 3em;
    text-align: center;
    line-height: 1;
    z-index: 999999;
  }

  .desconto-touchspin-up{
    border-radius: 0 4px 0 0;
    top: 0;
  }
  .desconto-touchspin-down{
    border-radius: 0 0 4px 0;
    bottom: 0;
  }
</style>
<div class="modal-header">
  <h4 class="modal-title" id="myLargeModalLabel">Agendamento</h4>
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
</div>

<div class="row modal-body" id="">
  <div class="col-md-3 formulario-historico-agendamento">
    <div id='agendantosPaciente'>
      <h5 class="text-center">Nenhum agendamento registrado</h5>
    </div>
  </div>
  
  <div class="col-md-9 formulario-dados-agendamento">
    <form action="{{ route('instituicao.agendamentos.salvarProcedimentoPaciente') }}" id="formAgendamento" method="post">
      {{-- <div class="modal-header">
        <h4 class="modal-title">Agendamento de consulta</h4>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      </div> --}}
       
          @csrf
            <div class="row">
              <div class="form-group col-md-6">
                  <label for="prestador_agenda" class="control-label">Prestador:</label>
                  <input type="text" class="form-control prestador_agenda" id="prestador_agenda" name="prestador_agenda" value="{{!empty($inst_prestador->nome) ? $inst_prestador->nome : $inst_prestador->prestador->nome}}" readonly>
                  <input type="hidden" class="form-control" id="inst_prest_id" name="inst_prest_id" value="{{$agendaInserir->id}}">
                  <input type="hidden" class="form-control" id="tipo_inserir" name="tipo_inserir" value="{{($verifica_agenda == "unica" && $tipo_inserir == "avulso") ? 'normal' : $tipo_inserir}}">
                  <input type="hidden" class="form-control" id="prestadores_id_inserir" name="prestadores_id_inserir" value="{{$inst_prestador->prestadores_id}}">
                  <input type="hidden" class="form-control" id="inst_prestador_id" name="inst_prestador_id" value="{{$inst_prestador->id}}">
              </div>
              <div class="form-group col-md-3">
                  <label for="data_agenda" class="control-label">Data:</label>
                  <input type="text" class="form-control" id="data_agenda" name="data_agenda" value="{{$data}}" readonly>
              </div>
              <div class="form-group col-md-3">
                <label for="hora_agenda" class="control-label">Hora Inicio/Final:</label>
                <div class="row">
                  <div class="col-md-6">
                    <input type="text" class="form-control" id="hora_agenda" onchange="verificaHorarioDisponivelInicio()" alt='time' name="hora_agenda" value="{{$hora ? $hora : date('H:i')}}" @if(!empty($hora) && $tipo_inserir == "normal") readonly @endif>
                  </div>
                  <div class="col-md-6">
                    <input type="text" class="form-control" id="hora_agenda_final" alt='time' name="hora_agenda_final" value="{{$hora_fim ? $hora_fim : date('H:i')}}">
                  </div>
                </div>
                <input type="hidden" class="form-control" id="proximo_horario_existe" name="proximo_horario_existe" val="0">
                <small>Duração atendimento: {{$total_minutos}} min</small>
              </div>
            </div>

            <hr style="border-top: 1px dashed rgba(0,0,0,.1)!important">

            <div class="item row">
              <input type="hidden" name="lista_paciente" value="@if(!empty($paciente)) {{$lista_id}} @endif">
              
              <div class="col-md-12" style="padding-bottom: 10px;">
                  <label for="paciente_agenda" class="control-label">Paciente: <span class="text-danger">*</span></label>
                  <select class="form-control select2agenda" name="paciente_agenda" id="paciente_agenda" style="width: 100%">
                    @if(!empty($paciente))
                      <option value="{{$paciente->id}}">{{$paciente->nome}} {{($paciente->cpf) ? '- ('.$paciente->cpf.')': '' }}</option>
                    @endif
                    <option value=""></option>
                  </select>
              </div>

              <div class="col-md-6">
                  <label class="form-control-label">Data nascimento: @if(!empty($campos_obg->nascimento)) <span class="text-danger">*</span> @endif</label>
                  <input type="date" class="form-control" name="data_paciente_agenda" id="data_paciente_agenda" value="">
              </div>
              <div class="col-md-6">
                  <label class="form-control-label">Telefone: @if(!empty($campos_obg->telefone1)) <span class="text-danger">*</span> @endif</label>
                  <input type="text" class="form-control telefone" name="telefone_paciente_agenda" id="telefone_paciente_agenda" value="">
              </div>


              <div class="col-md-12 novo-paciente-registro" style="font-size: 15px; margin: 5px; display: none; background: #36ff0340; padding-top: 5px;">
                <b>Bem vindo !</b> Este paciente não está cadastrado no sistema, e um registro será criado para o mesmo.
              </div>

              <div class="card card-body shadow-none paciente-registro m-3" style="display: none;">
                <div class="row">
                  <div class="col-md-4">
                    <label for="cpf" class="control-label">CPF: @if(!empty($campos_obg->cpf)) <span class="text-danger">*</span> @endif</label>
                    <input class="form-control cpf" alt="cpf" name="cpf" id="cpf" style="width: 100%"/>
                  </div>

                  <div class="col-md-3">
                    <label class="form-control-label">Telefone 2: @if(!empty($campos_obg->telefone2)) <span class="text-danger">*</span> @endif</label>
                    <input type="text" alt="phone" class="form-control telefone" name="telefone2" id="telefone2" value="">
                  </div>

                  <div class="col-md-3">
                    <label class="form-control-label">Telefone 3: @if(!empty($campos_obg->telefone3)) <span class="text-danger">*</span> @endif</label>
                    <input type="text" alt="phone" class="form-control telefone" name="telefone3" id="telefone3" value="">
                  </div>

                  <div class="col-md-2">
                    <label class="form-control-label">Sexo @if(!empty($campos_obg->sexo)) <span class="text-danger">*</span> @endif</label>
                    <select name="sexo" value="" class="form-control">
                      <option value="">Selecione</option>
                      @foreach (\App\Pessoa::getSexos() as $item)                            
                        <option value="{{ $item }}" @if(old('sexo')==$item) selected @endif>{{ App\Pessoa::getSexoTexto($item) }}</option>
                      @endforeach
                    </select>
                  </div>

                  <div class="col-md-3">
                    <label class="form-control-label">CEP @if(!empty($campos_obg->endereco)) <span class="text-danger">*</span> @endif</label>
                    <input type="text" name="cep" alt="cep" id="cep" value="" class="form-control ">
                  </div>

                  <div class="col-md-4">
                    <label class="form-control-label">Estado @if(!empty($campos_obg->endereco)) <span class="text-danger">*</span> @endif</label>
                    <select class="form-control" name="estado">
                      <option value="">Selecione</option>
                      <option value="AC" >Acre</option>
                      <option value="AL" >Alagoas</option>
                      <option value="AP">Amapá</option>
                      <option value="AM">Amazonas</option>
                      <option value="BA">Bahia</option>
                      <option value="CE">Ceará</option>
                      <option value="DF">Distrito Federal</option>
                      <option value="GO">Goiás</option>
                      <option value="ES">Espírito Santo</option>
                      <option value="MA">Maranhão</option>
                      <option value="MT">Mato Grosso</option>
                      <option value="MS">Mato Grosso do Sul</option>
                      <option value="MG">Minas Gerais</option>
                      <option value="PA">Pará</option>
                      <option value="PB">Paraiba</option>
                      <option value="PR">Paraná</option>
                      <option value="PE">Pernambuco</option>
                      <option value="PI">Piauí­</option>
                      <option value="RJ">Rio de Janeiro</option>
                      <option value="RN">Rio Grande do Norte</option>
                      <option value="RS">Rio Grande do Sul</option>
                      <option value="RO">Rondônia</option>
                      <option value="RR">Roraima</option>
                      <option value="SP">São Paulo</option>
                      <option value="SC">Santa Catarina</option>
                      <option value="SE">Sergipe</option>
                      <option value="TO">Tocantins</option>
                    </select>
                  </div>

                  <div class="col-sm-5">
                    <label class="form-control-label">Cidade @if(!empty($campos_obg->endereco)) <span class="text-danger">*</span> @endif</label>
                    <input id="cidade" type="text" name="cidade" value="" class="form-control">
                  </div>

                  <div class="col-sm-2">                    
                    <label class="form-control-label">Bairro @if(!empty($campos_obg->endereco)) <span class="text-danger">*</span> @endif</label>
                    <input id="bairro" type="text" name="bairro" value=""
                        class="form-control">                        
                  </div>

                  <div class="col-sm-4">                    
                    <label class="form-control-label">Rua @if(!empty($campos_obg->endereco)) <span class="text-danger">*</span> @endif</label>
                    <input type="text" name="rua" id="rua" value="" class="form-control">                       
                  </div>

                  <div class="col-sm-2">
                    <label class="form-control-label">Numero @if(!empty($campos_obg->endereco)) <span class="text-danger">*</span> @endif</label>
                    <input type="text" name="numero" id="numero" value="" class="form-control">
                  </div>

                  <div class="col-sm-4">
                    <label class="form-control-label">Complemento</label>
                    <input type="text" name="complemento" id="complemento" value="" class="form-control">
                  </div>
                </div>

                <div class="col-sm-6">
                  <label class="form-control-label">E-mail @if(!empty($campos_obg->email)) <span class="text-danger">*</span> @endif</label>
                  <input type="text" name="email" id="email" value="" class="form-control">
                </div>
              </div>

              <div class="col-sm-12 pt-3">
                <div class="card shadow-none p-3 mb-0">
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="acompanhante" value="1" @if(old('acompanhante')=="1") checked @endif id="acompanhanteCheck">
                    <label class="form-check-label" for="acompanhanteCheck">Acompanhante</label>
                  </div>

                  <div class="col-sm-12 acompanhante" style="display: none">
                    <div class='row py-2'>
                      <div class="col-sm-4">
                        <div class="form-group @if($errors->has('acompanhante_relacao')) has-danger @endif">
                            <label class="form-control-label p-0 m-0">Relação/Parentesco</label>
                            <select name="acompanhante_relacao" id="acompanhante_relacao" class="form-control select2-simples @if($errors->has('acompanhante_relacao')) form-control-danger @endif">
                                <option selected disabled>Selecione</option>
                                @foreach ($referencia_relacoes as $relacao)
                                    <option value="{{ $relacao }}" @if(old('acompanhante_relacao')==$relacao) selected @endif>{{ $relacao }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('acompanhante_relacao'))
                                <small class="form-text text-danger">{{ $errors->first('acompanhante_relacao') }}</small>
                            @endif
                        </div>
                      </div>

                      <div class="col-sm-8">
                        <div class="form-group @if($errors->has('acompanhante_nome')) has-danger @endif">
                            <label class="form-control-label p-0 m-0">Nome</label>
                            <input type="text" name="acompanhante_nome" id="acompanhante_nome" value="{{ old('acompanhante_nome') }}"
                                class="form-control campo @if($errors->has('acompanhante_nome')) form-control-danger @endif">
                            @if($errors->has('acompanhante_nome'))
                                <small class="form-text text-danger">{{ $errors->first('acompanhante_nome') }}</small>
                            @endif
                        </div>
                      </div>

                      <div class="col-sm-4">
                        <div class="form-group @if($errors->has('acompanhante_telefone')) has-danger @endif">
                            <label class="form-control-label p-0 m-0">Telefone</label>
                            <input type="text" name="acompanhante_telefone" id="acompanhante_telefone" alt="phone" value="{{ old('acompanhante_telefone') }}"
                                class="form-control campo telefone @if($errors->has('acompanhante_telefone')) form-control-danger @endif">
                            @if($errors->has('acompanhante_telefone'))
                                <small class="form-text text-danger">{{ $errors->first('acompanhante_telefone') }}</small>
                            @endif
                        </div>
                      </div>

                      <div class="col-sm-6">
                        <div class="form-group @if($errors->has('cpf_acompanhante')) has-danger @endif">
                            <label class="form-control-label p-0 m-0">Cpf</label>
                            <input type="text" name="cpf_acompanhante" id="cpf_acompanhante" alt="cpf" value="{{ old('cpf_acompanhante') }}"
                                class="form-control campo cpf @if($errors->has('cpf_acompanhante')) form-control-danger @endif">
                            @if($errors->has('cpf_acompanhante'))
                                <small class="form-text text-danger">{{ $errors->first('cpf_acompanhante') }}</small>
                            @endif
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div> 
              
              <div class="col-sm-12 pt-3 carteirinha_paciente" style="display: none">
                <label for="carteirinha_id" class="control-label">Carteirinha: 
                  @can('habilidade_instituicao_sessao', "cadastrar_carteirinha")
                    <a class="mytooltip carteirinha-nova" href="javascript:void(0)" target="_blank">
                      <span alt="default" class="fas fa-plus-circle">
                    </a>
                    <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="" data-original-title="Adicionar carterinha paciente"></i>
                    </span>
                  @endcan
                </label>
                <select class="form-control select2carteirinhaagenda" name="carteirinha_id" id="carteirinha_id" style="width: 100%">
                  <option value=""></option>
                </select>
              </div>

              <div class="col-sm-12 pt-3">
                <label for="compromisso_id" class="control-label">Etiquetas: </label>
                <select class="form-control select2ModalInserir" name="compromisso_id" id="compromisso_id" style="width: 100%">
                  <option value="">Nenhuma</option>
                  @foreach ($compromissos as $item)
                      <option value="{{$item->id}}">{{$item->descricao}}</option>
                  @endforeach
                </select>
              </div>


            </div>

            <hr style="border-top: 1px dashed rgba(0,0,0,.1)!important">

            <div class="convenio_procedimentos_agendar row">
              @include('instituicao.agendamentos.procedimentos_includes')
              @can('habilidade_instituicao_sessao', 'visualizar_valor_procedimento')
                <div class="form-group col-md-12 add-class" >
                  <span alt="default" class="add-convenio fas fa-plus-circle">
                      <a class="mytooltip" href="javascript:void(0)">
                          <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="" data-original-title="Adicionar convenio procedimento"></i>
                      </a>
                  </span>
                </div>
              @endcan
              <div class="col-md-9"></div>
              <div class="form-group col-md-3">
                  <label class="form-control-label">Total</label>
                  <input class="form-control" alt="decimal" type="text" readonly id="total_procedimentos_agendar" name="total_procedimentos_agendar">
              </div>
            </div>
            <div class="col-sm-12 my-3">
              <div class="card shadow-none p-3">
                <div class="col-sm">
                  <div class="form-group @if($errors->has('obs')) has-danger @endif">
                      <label class="form-control-label p-0 m-0">Observações</label>
                      <textarea rows='3' name="obs" id="obs" value="{{ old('obs') }}" class="form-control campo"></textarea>                        
                  </div>
                </div>

                <div class="col-sm">
                  <div class="form-group @if($errors->has('solicitante')) has-danger @endif">
                      <label class="form-control-label p-0 m-0">Prestador Solicitante</label>
                      <select class="form-control select2solicitante" name="solicitante_agenda" id="solicitante_agenda" style="width: 100%">
                        <option value=""></option>
                      </select>
                  </div>
                </div>

                {{-- INSTITUIÇÃO E PRESTADOR DEVEM ESTAR HABILITADO PARA REALIZAÇÃO DO TELEATENDIMENTO --}}
                @if($instituicao->telemedicina_integrado == 1 && $inst_prestador->telemedicina_integrado == 1)

                <div class="col-sm">
                  <div class="form-check form-check-inline" style="">
                      <input type="checkbox" class="form-check-input medico-checkbox"
                          id="teleatendimento" name="teleatendimento" value="1" @if(old('teleatendimento')=="1") checked @endif>
                      <label class="form-check-label" for="teleatendimento">Teleatendimento</label>
                  </div>
                </div>

                @endif


              </div>
            </div>
      
    </form>
  </div>
</div>

<div class="modal-footer" style="width: 100%">
  <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Fechar</button>

  <button type="button" class="btn btn-secondary waves-effect waves-light salvarAgendamentoInserir" style="background-color: #009688;border:#009688;color: #fff;">
    <span class="btn-label"><i class="fa fa-check"></i></span>Salvar agendamento
  </button>
  {{-- <button type="submit" class="btn btn-danger waves-effect waves-light">Salvar</button> --}}
</div>

{{-- Habilidade asaplan jogar direto no script posterior --}}
@if (\Gate::check('habilidade_instituicao_sessao', 'agendar_paciente_debito_asaplan'))
 <input type="hidden" id="agendar_paciente_debito_asaplan" value="1">
@else
 <input type="hidden" id="agendar_paciente_debito_asaplan" value="0">
@endif

<script>
  var procedimento_tempo_maior = 0;
  var extra_tempo_maior = 0;
  var tempo_consulta = "{{$total_minutos}}";
  var desconto_maximo = "{{$desconto_maximo}}";

  $(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip()
    $("#hora_agenda").setMask();
    $("#hora_agenda_final").setMask();
    $(".select2ModalInserir").select2();
    
    setTimeout(() => {
      $(".desc_juros_multa").setMask();
      $(".porcentagem_desconto").setMask();
      $(".desc_juros_multa").css('text-align', 'left');
      if($(".select2agenda").val()){
          $.ajax({
            url: "{{route('instituicao.agendamentos.getPaciente', ['pessoa' => 'pessoa_id'])}}".replace('pessoa_id', $(".select2agenda").val()),
            type: "GET",
            data: {
              "_token": "{{ csrf_token() }}"
              },
            datatype: "json",
            processData: false,
            contentType: false,
            success: function(result) {
              $(".paciente-registro").css('display', 'block');
              $("#acompanhante_relacao").val(result.referencia_relacao);
              $("#acompanhante_nome").val(result.referencia_nome);
              $("#acompanhante_telefone").val(result.referencia_telefone);
              $("#telefone_paciente_agenda").val(result.telefone1);
              $("#data_paciente_agenda").val(result.nascimento);
              $("#cpf").val(result.cpf);
              $("#telefone2").val(result.telefone2);
              $("#telefone3").val(result.telefone3);
              $("#sexo").val(result.sexo);
              $("#email").val(result.email);
              $("#cep").val(result.cep);
              $("#estado").val(result.estado);
              $("#cidade").val(result.cidade);
              $("#bairro").val(result.bairro);
              $("#rua").val(result.rua);
              $("#numero").val(result.numero);
              $("#complemento").val(result.complemento);
              $(".carteirinha_paciente").css('display', 'block');
              $(".carteirinha-nova").attr("href", "{{route('instituicao.carteirinhas.create', ['pessoa' => 'pessoa_id'])}}".replace('pessoa_id', data.id))
            }
        });
        $(".novo-paciente-registro").css('display', 'none');
      }
    }, 500);
  })

  $('.convenio_procedimentos_agendar').on('click', '.desconto-touchspin-up', function(e){
      var desc = retornaFormatoValor($(this).parents('.desconto_input').find('.desc_juros_multa').val());
      var element = $(this).parents('.desconto_input').find('.desc_juros_multa');
      if( desc < 0){
          desc = parseFloat(desc) * -1;
          element.val(desc.toFixed(2)).setMask()
        }else if(desc == 0){
          element.val('0').setMask()
        }
        $(".desc_juros_multa").css('text-align', 'left');
      calculaValorNovo(this);
  })
  
  $('.convenio_procedimentos_agendar').on('click', '.desconto-touchspin-down', function(e){
      var desc = retornaFormatoValor($(this).parents('.desconto_input').find('.desc_juros_multa').val());
      var element = $(this).parents('.desconto_input').find('.desc_juros_multa');
      if( desc > 0){
          var total = $(element).parents('.item-convenio-agendar').find('.valor_procedimento_agendar_inserir').attr('data-valor')
          desc = parseFloat(desc) * -1;
          var desconto_porcento = (desc*100)/total;

          if((desconto_porcento*-1) > desconto_maximo){
            Swal.fire("Não permitido!", "Porcentual de desconto maximo permitido é: "+desconto_maximo+"%", "error")
            $(element).val(0)
            desc = 0;
            $(element).parents('.item-convenio-agendar').find('.porcentagem_desconto').val(desconto_porcento.toFixed(2)).setMask()
          }

          element.val(desc.toFixed(2)).setMask()
        }else if(desc == 0){
          element.val('-0').setMask()
        }
        $(".desc_juros_multa").css('text-align', 'left');
      calculaValorNovo(this);
  })

  var quantidade_convenio = 0;
  $(".mytooltip").tooltip();

  $('.convenio_procedimentos_agendar').on('click', '.add-convenio', function(){
      addConvenioAgendar();
      $('[data-toggle="tooltip"]').tooltip()
  });

  
  $("#acompanhanteCheck").on("change", function(e){
    if($("#acompanhanteCheck").is(":checked")){
      $('.acompanhante').css('display', 'block');
    }else{
      $('.acompanhante').css('display', 'none');
      $("#acompanhante_relacao").val("");
      $("#acompanhante_nome").val("");
      $("#acompanhante_telefone").val("");
      $("#cpf_acompanhante").val("");
    }
  })

  $(".salvarAgendamentoInserir").on('click', function(e){
    e.preventDefault()
    if($("#proximo_horario_existe").val() == 1){
      Swal.fire({
            title: "Alerta!",
            text: 'Há um proximo atendimento marcado e o atual excede o limite de atendimento, deseja salvar mesmo assim ?',
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            cancelButtonText: "Não, cancelar!",
            confirmButtonText: "Sim, confirmar!",
        }).then(function(result) {
            if(result.value){
              $("#formAgendamento").submit();
            }
        })
    }else{
      $("#formAgendamento").submit();
    }
  })

  $("#formAgendamento").on('submit', function(e){
    e.preventDefault()

      var formData = new FormData($(this)[0]);

      $.ajax("{{route('instituicao.agendamentos.salvarProcedimentoPaciente')}}", {
          method: "POST",
          data: formData,
          processData: false,
          contentType: false,
          beforeSend: () => {
              $('.loading').css('display', 'block');
              $('.loading').find('.class-loading').addClass('loader')
          },
          success: function (result) {
            // console.log(result.icon)
            if(result.icon == "error"){
              $.toast({
                heading: result.title,
                text: result.text,
                position: 'top-right',
                loaderBg: '#ff6849',
                icon: result.icon,
                hideAfter: 5000,
                stack: 10
              });
            }else{
              $.toast({
                heading: 'Sucesso',
                text: 'Agendamento cadastrado com sucesso',
                position: 'top-right',
                loaderBg:'#ff6849',
                icon: 'success',
                hideAfter: 3000,
                stack: 10
              });
              $("#modalInserirAgenda").modal('hide');
              callRenderPage();
              if($("#semanal-click").hasClass('carregado')){
                callRenderSemanal(result.data);
              }
            }
            
            
            
          },
          complete: () => {
            $('.loading').css('display', 'none');
            $('.loading').find('.class-loading').removeClass('loader') 
          },
          error: function (response) {
            $('.loading').css('display', 'none');
            $('.loading').find('.class-loading').removeClass('loader')
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

  $('.telefone').each(function(){
      $(this).setMask('(99) 99999-9999', {
          translation: { '9': { pattern: /[0-9]/, optional: false} }
      })
  });

  $(".cpf").setMask();

  $('#paciente_agenda').on('change',function(){
    
    var id = $('#paciente_agenda').val();
    if(id != ''){
      $.ajax({
        url: "{{route('instituicao.agendamentos.getConvenio', ['pessoa' => 'pessoa_id'])}}".replace('pessoa_id', id),
        type: "GET",
        data: {
          "_token": "{{ csrf_token() }}"
        },
        datatype: "json",
        processData: false,
        contentType: false,
        success: function(result) {
          
          if(Object.keys(result).length === 0){
            console.log("retorno vazio de convenio")
          }else{
            
            Swal.fire({
                title: "Atenção",
                text: "Paciente possui convenio cadastrado, deseja utilizar?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Sim",
                cancelButtonText: "Não",
            }).then(function(retorno) {
              
              // $('#convenio_paciente_carteirinha option').each(function() {
                
              //   if($(this).val() == result.convenio_id) {
              //     console.log("aqui")
              //     $(this).attr('selected', true);
              //   }
              // })
              if(retorno.value){
                // console.log('aqui')
                $('#convenio_paciente_carteirinha').val(result.convenio_id).change()
              }
            })
          }
        }
      });

      $.ajax({
        url: "{{route('instituicao.agendamentos.getAgendamentos', ['pessoa' => 'pessoa_id'])}}".replace('pessoa_id', id),
        type: "GET",
        data: {
          "_token": "{{ csrf_token() }}"
        },
        datatype: "json",
        processData: false,
        contentType: false,
        success: function(result) {
          $("#agendantosPaciente").html(result)
        }
      });

      /* INTEGRAÇÃO ASAPLAN */
      var integracao_asaplan = "{{ $instituicao->integracao_asaplan }}";
      
      var permissao_agendamento_com_debitos =  $("#agendar_paciente_debito_asaplan").val();

      if(integracao_asaplan == 1){

        $.ajax({
          url: "{{route('instituicao.agendamentos.getInfoAsaplan', ['pessoa' => 'pessoa_id'])}}".replace('pessoa_id', id),
          type: "GET",
          data: {
            "_token": "{{ csrf_token() }}"
          },
          datatype: "json",
          processData: false,
          contentType: false,
          success: function(result) {
                if(result == 2){
                   alert('Plano em atraso, favor regulaziar pendência!');

                   if(permissao_agendamento_com_debitos == 0){
                    $("#paciente_agenda").val("").change();
                   }

                }else if(result == 3){
                    alert('Plano cancelado, favor reativar plano!');

                    if(permissao_agendamento_com_debitos == 0){
                     $("#paciente_agenda").val("").change();
                    }
                    
                }
          }
      });

      }

      /* FIM INTEGRAÇÃO ASAPLAN */

    }
  });

  $(".select2agenda").select2({
    placeholder: "Pesquise por nome ou cpf",
    allowClear: true,
    minimumInputLength: 3,
    tags: true,
    createTag: function (params) {
      var term = $.trim(params.term);

      return {
        id: term,
        text: term + ' (Novo Paciente)',
        newTag: true
      }
    },
    language: {
      searching: function () {
        return 'Buscando paciente (aguarde antes de selecionar)…';
      },
      
      inputTooShort: function (input) {
        return "Digite " + (input.minimum - input.input.length)+ " caracteres para pesquisar"; 
      },
    },    
    
    ajax: {
        url:"{{route('instituicao.agendamentos.getPacientes')}}",
        dataType: 'json',
        delay: 100,

        data: function (params) {
          return {
            q: params.term || '', // search term
            page: params.page || 1
          };
        },
        processResults: function (data, params) {
          params.page = params.page || 1;
          return {
            results: _.map(data.results, item => ({
                id: Number.parseInt(item.id),
                text: `${item.nome} ${(item.cpf) ? '- ('+item.cpf+')': ''} ${(item.id) ? '- #'+item.id+'': ''} ${(item.telefone1) ? '- Telefone '+item.telefone1+'': ''} ${(item.asaplan_nome_titular) ? '- Titular '+item.asaplan_nome_titular+'': ''}`    ,
              })),
            pagination: {
                more: data.pagination.more
            }
          };
        },
        cache: true
    },

  }).on('select2:select', function (e) {
    var data = e.params.data;
    $("#carteirinha_id").val("").change()
    if(e.params.data.newTag){
      $(".novo-paciente-registro").css('display', 'block');
      $(".paciente-registro").css('display', 'block');
      $("#telefone_paciente_agenda").val('');
      $("#cpf").val('');
      $("#telefone2").val('');
      $("#telefone3").val('');
      $("#sexo").val('');
      $("#cep").val('');
      $("#estado").val('');
      $("#cidade").val('');
      $("#bairro").val('');
      $("#rua").val('');
      $("#numero").val('');
      $("#complemento").val('');
      $("#telefone_paciente_agenda").val('');
      $("#data_paciente_agenda").val('');
      $("#acompanhante_relacao").val('');
      $("#acompanhante_nome").val('');
      $("#acompanhante_telefone").val('');
      $("#cpf_acompanhante").val('');
      $(".carteirinha_paciente").css('display', 'none');
    }else{
      $.ajax({
          url: "{{route('instituicao.agendamentos.getPaciente', ['pessoa' => 'pessoa_id'])}}".replace('pessoa_id', data.id),
          type: "GET",
          data: {
            "_token": "{{ csrf_token() }}"
            },
          datatype: "json",
          processData: false,
          contentType: false,
          success: function(result) {
            $(".paciente-registro").css('display', 'block');
            $("#acompanhante_relacao").val(result.referencia_relacao);
            $("#acompanhante_nome").val(result.referencia_nome);
            $("#acompanhante_telefone").val(result.referencia_telefone);
            $("#telefone_paciente_agenda").val(result.telefone1);
            $("#data_paciente_agenda").val(result.nascimento);
            $("#cpf").val(result.cpf);
            $("#telefone2").val(result.telefone2);
            $("#telefone3").val(result.telefone3);
            $("#sexo").val(result.sexo);
            $("#cep").val(result.cep);
            $("#estado").val(result.estado);
            $("#cidade").val(result.cidade);
            $("#bairro").val(result.bairro);
            $("#rua").val(result.rua);
            $("#numero").val(result.numero);
            $("#complemento").val(result.complemento);
            $(".carteirinha_paciente").css('display', 'block');
            $(".carteirinha-nova").attr("href", "{{route('instituicao.carteirinhas.create', ['pessoa' => 'pessoa_id'])}}".replace('pessoa_id', data.id))
          }
      });
      $(".novo-paciente-registro").css('display', 'none');
      
    }
  })

  $(".select2carteirinhaagenda").select2({
    placeholder: "Pesquise por carteirinha",
    allowClear: true,
    minimumInputLength: 3,
    language: {
      searching: function () {
        return 'Buscando carteirinha (aguarde antes de selecionar)…';
      },
      
      inputTooShort: function (input) {
        return "Digite " + (input.minimum - input.input.length)+ " caracteres para pesquisar"; 
      },
    },    
    
    ajax: {
        url:"{{route('instituicao.agendamentos.getCarteirinhas')}}",
        dataType: 'json',
        delay: 100,

        data: function (params) {
          return {
            q: params.term || '', // search term
            page: params.page || 1,
            pessoa: $("#paciente_agenda option:selected").val()
          };
        },
        processResults: function (data, params) {
          params.page = params.page || 1;
          return {
            results: _.map(data.results, item => ({
                id: Number.parseInt(item.id),
                text: `${item.carteirinha} (${item.convenio[0].nome})`,
              })),
            pagination: {
                more: data.pagination.more
            }
          };
        },
        cache: true
    },

  })

  $(".select2solicitante").select2({
    placeholder: "Pesquise por nome",
    allowClear: true,
    minimumInputLength: 3,
    tags: true,
    createTag: function (params) {
      var term = $.trim(params.term);

      return {
        id: term,
        text: term + ' (Novo Prestador)',
        newTag: true
      }
    },
    language: {
      searching: function () {
        return 'Buscando solicitante (aguarde antes de selecionar)…';
      },
      
      inputTooShort: function (input) {
        return "Digite " + (input.minimum - input.input.length)+ " caracteres para pesquisar"; 
      },
    },    
    
    ajax: {
        url:"{{route('instituicao.agendamentos.getSolicitantes')}}",
        dataType: 'json',
        delay: 100,

        data: function (params) {
          return {
            q: params.term || '', // search term
            page: params.page || 1
          };
        },
        processResults: function (data, params) {
          params.page = params.page || 1;
          return {
            results: _.map(data.results, item => ({
                id: Number.parseInt(item.id),
                text: item.nome,
              })),
            pagination: {
                more: data.pagination.more
            }
          };
        },
        cache: true
    },

  }).on('select2:select', function (e) {
    var data = e.params.data;
    if(e.params.data.newTag){
      
    }
  })

  
  $(".selectfild2_convenio").select2();
  $(".valor_mask").setMask();
  

  function addConvenioAgendar(){
      quantidade_convenio++;

      $($('#item-convenio-agendar').html()).insertBefore(".add-class");

      $('.mask_item').setMask();
      $('.mask_item').removeClass('mask_item');
      $(".selectfild2").select2();

      $("[name^='convenio[#]']").each(function(index, element) {
        const name = $(element).attr('name');

        $(element).attr('name', name.replace('#',quantidade_convenio));
      })

      
      setTimeout(() => {
        $(".desc_juros_multa").setMask();
        $(".porcentagem_desconto").setMask();
        $(".desc_juros_multa").css('text-align', 'left');
      }, 500);
  }

  $('.convenio_procedimentos_agendar').on('click', '.item-convenio-agendar .remove-convenio', function(e){
      e.preventDefault()

      $(e.currentTarget).parents('.item-convenio-agendar').remove();
      totalProcedimentosAgendar()
      verificaMaiorTempoProcedimentos()
      if ($('.convenio_procedimentos_agendar').find('.item-convenio-agendar').length == 0) {
          addConvenioAgendar();
      }

  });

  function getProcedimentos(element){
    var id = $(element).val()
    var prestador_id =  $('#prestadores_id_inserir').val()
    var options = $(element).parents(".item-convenio-agendar").find('.procedimentos');

    $.ajax({
        url: "{{route('instituicao.agendamentos.getProcedimentos', ['convenio' => 'convenio_id', 'prestador' => 'prestador_id'])}}".replace('convenio_id', id).replace('prestador_id', prestador_id),
        type: "GET",
        data: {
          "_token": "{{ csrf_token() }}",
        },
        datatype: "json",
        processData: false,
        contentType: false,
        beforeSend: () => {
            $('.loading').css('display', 'block');
            $('.loading').find('.class-loading').addClass('loader')
        },
        success: function(result) {
          if(result != null){
            procedimentos = result
            $(element).parents(".item-convenio-agendar").find('.valor_procedimento_agendar_inserir').val(' ');
            $(element).parents(".item-convenio-agendar").find('.valor_procedimento_agendar_inserir').setMask();
            options.prop('disabled', false);
            options.find('option').filter(':not([value=""])').remove();
            

            $.each(procedimentos, function (key, value) {
                        // $('<option').val(value.id).text(value.Nome).appendTo(options);
                options.append('<option value='+value.instituicao_procedimentos_convenios[0].pivot.id+' data-valor='+value.instituicao_procedimentos_convenios[0].pivot.valor+' data-exige-qtd='+value.procedimento.exige_quantidade+' data-cobrar='+value.procedimento.n_cobrar_agendamento+' data-tempo='+value.procedimento.duracao_atendimento+' data-compromisso='+value.procedimento.compromisso_id+'>'+value.procedimento.descricao+'</option>')
                //options += '<option value="' + key + '">' + value + '</option>';
            });

            $(element).parents(".item-convenio-agendar").find('.btnSelectProcedimentos').attr('data-convenio', id);

            if(options.data('val')){
              options.val(options.data('val')).change();
            }

            if(options.data('valor')){
              $(element).parents(".item-convenio-agendar").find('.valor_procedimento_agendar_inserir').val(options.data('valor')).change();
            }
          }
        },
        complete: () => {
          $('.loading').css('display', 'none');
          $('.loading').find('.class-loading').removeClass('loader')
        }
    });

  }

  $('.convenio_procedimentos_agendar').on('click', '.item-convenio-agendar .btnSelectProcedimentos', function(e){
    e.preventDefault();

    var convenio_id = $(this).attr('data-convenio');
    var prestador_id =  $('#prestadores_id_inserir').val()
    if(convenio_id){
      $.ajax({
          url: "{{route('instituicao.agendamentos.getSelectProcedimentos', ['convenio' => 'convenio_id', 'prestador' => 'prestador_id'])}}".replace('convenio_id', convenio_id).replace('prestador_id', prestador_id),
          type: "GET",
          data: {
            "_token": "{{ csrf_token() }}",
          },
          datatype: "json",
          processData: false,
          contentType: false,
          beforeSend: () => {
              $('.loading').css('display', 'block');
              $('.loading').find('.class-loading').addClass('loader')
          },
          success: function(result) {
            $('#modalPacotesProcedimentos .modal-content').html(result);
            $('#modalInserirAgenda').modal('hide');
            $('#modalPacotesProcedimentos').modal('show');
          },
          complete: () => {
            $(this).parents('.item-convenio-agendar').remove();
            totalProcedimentosAgendar();

            $('.loading').css('display', 'none');
            $('.loading').find('.class-loading').removeClass('loader')
          }
      });
    }
  })

  function getValorProcedimentoAgendar(element){
    valor = $('option:selected', element).attr('data-valor');
    var quantidade = $('option:selected', element).attr('data-exige-qtd');
    var cobrar = $('option:selected', element).attr('data-cobrar');

    if(quantidade == 'false'){
      $(element).parents(".item-convenio-agendar").find('.exige_quantidade_agendar').css('display', 'none');
    }else{
      $(element).parents(".item-convenio-agendar").find('.exige_quantidade_agendar').css('display', 'block');
    }

    if(cobrar == 'true'){
      valor = 0
    }
    
    $(element).parents(".item-convenio-agendar").find('.qtd_procedimento_agendar').val(1);
    $(element).parents(".item-convenio-agendar").find('.valor_procedimento_agendar_inserir').val(valor);
    $(element).parents(".item-convenio-agendar").find('.valor_procedimento_agendar_inserir').attr('data-valor', valor);
    $(element).parents(".item-convenio-agendar").find('.valor_procedimento_agendar_inserir').setMask();

    //VERIFICA SE EXISTE ETIQUETA
    var compromisso_id = $('option:selected', element).attr('data-compromisso');
    if(compromisso_id != 'null'){
      var compromisso_campo = $("#compromisso_id").val();
      //VERIFICA SE EXISTE ETIQUETA SELECIONADA E SETA OU PERGUNTA SE QUER TROCAR
      if(compromisso_campo == ''){
        $("#compromisso_id").val(compromisso_id).change();
      }else if(compromisso_campo != compromisso_id){
        Swal.fire({
            title: "Alerta!",
            text: 'Deseja mudar o tipo da etiqueta ?',
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            cancelButtonText: "Não, cancelar!",
            confirmButtonText: "Sim, confirmar!",
        }).then(function(result) {
            if(result.value){
              $("#compromisso_id").val(compromisso_id).change();
            }
        })
      }
    }

    // totalProcedimentosAgendar()

    //CALCULA TEMPO DE ATENDIMENTO
    verificaMaiorTempoProcedimentos()
    calculaValorNovo(element)

  }

  function getNovoValorAgendar(element){
    var valor_procedimento_agendar_inserir = retornaFormatoValor($(element).parents(".item-convenio-agendar").find('.procedimentos option:selected').attr('data-valor'))
    var quantidade_procedimento_inserir = $(element).val();

    var valor_novo = quantidade_procedimento_inserir * valor_procedimento_agendar_inserir;
    
    $(element).parents(".item-convenio-agendar").find('.valor_procedimento_agendar_inserir').val(valor_novo);
    $(element).parents(".item-convenio-agendar").find('.valor_procedimento_agendar_inserir').setMask();
    // totalProcedimentosAgendar();
    calculaValorNovo(element)
  }

  function calculaValorNovoPorcento(element){
    var total = $(element).parents('.item-convenio-agendar').find('.valor_procedimento_agendar_inserir').attr('data-valor')
    var desconto = $(element).val()

    if(desconto > desconto_maximo){
      Swal.fire("Não permitido!", "Porcentual de desconto maximo permitido é: "+desconto_maximo+"%", "error")
      $(element).val(0).setMask()
      desconto = 0;
    }
    var desconto_real = (total*desconto)/100;

    if(Math.sign(retornaFormatoValor($(element).parents('.item-convenio-agendar').find('.desc_juros_multa').val()) < 0) || retornaFormatoValor($(element).parents('.item-convenio-agendar').find('.desc_juros_multa').val()) == 0){
      $(element).parents('.item-convenio-agendar').find('.desc_juros_multa').val((desconto_real*-1).toFixed(2)).setMask()
    }else{
      $(element).parents('.item-convenio-agendar').find('.desc_juros_multa').val(desconto_real.toFixed(2)).setMask()
    }
    $(element).parents('.item-convenio-agendar').find('.desc_juros_multa').css('text-align', 'left')
    calculaValorNovo(element);
  }

  function calculaValorNovoReal(element){
    var total = $(element).parents('.item-convenio-agendar').find('.valor_procedimento_agendar_inserir').attr('data-valor')
    var desconto = retornaFormatoValor($(element).val())

    var desconto_porcento = (desconto*100)/total;

    if((desconto_porcento*-1) > desconto_maximo){
      Swal.fire("Não permitido!", "Porcentual de desconto maximo permitido é: "+desconto_maximo+"%", "error")
      $(element).val(0)
      desconto = 0;
      desconto_porcento = 0;
    }

    $(element).parents('.item-convenio-agendar').find('.porcentagem_desconto').val(desconto_porcento.toFixed(2)).setMask()
    calculaValorNovo(element);
  }

  function calculaValorNovo(element){
    var valor = $(element).parents('.item-convenio-agendar').find('.valor_procedimento_agendar_inserir').attr('data-valor');
    var quantidade = $(element).parents('.item-convenio-agendar').find('.qtd_procedimento_agendar').val();
    var desconto = 0;
    if($(element).parents('.item-convenio-agendar').find('.desc_juros_multa').length > 0){
      desconto = retornaFormatoValor($(element).parents('.item-convenio-agendar').find('.desc_juros_multa').val());
    }

    var total = (parseFloat(valor)*quantidade) + parseFloat(desconto);
    $(element).parents('.item-convenio-agendar').find('.valor_procedimento_agendar_inserir').val(total.toFixed(2));
    $(element).parents('.item-convenio-agendar').find('.valor_procedimento_agendar_inserir').setMask();
    totalProcedimentosAgendar();
  }

  function totalProcedimentosAgendar(){

    var total_procedimentos_agendar = 0;

    $(".valor_procedimento_agendar_inserir").each(function(index, element) {
        var valor_procedimento_agendar = retornaFormatoValor($(element).val())
        total_procedimentos_agendar = parseFloat(valor_procedimento_agendar) + parseFloat(total_procedimentos_agendar);
    })

    $("#total_procedimentos_agendar").val(total_procedimentos_agendar.toFixed(2))
    $("#total_procedimentos_agendar").setMask()
  }

  function retornaFormatoValor(valor){
    var novo = valor;
    novo = novo.replace('.','')
    novo = novo.replace(',','.')
    return novo;
  }



  $("#hora_agenda_final").on('change', function(){
    var time1 = $("#hora_agenda").val();
    var time2 = $("#hora_agenda_final").val();

    const date1 = new Date('2022-01-01 ' + time1);
    const date2 = new Date('2022-01-01 ' + time2);

    var diferenca = new Date( date2 - date1 );

    var resultado =  diferenca.getUTCHours()*60;
    resultado += diferenca.getUTCMinutes();
    resultado += diferenca.getUTCSeconds()/60;
    extra_tempo_maior = resultado;
    verificaHorarioDisponivelFinal()
  })

  function verificaHorarioDisponivelInicio(){
    var time1 = $("#hora_agenda").val();
    var time2 = $("#hora_agenda_final").val();

    const date1 = new Date('2022-01-01 ' + time1);
    const date2 = new Date('2022-01-01 ' + time2);

    // VERIFICA SE O INICIAL N É MENOR
    if (date1.getTime() >= date2.getTime()) {
      $.toast({
        heading: 'Error',
        text: 'Hora final incorreta!',
        position: 'top-right',
        loaderBg: '#ff6849',
        icon: 'error',
        hideAfter: 5000,
        stack: 10
      });
      $(".salvarAgendamentoInserir").attr('disabled', true)
    }else{
      $(".salvarAgendamentoInserir").attr('disabled', false)
    }
  }

  function verificaHorarioDisponivelFinal(){
    // PEGA OS HORARIOS
    var time1 = $("#hora_agenda").val();
    var time2 = $("#hora_agenda_final").val();

    const date1 = new Date('2022-01-01 ' + time1);
    const date2 = new Date('2022-01-01 ' + time2);

    // VERIFICA SE O INICIAL N É MENOR
    if (date1.getTime() >= date2.getTime()) {
      $.toast({
        heading: 'Error',
        text: 'Hora final incorreta!',
        position: 'top-right',
        loaderBg: '#ff6849',
        icon: 'error',
        hideAfter: 5000,
        stack: 10
      });
      $(".salvarAgendamentoInserir").attr('disabled', true)
    }else{
      $(".salvarAgendamentoInserir").attr('disabled', false)
      
      //VERIFICA SE O HORARIO PROXIMO N EXISTE
      $.ajax("{{route('instituicao.agendamentos.verificaProximoHorarioAgenda')}}", {
            method: "POST",
            data: {
              '_token': '{{csrf_token()}}',
              'hora_agenda': time1,
              'hora_agenda_final': time2,
              'data_agenda': $("#data_agenda").val(),
              'inst_prest_id': $("#inst_prest_id").val()
            },
            success: function (result) {
              if(result.icon == "warning"){
                $.toast({
                  heading: result.title,
                  text: result.text,
                  position: 'top-right',
                  loaderBg: '#ff6849',
                  icon: result.icon,
                  hideAfter: 5000,
                  stack: 10
                });
                $("#proximo_horario_existe").val(1)
              }else{
                $("#proximo_horario_existe").val(0)              
              }
            }
        })
    }
  }

  function verificaMaiorTempoProcedimentos(){
    var tempo_maior = 0;
    $("[name*='[procedimento_agenda]']").each(function(index, element) {     
      var tempo = $(element).find('option:selected').attr('data-tempo');
      if(tempo > 0){
        if(tempo > tempo_maior){
          tempo_maior = tempo;
        }
      }
    })


    if(tempo_maior > extra_tempo_maior && tempo_maior > 0){
      //ADICIONA TEMPO DE ATENDIMENTO
      var time1 = $("#hora_agenda").val();
      const date1 = new Date('2022-01-01 ' + time1);

      date1.setMinutes(date1.getMinutes() + parseInt(tempo_maior));
      var novo_final = addZero(date1.getHours())+':'+addZero(date1.getMinutes());

      $("#hora_agenda_final").val(novo_final)
      extra_tempo_maior = 0;
      verificaHorarioDisponivelFinal()
    }else if(extra_tempo_maior == 0){
      //ADICIONA TEMPO DE ATENDIMENTO
      var time1 = $("#hora_agenda").val();
      const date1 = new Date('2022-01-01 ' + time1);

      date1.setMinutes(date1.getMinutes() + parseInt(tempo_consulta));
      var novo_final = addZero(date1.getHours())+':'+addZero(date1.getMinutes());

      $("#hora_agenda_final").val(novo_final)
      verificaHorarioDisponivelFinal()
    }
  }

  function addZero(i) {
    if (i < 10) {i = "0" + i}
    return i;
  }

</script>

<script type="text/template" id="item-convenio-agendar">
  <div class="col-md-12 item-convenio-agendar">
      <div class="row">
          @can('habilidade_instituicao_sessao', 'visualizar_valor_procedimento')
            <div class="col-md-12">
                <a href="javascrit:void(0)" class="small remove-convenio">(remover)</a>
            </div>
          @endcan
          <div class="form-group dados_parcela @if ($instituicao->desconto_por_procedimento_agenda && \Gate::check('habilidade_instituicao_sessao', 'desconto_procedimento_agendamentos')) col-md-6 @else col-md-4 @endif">
              <label class="form-control-label">Convênio:</span></label>
              <select name="convenio[#][convenio_agenda]" class="form-control selectfild2 convenio" style="width: 100%" onchange="getProcedimentos(this)">
                  <option value="">Selecione um convênio</option>
                  @foreach ($convenios as $item)
                      <option value="{{$item->id}}">{{$item->nome}}</option>
                  @endforeach
              </select>
          </div>
          <div class="form-group @if ($instituicao->desconto_por_procedimento_agenda && \Gate::check('habilidade_instituicao_sessao', 'desconto_procedimento_agendamentos')) col-md-6 @else col-md-4 @endif">
              <label class="form-control-label">Procedimento * <span class="mdi mdi-plus-circle-multiple-outline btnSelectProcedimentos" data-convenio="" style="cursor: pointer;" data-toggle="tooltip" data-placement="top" title="" data-original-title="Selecionar varios procedimentos para o convenio selecionado"></span></label>
              <select name="convenio[#][procedimento_agenda]" class="form-control selectfild2 procedimentos" onchange="getValorProcedimentoAgendar(this)" disabled style="width: 100%">
                <option value="">Selecione um procedimento</option>
              </select>
          </div>
          <div class="form-group @if ($instituicao->desconto_por_procedimento_agenda && \Gate::check('habilidade_instituicao_sessao', 'desconto_procedimento_agendamentos')) col-md-4 @else col-md-2 @endif exige_quantidade_agendar">
            <label class="form-control-label">Qtd *</span></label>
            <input type="number" class="form-control qtd_procedimento_agendar" name="convenio[#][qtd_procedimento]" value='1' onchange="getNovoValorAgendar(this)">
          </div>
          @if ($instituicao->desconto_por_procedimento_agenda)
            @can('habilidade_instituicao_sessao', 'desconto_procedimento_agendamentos')
              <div class="desconto_input col-md-4 @if($errors->has("convenio.0.desconto")) has-danger @endif">
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label class="form-control-label">Desconto (%)</span></label>
                        <div class="input-group">
                            <input type="text" alt="porcentagem" class="form-control porcentagem_desconto" name="convenio[#][porcentagem_desconto]" placeholder="0.00" value="0.00" onchange="calculaValorNovoPorcento(this)">
                        </div>
                    </div>
                    <div class="col-md-6 form-group">
                        <label class="form-control-label">Desconto R$</span></label>
                        <div class="input-group">
                            <input type="text" alt="signed-decimal" class="form-control desc_juros_multa" data-bts-button-up-class="btn btn-secondary btn-outline down-button" data-bts-button-down-class="btn btn-secondary btn-outline up-button" name="convenio[#][desconto]" placeholder="-0,00" value="-0,00" onchange="calculaValorNovoReal(this)">
                            <div class="input-group-append " >
                                    <div class="group-vertical-button desconto-group">
                                        <button type="button" class="btn btn-xs btn-secondary desconto-touchspin-up">
                                                <i class="fas fa-plus"></i>
                                        </button>
                                        <button type="button" class="btn btn-xs btn-secondary desconto-touchspin-down">
                                                <i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
                  
              </div>
            @endcan
          @endif
          @can('habilidade_instituicao_sessao', 'visualizar_valor_procedimento')
            <div class="form-group @if ($instituicao->desconto_por_procedimento_agenda && \Gate::check('habilidade_instituicao_sessao', 'desconto_procedimento_agendamentos')) col-md-4 @else col-md-2 @endif">
                <label class="form-control-label">Valor R$ *</span></label>
                <input type="text" alt="decimal" class="form-control mask_item  valor_procedimento_agendar_inserir" name="convenio[#][valor]">
            </div>
          @endcan
      </div>
  </div>
</script>
