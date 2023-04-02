<style>
    ul.timeline {
        list-style-type: none;
        position: relative;
    }
    ul.timeline:before {
        content: ' ';
        background: #d4d9df;
        display: inline-block;
        position: absolute;
        left: 29px;
        width: 2px;
        height: 100%;
        z-index: 400;
    }
    ul.timeline > li {
        margin: 20px 0;
        padding-left: 45px;
    }
    ul.timeline > li:before {
        content: ' ';
        background: white;
        display: inline-block;
        position: absolute;
        border-radius: 50%;
        border: 3px solid #22c0e8;
        left: 20px;
        width: 20px;
        height: 20px;
        z-index: 400;
    }

    .modal-resumo .menu-lista{
        margin-bottom: 10px;
        margin-top: 10px;
        background: #1babfffa;
        color: white;
    }
    .modal-resumo .item-lista{
        border: 1px solid #abababeb;
        padding: 7px;
    }
    .modal-resumo .item-lista .item{
        background: #ababab61;
        padding: 10px;
    }
    .modal-resumo .item-lista .texto{
        padding: 10px;
        text-align: justify;
    }
</style>

<ul class="timeline" style="z-index: 0">

    @foreach ($resumos as $item)  
        
        <li class="resumo_{{$item->id}}">
            <div id="accordian-{{$item->id}}">
                <div class="card m-b-0">
                    <a class="card-header text-decoration-none" id="resumos_{{$item->id}}">
                        <button class="btn btn-link" data-toggle="collapse" data-target="#collapse{{$item->id}}" aria-expanded="false" aria-controls="collapse{{$item->id}}" style="width: 100%; text-align: left">
                            <h5 class="m-b-0">Atendimento #{{$item->id}} 
                                @if (sizeof($item->prontuario) > 0)
                                    <i class="mdi mdi-bookmark-plus-outline"></i>
                                @endif
                                @if (sizeof($item->receituario) > 0)
                                    <i class="mdi mdi-pill"></i>
                                @endif 
                                @if (sizeof($item->refracao) > 0)
                                    <i class="mdi mdi-glasses"></i>
                                @endif 
                                @if (sizeof($item->atestado) > 0)
                                    <i class="mdi mdi-clipboard-outline"></i>
                                @endif 
                                @if (sizeof($item->conclusao) > 0)
                                    <i class="mdi mdi-file-check"></i>
                                @endif 
                                @if (sizeof($item->relatorio) > 0)
                                    <i class="mdi mdi-image-filter"></i>
                                @endif 
                                @if (sizeof($item->exame) > 0)
                                    <i class="mdi mdi-library-plus"></i>
                                @endif 
                                @if (sizeof($item->encaminhamento) > 0)
                                    <i class="mdi mdi-ambulance"></i>
                                @endif 
                                @if (sizeof($item->laudo) > 0)
                                    <i class="mdi mdi-clipboard-text"></i>
                                @endif 
                            </h5><small>Realizado em {{ date('d/m/Y H:i', strtotime($item->data) ) }}</small>
                        </button>
                    </a>
                    <div id="collapse{{$item->id}}" class="collapse" aria-labelledby="resumos_{{$item->id}}" data-parent="#accordian-{{$item->id}}">
                        <div class="card-body">
                            @can('habilidade_instituicao_sessao', 'visualizar_receituario')
                                @if (sizeof($item->receituario) > 0)
                                    <div id="accordian-receituario-{{$item->id}}" style="margin: 10px;">
                                        <a class="card-header text-decoration-none" id="receituario_resumo_{{$item->id}}" style="padding: 0px">
                                            <button class="btn btn-info" data-toggle="collapse" data-target="#collapse_receituario_{{$item->id}}" aria-expanded="false" aria-controls="collapse_receituario_{{$item->id}}">
                                                <h5 class="m-b-0" style="color: white; font-size: 15px;">Receituário</h5>
                                            </button>
                                        </a>
                                        <div id="collapse_receituario_{{$item->id}}" class="collapse" aria-labelledby="resumos_receituario_{{$item->id}}" data-parent="#accordian-receituario-{{$item->id}}">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist" style="width: 90%">#</th>
                                                        <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="3" style="width: 10%">Ações</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($item->receituario as $keyp => $receituario)
                                                    <tr>
                                                        <td>
                                                            #{{$keyp+1}}
                                                        </td>
                                                        <td>
                                                            @if($receituario->estrutura == "memed")
                                                                <button type="button" class="btn btn-xs btn-secondary visualizar-resumo-receituario-memed" aria-haspopup="true" aria-expanded="false"
                                                                data-toggle="tooltip" data-placement="top" data-id="{{$item->id}}" data-receituario="{{$receituario->id}}"
                                                                data-original-title="Visualizar">
                                                                <i class="far fa-list-alt"></i>
                                                            </button>

                                                            @else
                                                                <a href="javascript:newPopup('{{route('agendamento.receituario.imprimirReceituario', $receituario)}}')">
                                                                    <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                                                                data-toggle="tooltip" data-placement="top" data-original-title="Imprimir">
                                                                        <i class="ti-printer"></i>
                                                                    </button>
                                                                </a>
                                                                <button type="button" class="btn btn-xs btn-secondary visualizar-resumo-receituario" aria-haspopup="true" aria-expanded="false"
                                                                    data-toggle="tooltip" data-placement="top" data-id="{{$item->id}}" data-receituario="{{$receituario->id}}" data-original-title="Visualizar">
                                                                    <i class="far fa-list-alt"></i>
                                                                </button>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    
                                @endif
                            @endcan

                            @can('habilidade_instituicao_sessao', 'visualizar_prontuario')
                                
                                @if (sizeof($item->prontuario) > 0)
                                    <div id="accordian-prontuario-{{$item->id}}" style="margin: 10px;">
                                        <a class="card-header text-decoration-none" id="prontuario_resumo_{{$item->id}}" style="padding: 0px">
                                            <button class="btn btn-info" data-toggle="collapse" data-target="#collapse-prontuario-{{$item->id}}" aria-expanded="false" aria-controls="collapse-prontuario-{{$item->id}}">
                                                <h5 class="m-b-0" style="color: white; font-size: 17px;">Prontuário</h5>
                                            </button>
                                        </a>
                                        <div id="collapse-prontuario-{{$item->id}}" class="collapse" aria-labelledby="resumos_prontuario_{{$item->id}}" data-parent="#accordian-prontuario-{{$item->id}}">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist" style="width: 90%">#</th>
                                                        <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="3" style="width: 10%">Ações</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($item->prontuario as $keyp => $prontuario)
                                                    <tr>
                                                        <td>
                                                            #{{$keyp+1}}
                                                        </td>
                                                        <td>
                                                            <a href="javascript:newPopup('{{route('agendamento.prontuario.imprimirProntuario', $prontuario)}}')">
                                                                <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                                                            data-toggle="tooltip" data-placement="top" data-original-title="Imprimir">
                                                                    <i class="ti-printer"></i>
                                                                </button>
                                                            </a>
                                                            <button type="button" class="btn btn-xs btn-secondary visualizar-resumo-prontuario" aria-haspopup="true" aria-expanded="false"
                                                                data-toggle="tooltip" data-placement="top" data-id="{{$item->id}}" data-prontuario="{{$prontuario->id}}" data-original-title="Visualizar">
                                                                <i class="far fa-list-alt"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endif
                            @endcan

                            @can('habilidade_instituicao_sessao', 'visualizar_refracao')
                                
                                @if (sizeof($item->refracao) > 0)
                                    <div id="accordian-refracao-{{$item->id}}" style="margin: 10px;">
                                        <a class="card-header text-decoration-none" id="refracao_resumo_{{$item->id}}" style="padding: 0px">
                                            <button class="btn btn-info" data-toggle="collapse" data-target="#collapse-refracao-{{$item->id}}" aria-expanded="false" aria-controls="collapse-refracao-{{$item->id}}">
                                                <h5 class="m-b-0" style="color: white; font-size: 17px;">Refração</h5>
                                            </button>
                                        </a>
                                        <div id="collapse-refracao-{{$item->id}}" class="collapse" aria-labelledby="resumos_refracao_{{$item->id}}" data-parent="#accordian-refracao-{{$item->id}}">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist" style="width: 90%">#</th>
                                                        <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="3" style="width: 10%">Ações</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($item->refracao as $keyp => $refracao)
                                                    <tr>
                                                        <td>
                                                            #{{$keyp+1}}
                                                        </td>
                                                        <td>
                                                            <a href="javascript:newPopup('{{route('agendamento.refracao.imprimirRefracao', $refracao)}}')">
                                                                <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                                                            data-toggle="tooltip" data-placement="top" data-original-title="Imprimir">
                                                                    <i class="ti-printer"></i>
                                                                </button>
                                                            </a>
                                                            <button type="button" class="btn btn-xs btn-secondary visualizar-resumo-refracao" aria-haspopup="true" aria-expanded="false"
                                                                data-toggle="tooltip" data-placement="top" data-id="{{$item->id}}" data-refracao="{{$refracao->id}}" data-original-title="Visualizar">
                                                                <i class="far fa-list-alt"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endif
                            @endcan

                            @can('habilidade_instituicao_sessao', 'visualizar_atestado')
                                
                                @if (sizeof($item->atestado) > 0)
                                    <div id="accordian-atestado-{{$item->id}}" style="margin: 10px;">
                                        <a class="card-header text-decoration-none" id="atestado_resumo_{{$item->id}}" style="padding: 0px">
                                            <button class="btn btn-info" data-toggle="collapse" data-target="#collapse-atestado-{{$item->id}}" aria-expanded="false" aria-controls="collapse-atestado-{{$item->id}}">
                                                <h5 class="m-b-0" style="color: white; font-size: 17px;">Atestados</h5>
                                            </button>
                                        </a>
                                        <div id="collapse-atestado-{{$item->id}}" class="collapse" aria-labelledby="resumos_atestado_{{$item->id}}" data-parent="#accordian-atestado-{{$item->id}}">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist" style="width: 90%">#</th>
                                                        <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="3" style="width: 10%">Ações</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($item->atestado as $keyp => $atestado)
                                                    <tr>
                                                        <td>
                                                            #{{$keyp+1}}
                                                        </td>
                                                        <td>
                                                            <a href="javascript:newPopup('{{route('agendamento.atestado.imprimirAtestado', $atestado)}}')">
                                                                <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                                                            data-toggle="tooltip" data-placement="top" data-original-title="Imprimir">
                                                                    <i class="ti-printer"></i>
                                                                </button>
                                                            </a>
                                                            <button type="button" class="btn btn-xs btn-secondary visualizar-resumo-atestado" aria-haspopup="true" aria-expanded="false"
                                                                data-toggle="tooltip" data-placement="top" data-id="{{$item->id}}" data-atestado="{{$atestado->id}}" data-original-title="Visualizar">
                                                                <i class="far fa-list-alt"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endif
                            @endcan

                            @can('habilidade_instituicao_sessao', 'visualizar_relatorio')
                                
                                @if (sizeof($item->relatorio) > 0)
                                    <div id="accordian-relatorio-{{$item->id}}" style="margin: 10px;">
                                        <a class="card-header text-decoration-none" id="relatorio_resumo_{{$item->id}}" style="padding: 0px">
                                            <button class="btn btn-info" data-toggle="collapse" data-target="#collapse-relatorio-{{$item->id}}" aria-expanded="false" aria-controls="collapse-relatorio-{{$item->id}}">
                                                <h5 class="m-b-0" style="color: white; font-size: 17px;">Relatórios</h5>
                                            </button>
                                        </a>
                                        <div id="collapse-relatorio-{{$item->id}}" class="collapse" aria-labelledby="resumos_relatorio_{{$item->id}}" data-parent="#accordian-relatorio-{{$item->id}}">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist" style="width: 90%">#</th>
                                                        <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="3" style="width: 10%">Ações</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($item->relatorio as $keyp => $relatorio)
                                                    <tr>
                                                        <td>
                                                            #{{$keyp+1}}
                                                        </td>
                                                        <td>
                                                            <a href="javascript:newPopup('{{route('agendamento.relatorio.imprimirRelatorio', $relatorio)}}')">
                                                                <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                                                            data-toggle="tooltip" data-placement="top" data-original-title="Imprimir">
                                                                    <i class="ti-printer"></i>
                                                                </button>
                                                            </a>
                                                            <button type="button" class="btn btn-xs btn-secondary visualizar-resumo-relatorio" aria-haspopup="true" aria-expanded="false"
                                                                data-toggle="tooltip" data-placement="top" data-id="{{$item->id}}" data-relatorio="{{$relatorio->id}}" data-original-title="Visualizar">
                                                                <i class="far fa-list-alt"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endif
                            @endcan

                            @can('habilidade_instituicao_sessao', 'visualizar_exame')
                                
                                @if (sizeof($item->exame) > 0)
                                    <div id="accordian-exame-{{$item->id}}" style="margin: 10px;">
                                        <a class="card-header text-decoration-none" id="exame_resumo_{{$item->id}}" style="padding: 0px">
                                            <button class="btn btn-info" data-toggle="collapse" data-target="#collapse-exame-{{$item->id}}" aria-expanded="false" aria-controls="collapse-exame-{{$item->id}}">
                                                <h5 class="m-b-0" style="color: white; font-size: 17px;">Exames</h5>
                                            </button>
                                        </a>
                                        <div id="collapse-exame-{{$item->id}}" class="collapse" aria-labelledby="resumos_exame_{{$item->id}}" data-parent="#accordian-exame-{{$item->id}}">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist" style="width: 90%">#</th>
                                                        <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="3" style="width: 10%">Ações</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($item->exame as $keyp => $exame)
                                                    <tr>
                                                        <td>
                                                            #{{$keyp+1}}
                                                        </td>
                                                        <td>
                                                            <a href="javascript:newPopup('{{route('agendamento.exame.imprimirExame', $exame)}}')">
                                                                <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                                                            data-toggle="tooltip" data-placement="top" data-original-title="Imprimir">
                                                                    <i class="ti-printer"></i>
                                                                </button>
                                                            </a>
                                                            <button type="button" class="btn btn-xs btn-secondary visualizar-resumo-exame" aria-haspopup="true" aria-expanded="false"
                                                                data-toggle="tooltip" data-placement="top" data-id="{{$item->id}}" data-exame="{{$exame->id}}" data-original-title="Visualizar">
                                                                <i class="far fa-list-alt"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endif
                            @endcan
                            @can('habilidade_instituicao_sessao', 'visualizar_encaminhamento')
                                
                                @if (sizeof($item->encaminhamento) > 0)
                                    <div id="accordian-encaminhamento-{{$item->id}}" style="margin: 10px;">
                                        <a class="card-header text-decoration-none" id="encaminhamento_resumo_{{$item->id}}" style="padding: 0px">
                                            <button class="btn btn-info" data-toggle="collapse" data-target="#collapse-encaminhamento-{{$item->id}}" aria-expanded="false" aria-controls="collapse-encaminhamento-{{$item->id}}">
                                                <h5 class="m-b-0" style="color: white; font-size: 17px;">Encaminhamentos</h5>
                                            </button>
                                        </a>
                                        <div id="collapse-encaminhamento-{{$item->id}}" class="collapse" aria-labelledby="resumos_encaminhamento_{{$item->id}}" data-parent="#accordian-encaminhamento-{{$item->id}}">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist" style="width: 90%">#</th>
                                                        <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="3" style="width: 10%">Ações</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($item->encaminhamento as $keyp => $encaminhamento)
                                                    <tr>
                                                        <td>
                                                            #{{$keyp+1}}
                                                        </td>
                                                        <td>
                                                            <a href="javascript:newPopup('{{route('agendamento.encaminhamento.imprimirEncaminhamento', $encaminhamento)}}')">
                                                                <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                                                            data-toggle="tooltip" data-placement="top" data-original-title="Imprimir">
                                                                    <i class="ti-printer"></i>
                                                                </button>
                                                            </a>
                                                            <button type="button" class="btn btn-xs btn-secondary visualizar-resumo-encaminhamento" aria-haspopup="true" aria-expanded="false"
                                                                data-toggle="tooltip" data-placement="top" data-id="{{$item->id}}" data-encaminhamento="{{$encaminhamento->id}}" data-original-title="Visualizar">
                                                                <i class="far fa-list-alt"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endif
                            @endcan
                            @can('habilidade_instituicao_sessao', 'visualizar_laudo')
                                
                                @if (sizeof($item->laudo) > 0)
                                    <div id="accordian-laudo-{{$item->id}}" style="margin: 10px;">
                                        <a class="card-header text-decoration-none" id="laudo_resumo_{{$item->id}}" style="padding: 0px">
                                            <button class="btn btn-info" data-toggle="collapse" data-target="#collapse-laudo-{{$item->id}}" aria-expanded="false" aria-controls="collapse-laudo-{{$item->id}}">
                                                <h5 class="m-b-0" style="color: white; font-size: 17px;">Laudos</h5>
                                            </button>
                                        </a>
                                        <div id="collapse-laudo-{{$item->id}}" class="collapse" aria-labelledby="resumos_laudo_{{$item->id}}" data-parent="#accordian-laudo-{{$item->id}}">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist" style="width: 90%">#</th>
                                                        <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="3" style="width: 10%">Ações</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($item->laudo as $keyp => $laudo)
                                                    <tr>
                                                        <td>
                                                            #{{$keyp+1}}
                                                        </td>
                                                        <td>
                                                            <a href="javascript:newPopup('{{route('agendamento.laudo.imprimirLaudo', $laudo)}}')">
                                                                <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                                                            data-toggle="tooltip" data-placement="top" data-original-title="Imprimir">
                                                                    <i class="ti-printer"></i>
                                                                </button>
                                                            </a>
                                                            <button type="button" class="btn btn-xs btn-secondary visualizar-resumo-laudo" aria-haspopup="true" aria-expanded="false"
                                                                data-toggle="tooltip" data-placement="top" data-id="{{$item->id}}" data-laudo="{{$laudo->id}}" data-original-title="Visualizar">
                                                                <i class="far fa-list-alt"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endif
                            @endcan
                            @can('habilidade_instituicao_sessao', 'visualizar_conclusao')
                                
                                @if (sizeof($item->conclusao) > 0)
                                    <div id="accordian-conclusao-{{$item->id}}" style="margin: 10px;">
                                        <a class="card-header text-decoration-none" id="conclusao_resumo_{{$item->id}}" style="padding: 0px">
                                            <button class="btn btn-info" data-toggle="collapse" data-target="#collapse-conclusao-{{$item->id}}" aria-expanded="false" aria-controls="collapse-conclusao-{{$item->id}}">
                                                <h5 class="m-b-0" style="color: white; font-size: 17px;">Conclusão</h5>
                                            </button>
                                        </a>
                                        <div id="collapse-conclusao-{{$item->id}}" class="collapse" aria-labelledby="resumos_conclusao_{{$item->id}}" data-parent="#accordian-conclusao-{{$item->id}}">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist" style="width: 90%">#</th>
                                                        <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="3" style="width: 10%">Ações</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($item->conclusao as $keyp => $conclusao)
                                                    <tr>
                                                        <td>
                                                            #{{$keyp+1}}
                                                        </td>
                                                        <td>
                                                            <a href="javascript:newPopup('{{route('agendamento.conclusao.imprimirConclusao', $conclusao)}}')">
                                                                <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                                                            data-toggle="tooltip" data-placement="top" data-original-title="Imprimir">
                                                                    <i class="ti-printer"></i>
                                                                </button>
                                                            </a>
                                                            <button type="button" class="btn btn-xs btn-secondary visualizar-resumo-conclusao" aria-haspopup="true" aria-expanded="false"
                                                                data-toggle="tooltip" data-placement="top" data-id="{{$item->id}}" data-conclusao="{{$conclusao->id}}" data-original-title="Visualizar">
                                                                <i class="far fa-list-alt"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endif
                            @endcan
                        </div>
                    </div>
                
                    <div style="padding: 10px;">
                        <hr style="width: 100%">
                        <p><i class="fas fa-user-md"></i> @if(sizeof($item->receituario) > 0)
                            {{$item->receituario[0]->usuario->nome}}
                        @elseif(sizeof($item->prontuario) > 0)
                            {{$item->prontuario[0]->usuario->nome}}
                        @elseif(sizeof($item->relatorio) > 0)
                            {{$item->relatorio[0]->usuario->nome}}
                        @elseif(sizeof($item->atestado) > 0)
                            {{$item->atestado[0]->usuario->nome}}
                        @elseif(sizeof($item->conclusao) > 0)
                            {{$item->conclusao[0]->usuario->nome}}
                        @elseif(sizeof($item->exame) > 0)
                            {{$item->exame[0]->usuario->nome}}
                        @elseif(sizeof($item->encaminhamento) > 0)
                            {{$item->encaminhamento[0]->usuario->nome}}
                        @endif</p>
                    </div>
                </div>  
            </div>
        </li>
    @endforeach
