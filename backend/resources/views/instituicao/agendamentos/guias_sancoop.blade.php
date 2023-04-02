<form id="formGuia">
    @csrf
    <div class="card shadow-none p-3">
      <input type="hidden" name="paciente_id_desc" id="paciente_id_desc" value="{{$agendamento->pessoa_id}}">
      <div class="col-sm-12 pt-3 carteirinha_paciente">
        <label for="carteirinha_id" class="control-label">Carteirinha:  @if($aut) <span class="text-danger">*</span> @endif
          @can('habilidade_instituicao_sessao', "cadastrar_carteirinha")
            <a class="mytooltip carteirinha-nova" href="{{route('instituicao.carteirinhas.create', [$agendamento->pessoa_id])}}" target="_blank">
              <span alt="default" class="fas fa-plus-circle">
            </a>
            <i class="fa fa-question-circle help" data-toggle="tooltip" data-placement="right" title="" data-original-title="Adicionar carterinha paciente"></i>
            </span>
          @endcan
        </label>
        {{-- <select class="form-control select2carteirinha" name="carteirinha_id" id="carteirinha_id" style="width: 100%" onchange="verificaExigeCarteirinha()"> --}}
        <select class="form-control select2carteirinha" name="carteirinha_id" id="carteirinha_id" style="width: 100%">
          @if ($agendamento->carteirinha)
            <option value="{{$agendamento->carteirinha->id}}">{{$agendamento->carteirinha->carteirinha}} ({{$agendamento->carteirinha->convenio[0]->nome}})</option>
          @else
            <option value=""></option>
          @endif
        </select>
      </div>

      {{-- @dump($divisao_guia, $tipo_guia, $agendamento->agendamentoGuias) --}}

      @php 
        if($divisao_guia == 'separado'){
          // dump($tipo_guia, $divisao_guia, $agendamento->agendamentoGuias);
          
          if(count($tipo_guia) > 1){
            $rep = 2;
          }else{
            $rep = 1;
          }
        }else{
          $rep = 1;
        }

      @endphp
      
      @for($i = 0; $i < $rep; $i++)
        <div class="row col-sm">
          <div class="col-sm-3 pt-3 carteirinha_paciente">
            <label class="control-label">Tipo guia: <span class="text-danger">*</span></label>
            <select class="form-control" name="guia[{{$i}}][tipo_guia]" id="tipo_guia_{{$i}}">
              <option value="">Selecione </option>
              <option value="consulta"
                @if(empty($agendamento->agendamentoGuias[$i]->tipo_guia) && $divisao_guia == 'separado' && $tipo_guia[$i] == 1) selected @endif
                @if(!empty($agendamento->agendamentoGuias[$i]->tipo_guia) && $agendamento->agendamentoGuias[$i]->tipo_guia == 'consulta') selected @endif
                @if(empty($agendamento->agendamentoGuias[$i]->tipo_guia) && $divisao_guia == 'separado' && $tipo_guia[$i] == 2) selected @endif
                @if(empty($agendamento->agendamentoGuias[$i]->tipo_guia) && $divisao_guia == 'junto' && count($tipo) ==1 && $tipo_guia[$i] == 1) selected @endif
              >Consulta</option>
              <option value="sadt" 
                @if(!empty($agendamento->agendamentoGuias[0]->tipo_guia) && $agendamento->agendamentoGuias[0]->tipo_guia == 'consulta' && $divisao_guia == 'separado' && $i==1) selected @endif  
                @if(empty($agendamento->agendamentoGuias[$i]->tipo_guia) && $divisao_guia == 'separado'  && $tipo_guia[$i] == 2) selected @endif
                @if(empty($agendamento->agendamentoGuias[$i]->tipo_guia) && $divisao_guia == 'junto' && !(count($tipo) ==1 && $tipo_guia[$i] == 1)) selected @endif
                @if(!empty($agendamento->agendamentoGuias[$i]->tipo_guia) && $agendamento->agendamentoGuias[$i]->tipo_guia == 'sadt') selected @endif
              >SADT</option>
            </select>

            {{-- @php
              dump($tipo, $divisao_guia, $tipo_guia[$i]);
            @endphp --}}
          </div>

          <input type='hidden' name="guia[{{$i}}][agendamento_id]" value="{{$agendamento->id}}">
          <div class="col-sm-6 pt-3 carteirinha_paciente">
            <label class="control-label">Cod autorização: @if($aut) <span class="text-danger">*</span> @endif</label>
            <input class="form-control" name="guia[{{$i}}][cod_aut_convenio]" id="cod_aut_convenio_{{$i}}" value="{{!empty($agendamento->agendamentoGuias[$i]->cod_aut_convenio) ? $agendamento->agendamentoGuias[$i]->cod_aut_convenio : ''}}">
            {{-- <input class="form-control" name="cod_aut_convenio" id="cod_aut_convenio" value="{{$agendamento->cod_aut_convenio}}" onchange="verificaExigeCarteirinha()"> --}}
          </div>

          <div class="col-sm-3 pt-3 carteirinha_paciente">
            <label class="control-label">Número Guia:</label>
            <input class="form-control" name="guia[{{$i}}][num_guia_convenio]" id="num_guia_convenio_{{$i}}" value="{{!empty($agendamento->agendamentoGuias[$i]->num_guia_convenio) ? $agendamento->agendamentoGuias[$i]->num_guia_convenio : '' }}">
          </div>                  
        </div>
      @endfor
    </form>
    <div class="row col-sm">
      <form class="col-sm-12 pt-3" id="formArquivoGuia" enctype="multipart/form-data">
        @csrf
        <label class="form-control-label">
          Arquivo: 
          <span alt="default" class="refresh mdi mdi-refresh sem-arquivo @if(empty($agendamento->arquivo_guia_convenio)) exibe @endif" style="cursor: pointer; display: none;"></span>
          <span alt="default" class="close mdi mdi-close-circle tem-arquivo @if(!empty($agendamento->arquivo_guia_convenio)) exibe @endif" style="cursor: pointer; display: none;"></span>
        </label>
        <input disabled type="text" class="tem-arquvio @if(!empty($agendamento->arquivo_guia_convenio)) exibe @endif form-control"  value="Guia Carregada" style="display: none;">

        <div class="sem-arquivo @if(empty($agendamento->arquivo_guia_convenio)) exibe @endif" style="display: none;">
          <input id="arquivo_upload" class="" type="file" name="arquivo_guia_convenio" onchange="uploadGuiaConvenio()" value="{{$agendamento->arquivo_guia_convenio}}">
        </div>
      </form>
    </div>
    
    
    <button class="btn btn-primary waves-effect editar-carteirinha" type="button" style="background-color: #26c6da;border:#26c6da;color: #fff; margin-top: 10px">Salvar dados da(s) guia(s) do convênio</button>
          
  </div>