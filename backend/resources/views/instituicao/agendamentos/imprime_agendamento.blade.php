<div class="row">
    <div class="col-md-12 row align-items-center">
        <img class="light-logo col-sm-2" src="@if ($instituicao->imagem){{ \Storage::cloud()->url($instituicao->imagem) }} @endif" alt="" style="height: 100px;"/>
        <h3 class='lead col-sm-8'>{{$instituicao->nome}}</h3>
        <label class="col-sm-2">{{date("d/m/Y H:i:s")}}</label>
    </div>
</div>

<hr class="hr-line-dashed">

<div class="row">
    <div class="col-md-2">
      <div class="form-group">
        <label>Num. Registro</label>
        <h4>{{$agendamento->id}}</h3>
      </div>
    </div>

    <div class="col-md-3">
      <div class="form-group">
        <label>Data </label>
        <h4>{{\Carbon\Carbon::parse($agendamento['data'])->format('d/m/Y')}}</h4>
      </div>
    </div>

    <div class="col-md-2">
      <div class="form-group is-async-input">
        <label>Hora </label>
        <div class="input-container">
          <h4>{{\Carbon\Carbon::parse($agendamento['data'])->format('H:i')}}</h4>
        </div>
      </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            @if($agendamento->instituicoesAgenda->prestadores)
                <label>Profissional</label>
                <h4>{{ucwords($agendamento->instituicoesAgenda->prestadores->especialidade->descricao)}} - {{ucwords($agendamento->instituicoesAgenda->prestadores->prestador->nome)}}</h4>
            @endif
        </div>
    </div>
</div>

<div class="row border p-2">
    <div class="col-md-2">
        <div class="form-group">
            <label>Id Paciente</label>
            <h4>{{$agendamento->pessoa->id}}</h4>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="form-group is-async-input">
            <label>Nome do Paciente</label>
            <h4>{{ucwords($agendamento->pessoa->nome)}}</h4>
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group">
            <label>Data nascimento</label>
            <h4>{{\Carbon\Carbon::parse($agendamento->pessoa->nascimento)->format('d/m/Y')}} - {{floor(((((strtotime(date("Y-m-d")) - strtotime($agendamento->pessoa->nascimento)) / 60) / 60) / 24) / 365.25)}} Anos</h4>
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group">
            <label>Telefone 1</label>
            <h4>{{$agendamento->pessoa->telefone1}}</h4>
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group">
            <label>Telefone 2</label>
            <h4>{{$agendamento->pessoa->telefone2}}</h4>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="form-group">
            <label>CPF</label>
            <h4>{{$agendamento->pessoa->cpf}}</h4>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>Identidade</label>
            <h4>{{$agendamento->pessoa->identidade}}</h4>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>Nome Mãe</label>
            <h4>{{$agendamento->pessoa->nome_mae}}</h4>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>Estado Civil</label>
            <h4>{{ucwords($agendamento->pessoa->estado_civil)}}</h4>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>Naturalidade</label>
            <h4>{{ucwords($agendamento->pessoa->naturalidade)}}</h4>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>Profissão</label>
            <h4>{{ucwords($agendamento->pessoa->profissao)}}</h4>
        </div>
    </div>

    <div class="col-md-5">
        <div class="form-group">
            <label>Rua</label>
            <h4>{{$agendamento->pessoa->rua}}</h4>
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group">
            <label>Número</label>
            <h4>{{$agendamento->pessoa->numero}}</h4>
        </div>
    </div>

    <div class="col-md-5">
        <div class="form-group">
            <label>Complemento</label>
            <h4>{{$agendamento->pessoa->complemento}}</h4>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>Bairro</label>
            <h4>{{$agendamento->pessoa->bairro}}</h4>
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label>cidade</label>
            <h4>{{$agendamento->pessoa->cidade}}</h4>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>Uf</label>
            <h4>{{$agendamento->pessoa->estado}}</h4>
        </div>
    </div>
</div>

<br>

<div class="row border p-2">
    <div class="col-md-12">
        <div class="form-group">
            <label>Obs</label>
            <h4>{{ucwords($agendamento->obs)}}</h4>
        </div>
    </div>
</div>

<br>

<div class="row">
    <div class="col-md-12">
        <table class="tablesaw table-bordered table">
            <thead>
                <tr>
                    <th>Convênio</th>
                    <th>Procedimento</th>
                    @cannot('habilidade_instituicao_sessao', 'ocultar_valor_proc_imprime_agendamento') 
                    <th>Valor</th>
                    @endcannot
                </tr>
            </thead>
            
            <tbody style="font-size: 25px">
                @foreach ($agendamento->agendamentoProcedimento as $index => $agendamentoProcedimento)
                    <tr>
                        <td>
                            {{strtoupper($agendamentoProcedimento->procedimentoInstituicaoConvenio->convenios->nome)}}
                        </td>
                        <td>
                            {{strtoupper($agendamentoProcedimento->procedimentoInstituicaoConvenio->procedimentoInstituicao->procedimento->descricao)}}
                        </td>
                        
                        @cannot('habilidade_instituicao_sessao', 'ocultar_valor_proc_imprime_agendamento') 
                        <td>
                            {{strtoupper(number_format($agendamentoProcedimento->valor_atual+$agendamentoProcedimento->valor_convenio, 2,",","."))}}
                        </td>
                        @endcannot
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>