</ul>
<div class="modal-resumo"></div>
<script>
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip()
    })

    $(".visualizar-resumo-receituario").on('click', function(){
        var paciente_id = $("#paciente_id").val();
        var agendamento_id = $(this).attr('data-id');
        var receituario_id = $(this).attr('data-receituario');
        
        $.ajax({
            url: "{{route('agendamento.resumo.paciente.receituario', ['agendamento' => 'agendamento_id', 'paciente' => 'paciente_id', 'receituario' => 'receituario_id'])}}".replace('agendamento_id', agendamento_id).replace('paciente_id', paciente_id).replace('receituario_id', receituario_id),
            type: 'POST',
            data: {'_token': '{{ csrf_token() }}'},
            beforeSend: () => {
                $('.loading').css('display', 'block');
                $('.loading').find('.class-loading').addClass('loader')
            },
            success: function(result) {
                $(".modal-resumo").html('');
                $(".modal-resumo").html(result);
                $(".modal-resumo").find('#modalReceituarioResumo').modal('show')
                $('.loading').css('display', 'none');
            },
            complete: () => {
                $('.loading').find('.class-loading').removeClass('loader') 
            }

        });
    });


    // vizualizar receituario memed prescrito
    $(".visualizar-resumo-receituario-memed").on('click', function(){
        var paciente_id = $("#paciente_id").val();
        var agendamento_id = $(this).attr('data-id');
        var receituario_id = $(this).attr('data-receituario');

        $.ajax({
            url: "{{route('agendamento.receituario_memed.getReceituario', ['agendamento' => 'agendamento_id', 'paciente' => 'paciente_id', 'receituario' => 'receituario_id'])}}".replace('agendamento_id', agendamento_id).replace('paciente_id', paciente_id).replace('receituario_id', receituario_id),
            type: 'POST',
            data: {'_token': '{{ csrf_token() }}'},
            beforeSend: () => {
                $('.loading').css('display', 'block');
                $('.loading').find('.class-loading').addClass('loader')
            },
            success: function(result) {
                $(".modal-resumo").html('');
                if(result.icon == "error"){
                    $.toast({
                        heading: response.title,
                        text: response.text,
                        position: 'top-right',
                        loaderBg: '#ff6849',
                        icon: response.icon,
                        hideAfter: 3000,
                        stack: 10
                    });
                }else{
                    $(".modal-resumo").html(result);
                    $(".modal-resumo").find('#modalReceituarioMemed').modal('show')
                }
                
                $('.loading').css('display', 'none');
            },
            complete: () => {
                $('.loading').find('.class-loading').removeClass('loader') 
            }

        });
    });
    
    $(".visualizar-resumo-prontuario").on('click', function(){
        var paciente_id = $("#paciente_id").val();
        var agendamento_id = $(this).attr('data-id');
        var prontuario_id = $(this).attr('data-prontuario');

        $.ajax({
            url: "{{route('agendamento.resumo.paciente.prontuario', ['agendamento' => 'agendamento_id', 'paciente' => 'paciente_id', 'prontuario' => 'prontuario_id'])}}".replace('agendamento_id', agendamento_id).replace('paciente_id', paciente_id).replace('prontuario_id', prontuario_id),
            type: 'POST',
            data: {'_token': '{{ csrf_token() }}'},
            beforeSend: () => {
                $('.loading').css('display', 'block');
                $('.loading').find('.class-loading').addClass('loader')
            },
            success: function(result) {
                $(".modal-resumo").html('');
                $(".modal-resumo").html(result);
                $(".modal-resumo").find('#modalProntuarioResumo').modal('show')
                $('.loading').css('display', 'none');
            },
            complete: () => {
                $('.loading').find('.class-loading').removeClass('loader') 
            }

        });
    });
    $(".visualizar-resumo-atestado").on('click', function(){
        var paciente_id = $("#paciente_id").val();
        var agendamento_id = $(this).attr('data-id');
        var atestado_id = $(this).attr('data-atestado');

        $.ajax({
            url: "{{route('agendamento.resumo.paciente.atestado', ['agendamento' => 'agendamento_id', 'paciente' => 'paciente_id', 'atestado' => 'atestado_id'])}}".replace('agendamento_id', agendamento_id).replace('paciente_id', paciente_id).replace('atestado_id', atestado_id),
            type: 'POST',
            data: {'_token': '{{ csrf_token() }}'},
            beforeSend: () => {
                $('.loading').css('display', 'block');
                $('.loading').find('.class-loading').addClass('loader')
            },
            success: function(result) {
                $(".modal-resumo").html('');
                $(".modal-resumo").html(result);
                $(".modal-resumo").find('#modalAtestadoResumo').modal('show')
                $('.loading').css('display', 'none');
            },
            complete: () => {
                $('.loading').find('.class-loading').removeClass('loader') 
            }

        });
    });
    $(".visualizar-resumo-relatorio").on('click', function(){
        var paciente_id = $("#paciente_id").val();
        var agendamento_id = $(this).attr('data-id');
        var relatorio_id = $(this).attr('data-relatorio');

        $.ajax({
            url: "{{route('agendamento.resumo.paciente.relatorio', ['agendamento' => 'agendamento_id', 'paciente' => 'paciente_id', 'relatorio' => 'relatorio_id'])}}".replace('agendamento_id', agendamento_id).replace('paciente_id', paciente_id).replace('relatorio_id', relatorio_id),
            type: 'POST',
            data: {'_token': '{{ csrf_token() }}'},
            beforeSend: () => {
                $('.loading').css('display', 'block');
                $('.loading').find('.class-loading').addClass('loader')
            },
            success: function(result) {
                $(".modal-resumo").html('');
                $(".modal-resumo").html(result);
                $(".modal-resumo").find('#modalRelatorioResumo').modal('show')
                $('.loading').css('display', 'none');
            },
            complete: () => {
                $('.loading').find('.class-loading').removeClass('loader') 
            }

        });
    });
    $(".visualizar-resumo-exame").on('click', function(){
        var paciente_id = $("#paciente_id").val();
        var agendamento_id = $(this).attr('data-id');
        var exame_id = $(this).attr('data-exame');

        $.ajax({
            url: "{{route('agendamento.resumo.paciente.exame', ['agendamento' => 'agendamento_id', 'paciente' => 'paciente_id', 'exame' => 'exame_id'])}}".replace('agendamento_id', agendamento_id).replace('paciente_id', paciente_id).replace('exame_id', exame_id),
            type: 'POST',
            data: {'_token': '{{ csrf_token() }}'},
            beforeSend: () => {
                $('.loading').css('display', 'block');
                $('.loading').find('.class-loading').addClass('loader')
            },
            success: function(result) {
                $(".modal-resumo").html('');
                $(".modal-resumo").html(result);
                $(".modal-resumo").find('#modalExameResumo').modal('show')
                $('.loading').css('display', 'none');
            },
            complete: () => {
                $('.loading').find('.class-loading').removeClass('loader') 
            }

        });
    });
    $(".visualizar-resumo-refracao").on('click', function(){
        var paciente_id = $("#paciente_id").val();
        var agendamento_id = $(this).attr('data-id');
        var refracao_id = $(this).attr('data-refracao');

        $.ajax({
            url: "{{route('agendamento.resumo.paciente.refracao', ['agendamento' => 'agendamento_id', 'paciente' => 'paciente_id', 'refracao' => 'refracao_id'])}}".replace('agendamento_id', agendamento_id).replace('paciente_id', paciente_id).replace('refracao_id', refracao_id),
            type: 'POST',
            data: {'_token': '{{ csrf_token() }}'},
            beforeSend: () => {
                $('.loading').css('display', 'block');
                $('.loading').find('.class-loading').addClass('loader')
            },
            success: function(result) {
                $(".modal-resumo").html('');
                $(".modal-resumo").html(result);
                $(".modal-resumo").find('#modalRefracaoResumo').modal('show')
                $('.loading').css('display', 'none');
            },
            complete: () => {
                $('.loading').find('.class-loading').removeClass('loader') 
            }

        });
    });

    $(".visualizar-resumo-encaminhamento").on('click', function(){
        var paciente_id = $("#paciente_id").val();
        var agendamento_id = $(this).attr('data-id');
        var encaminhamento_id = $(this).attr('data-encaminhamento');

        $.ajax({
            url: "{{route('agendamento.resumo.paciente.encaminhamento', ['agendamento' => 'agendamento_id', 'paciente' => 'paciente_id', 'encaminhamento' => 'encaminhamento_id'])}}".replace('agendamento_id', agendamento_id).replace('paciente_id', paciente_id).replace('encaminhamento_id', encaminhamento_id),
            type: 'POST',
            data: {'_token': '{{ csrf_token() }}'},
            beforeSend: () => {
                $('.loading').css('display', 'block');
                $('.loading').find('.class-loading').addClass('loader')
            },
            success: function(result) {
                $(".modal-resumo").html('');
                $(".modal-resumo").html(result);
                $(".modal-resumo").find('#modalEncaminhamentoResumo').modal('show')
                $('.loading').css('display', 'none');
            },
            complete: () => {
                $('.loading').find('.class-loading').removeClass('loader') 
            }

        });
    });
    
    $(".visualizar-resumo-laudo").on('click', function(){
        var paciente_id = $("#paciente_id").val();
        var agendamento_id = $(this).attr('data-id');
        var laudo_id = $(this).attr('data-laudo');

        $.ajax({
            url: "{{route('agendamento.resumo.paciente.laudo', ['agendamento' => 'agendamento_id', 'paciente' => 'paciente_id', 'laudo' => 'laudo_id'])}}".replace('agendamento_id', agendamento_id).replace('paciente_id', paciente_id).replace('laudo_id', laudo_id),
            type: 'POST',
            data: {'_token': '{{ csrf_token() }}'},
            beforeSend: () => {
                $('.loading').css('display', 'block');
                $('.loading').find('.class-loading').addClass('loader')
            },
            success: function(result) {
                $(".modal-resumo").html('');
                $(".modal-resumo").html(result);
                $(".modal-resumo").find('#modalLaudoResumo').modal('show')
                $('.loading').css('display', 'none');
            },
            complete: () => {
                $('.loading').find('.class-loading').removeClass('loader') 
            }

        });
    });
    
    $(".visualizar-resumo-conclusao").on('click', function(){
        var paciente_id = $("#paciente_id").val();
        var agendamento_id = $(this).attr('data-id');
        var conclusao_id = $(this).attr('data-conclusao');

        $.ajax({
            url: "{{route('agendamento.resumo.paciente.conclusao', ['agendamento' => 'agendamento_id', 'paciente' => 'paciente_id', 'conclusao' => 'conclusao_id'])}}".replace('agendamento_id', agendamento_id).replace('paciente_id', paciente_id).replace('conclusao_id', conclusao_id),
            type: 'POST',
            data: {'_token': '{{ csrf_token() }}'},
            beforeSend: () => {
                $('.loading').css('display', 'block');
                $('.loading').find('.class-loading').addClass('loader')
            },
            success: function(result) {
                $(".modal-resumo").html('');
                $(".modal-resumo").html(result);
                $(".modal-resumo").find('#modalConclusaoResumo').modal('show')
                $('.loading').css('display', 'none');
            },
            complete: () => {
                $('.loading').find('.class-loading').removeClass('loader') 
            }

        });
    });
</script>