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
    .item-lista{
        /* border: 1px solid #abababeb;
        padding: 7px; */
        margin-top: 7px;
    }
    .item-lista .item{
        background: #ababab61;
        padding: 5px;
        border-radius: 5px;
    }
    .item-lista .texto{
        padding: 10px;
        text-align: justify;
    }
    .item-lista .imprimir_footer{
        text-align: right;
    }

    .card-header{
        padding: 0.3rem 0 0 0 !important;
    }

    .card-body{
        /* padding: 0px; */
    }

    p{
        margin-bottom: 0rem;
    }
</style>

<ul class="timeline" style="z-index: 0">

    @foreach ($resumos as $item)  
        
        <li class="resumo_{{$item->id}}">
            <div id="accordian-{{$item->id}}">
                <div class="card m-b-0">
                    <a class="card-header text-decoration-none" id="resumos_{{$item->id}}">
                        <button class="btn btn-link" data-toggle="collapse" data-target="#collapse{{$item->id}}" aria-expanded="@if ($tipo_resumo == '2')
                            true
                        @else
                            false
                        @endif" aria-controls="collapse{{$item->id}}" style="width: 100%; text-align: left">
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
                    <div id="collapse{{$item->id}}" class="collapse @if ($tipo_resumo == '2')
                        show
                    @endif" aria-labelledby="resumos_{{$item->id}}" data-parent="#accordian-{{$item->id}}">
                        <div class="card-body" style="padding: 0px 7px 0px 0px;">
                            @can('habilidade_instituicao_sessao', 'visualizar_receituario')
                                @if (sizeof($item->receituario) > 0)
                                    <div id="accordian-receituario-{{$item->id}}" style="margin: 10px;">
                                        <a class="card-header text-decoration-none" id="receituario_resumo_{{$item->id}}" style="padding: 0px">
                                            <button class="btn btn-info" data-toggle="collapse" data-target="#collapse_receituario_{{$item->id}}" aria-expanded="@if ($tipo_resumo == '2')
                                                true
                                            @else
                                                false
                                            @endif" aria-controls="collapse_receituario_{{$item->id}}">
                                                <h5 class="m-b-0" style="color: white; font-size: 15px;">Receituário</h5>
                                            </button>
                                        </a>
                                        <div id="collapse_receituario_{{$item->id}}" class="collapse @if ($tipo_resumo == '2') show @endif" aria-labelledby="resumos_receituario_{{$item->id}}" data-parent="#accordian-receituario-{{$item->id}}">
                                            @foreach ($item->receituario as $keyp => $receituario)
                                                @if($receituario->estrutura == "memed")
                                                    <div class="item-lista">
                                                        <div class="item">#{{$keyp+1}} <button type="button" class="btn btn-xs btn-secondary visualizar-resumo-receituario-memed" aria-haspopup="true" aria-expanded="false"
                                                            data-toggle="tooltip" data-placement="top" data-id="{{$item->id}}" data-receituario="{{$receituario->id}}"
                                                            data-original-title="Visualizar">
                                                            <i class="far fa-list-alt"></i>
                                                        </button></div>
                                                        @foreach ($receituario->receituario as $memed)
                                                            <p class="texto">Medicamento: {{$memed['medicamento']['nome']}} - {{$memed['quantidade']}}</p>
                                                            <p></p>
                                                            <p class="texto">Posologia: {{$memed['posologia']}}</p>
                                                            <hr style="width: 100%">
                                                        @endforeach
                                                        <div class="imprimir_footer">
                                                            <button type="button" class="btn btn-xs btn-secondary visualizar-resumo-receituario-memed" aria-haspopup="true" aria-expanded="false"
                                                                data-toggle="tooltip" data-placement="top" data-id="{{$item->id}}" data-receituario="{{$receituario->id}}"
                                                                data-original-title="Visualizar"> Visualizar Memed
                                                                <i class="far fa-list-alt"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                @else
                                                    @if ($receituario->estrutura == "livre")
                                                        <div class="item-lista">
                                                            <div class="item">#{{$keyp+1}} <a href="javascript:newPopup('{{route('agendamento.receituario.imprimirReceituario', $receituario)}}')">
                                                                <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                                                            data-toggle="tooltip" data-placement="top" data-original-title="Imprimir">
                                                                    <i class="ti-printer"></i>
                                                                </button>
                                                            </a></div>
                                                            <p class="texto">{!! ($receituario->receituario['receituario']) !!}</p>
                                                            <div class="imprimir_footer">
                                                                <a href="javascript:newPopup('{{route('agendamento.receituario.imprimirReceituario', $receituario)}}')">
                                                                    <button type="button" class="btn btn-secondary waves-effect text-left">
                                                                        Imprimir
                                                                    </button>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    @elseif($receituario->estrutura == "formulario")   
                                                        <div class="item-lista">
                                                            <div class="item">#{{$keyp+1}} <a href="javascript:newPopup('{{route('agendamento.receituario.imprimirReceituario', $receituario)}}')">
                                                                <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                                                            data-toggle="tooltip" data-placement="top" data-original-title="Imprimir">
                                                                    <i class="ti-printer"></i>
                                                                </button>
                                                            </a></div>
                                                            @foreach ($receituario->receituario['medicamentos'] as $med)
                                                                <p class="texto">Medicamento: {{$med['medicamento']['nome']}} - {{$med['quantidade']}}</p>
                                                                @if ($med['medicamento']['composicao'] != null)
                                                                <h6 style="margin-left: 10px">Composição:</h6>
                                                                    @foreach ($med['medicamento']['composicao'] as $composicao)
                                                                        <p style="margin-left: 15px">{{$composicao['substancia']}} ------ {{$composicao['concentracao']}}</p>
                                                                    @endforeach
                                                                @endif
                                                                <p></p>
                                                                <p class="texto">Posologia: {{$med['posologia']}}</p>
                                                                <hr style="width: 100%">
                                                            @endforeach
                                                            <div class="imprimir_footer">
                                                                <a href="javascript:newPopup('{{route('agendamento.receituario.imprimirReceituario', $receituario)}}')">
                                                                    <button type="button" class="btn btn-secondary waves-effect text-left">
                                                                        Imprimir
                                                                    </button>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                    
                                @endif
                            @endcan

                            @can('habilidade_instituicao_sessao', 'visualizar_prontuario')
                                
                                @if (sizeof($item->prontuario) > 0)
                                    <div id="accordian-prontuario-{{$item->id}}" style="margin-left: 10px;">
                                        <a class="card-header text-decoration-none" id="prontuario_resumo_{{$item->id}}" style="padding: 0px">
                                            <button class="btn btn-info" data-toggle="collapse" data-target="#collapse-prontuario-{{$item->id}}" aria-expanded="@if ($tipo_resumo == '2')
                                                true
                                            @else
                                                false
                                            @endif" aria-controls="collapse-prontuario-{{$item->id}}">
                                                <h5 class="m-b-0" style="color: white; font-size: 17px;">Prontuário</h5>
                                            </button>
                                        </a>
                                        <div id="collapse-prontuario-{{$item->id}}" class="collapse @if ($tipo_resumo == '2') show @endif" aria-labelledby="resumos_prontuario_{{$item->id}}" data-parent="#accordian-prontuario-{{$item->id}}">
                                            @foreach ($item->prontuario as $keyp => $prontuario)
                                                <div class="item-lista">
                                                    <div class="item">
                                                        #{{$keyp+1}} <a href="javascript:newPopup('{{route('agendamento.prontuario.imprimirProntuario', $prontuario)}}')">
                                                            <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                                                        data-toggle="tooltip" data-placement="top" data-original-title="Imprimir">
                                                                <i class="ti-printer"></i>
                                                            </button>
                                                        </a>
                                                    </div>
                                                    
                                                    @if ($prontuario->prontuario['tipo'] == 'old')
                                                        @if (array_key_exists('obs', $prontuario->prontuario))
                                                            <p class="texto">{!!str_replace("\n", '<br>', $prontuario->prontuario['obs'])!!}</p>
                                                        @else
                                                            <p class="texto">{!!$prontuario->prontuario['obs']!!}</p>
                                                        @endif
                                                    @else
                                                        @if (array_key_exists('queixa_principal', $prontuario->prontuario))
                                                            @if ($prontuario->prontuario['queixa_principal'] != "")    
                                                                <p><b>Queixa principal:</b></p>
                                                                <p>{{$prontuario->prontuario['queixa_principal']}}</p>
                                                                <hr>
                                                            @endif
                                                        @endif
                                                        @if (array_key_exists('h_m_a', $prontuario->prontuario))
                                                            @if ($prontuario->prontuario['h_m_a'] != "")    
                                                                <p><b>H.M.A:</b></p>
                                                                <p>{{$prontuario->prontuario['h_m_a']}}</p>
                                                                <hr>
                                                            @endif
                                                        @endif
                                                        @if (array_key_exists('h_p', $prontuario->prontuario))
                                                            @if ($prontuario->prontuario['h_p'] != "")    
                                                                <p><b>H.P:</b></p>
                                                                <p>{{$prontuario->prontuario['h_p']}}</p>
                                                                <hr>
                                                            @endif
                                                        @endif
                                                        @if (array_key_exists('h_f', $prontuario->prontuario))
                                                            @if ($prontuario->prontuario['h_f'] != "")    
                                                                <p><b>H.F:</b></p>
                                                                <p>{{$prontuario->prontuario['h_f']}}</p>
                                                                <hr>
                                                            @endif
                                                        @endif
                                                        @if (array_key_exists('hipotese_diagnostica', $prontuario->prontuario))
                                                            @if ($prontuario->prontuario['hipotese_diagnostica'] != "")    
                                                                <p><b>Hipótese diagnôstica:</b></p>
                                                                <p>{{$prontuario->prontuario['hipotese_diagnostica']}}</p>
                                                                <hr>
                                                            @endif
                                                        @endif
                                                        @if (array_key_exists('conduta', $prontuario->prontuario))
                                                            @if ($prontuario->prontuario['conduta'] != "")    
                                                                <p><b>Conduta:</b></p>
                                                                <p>{{$prontuario->prontuario['conduta']}}</p>
                                                                <hr>
                                                            @endif
                                                        @endif
                                                        @if (array_key_exists('exame_fisico', $prontuario->prontuario))
                                                            @if ($prontuario->prontuario['exame_fisico'] != "")    
                                                                <p><b>Exame fisico:</b></p>
                                                                <p>{{$prontuario->prontuario['exame_fisico']}}</p>
                                                                <hr>
                                                            @endif
                                                        @endif
                                                        @if (array_key_exists('obs', $prontuario->prontuario))
                                                            @if ($prontuario->prontuario['obs'] != "")    
                                                                <p><b>Observações:</b></p>
                                                                <p>{{$prontuario->prontuario['obs']}}</p>
                                                                <hr>
                                                            @endif
                                                        @endif
                                                        @if (array_key_exists('cid', $prontuario->prontuario))
                                                            @if ($prontuario->prontuario['cid'] != "")    
                                                                @if ($prontuario->prontuario['cid'] != "")
                                                                    <p><b>CID:</b></p>
                                                                    <p>{{$prontuario->prontuario['cid']['texto']}}</p>
                                                                    <hr>
                                                                @endif
                                                            @endif
                                                        @endif
                                                    @endif

                                                    <div class="imprimir_footer">
                                                        <a href="javascript:newPopup('{{route('agendamento.prontuario.imprimirProntuario', $prontuario)}}')">
                                                            <button type="button" class="btn btn-secondary waves-effect text-right">
                                                                Imprimir
                                                            </button>
                                                        </a>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            @endcan

                            @can('habilidade_instituicao_sessao', 'visualizar_refracao')
                                
                                @if (sizeof($item->refracao) > 0)
                                    <div id="accordian-refracao-{{$item->id}}" style="margin: 10px;">
                                        <a class="card-header text-decoration-none" id="refracao_resumo_{{$item->id}}" style="padding: 0px">
                                            <button class="btn btn-info" data-toggle="collapse" data-target="#collapse-refracao-{{$item->id}}" aria-expanded="@if ($tipo_resumo == '2')
                                                true
                                            @else
                                                false
                                            @endif" aria-controls="collapse-refracao-{{$item->id}}">
                                                <h5 class="m-b-0" style="color: white; font-size: 17px;">Refração</h5>
                                            </button>
                                        </a>
                                        <div id="collapse-refracao-{{$item->id}}" class="collapse @if ($tipo_resumo == '2') show @endif" aria-labelledby="resumos_refracao_{{$item->id}}" data-parent="#accordian-refracao-{{$item->id}}">
                                            @foreach ($item->refracao as $keyp => $refracao)
                                                <div class="item-lista">
                                                    <div class="item">#{{$keyp+1}}
                                                        <a href="javascript:newPopup('{{route('agendamento.refracao.imprimirRefracao', $refracao)}}')">
                                                            <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                                                        data-toggle="tooltip" data-placement="top" data-original-title="Imprimir">
                                                                <i class="ti-printer"></i>
                                                            </button>
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="item-lista">
                                                    <div class="titulo-center"><b>Refração Atual</b></div>
                                                    <div><b>Olho direito</b></div>
                                                    <div class="row">
                                                        <div class="col-md-3"><div class="cards-borda">Esférico<p>{{($refracao->refracao['refracao_atual']['ref_atual_od_esferico']) ? $refracao->refracao['refracao_atual']['ref_atual_od_esferico'] : '-' }}</p></div></div>
                                                        <div class="col-md-3"><div class="cards-borda">Cilíndrico<p>{{($refracao->refracao['refracao_atual']['ref_atual_od_cilindrico']) ? $refracao->refracao['refracao_atual']['ref_atual_od_cilindrico'] : '-' }}</p></div></div>
                                                        <div class="col-md-3"><div class="cards-borda">Eixo<p>{{($refracao->refracao['refracao_atual']['ref_atual_od_eixo']) ? $refracao->refracao['refracao_atual']['ref_atual_od_eixo'] : '-' }}</p></div></div>
                                                        <div class="col-md-3"><div class="cards-borda">Adição<p>{{($refracao->refracao['refracao_atual']['ref_atual_od_adicao']) ? $refracao->refracao['refracao_atual']['ref_atual_od_adicao'] : '-' }}</p></div></div>
                                                    </div>
                                                    <div><b>Olho esquerdo</b></div>
                                                    <div class="row">
                                                        <div class="col-md-3"><div class="cards-borda">Esférico<p>{{($refracao->refracao['refracao_atual']['ref_atual_oe_esferico']) ? $refracao->refracao['refracao_atual']['ref_atual_oe_esferico'] : '-' }}</p></div></div>
                                                        <div class="col-md-3"><div class="cards-borda">Cilíndrico<p>{{($refracao->refracao['refracao_atual']['ref_atual_oe_cilindrico']) ? $refracao->refracao['refracao_atual']['ref_atual_oe_cilindrico'] : '-' }}</p></div></div>
                                                        <div class="col-md-3"><div class="cards-borda">Eixo<p>{{($refracao->refracao['refracao_atual']['ref_atual_oe_eixo']) ? $refracao->refracao['refracao_atual']['ref_atual_oe_eixo'] : '-' }}</p></div></div>
                                                        <div class="col-md-3"><div class="cards-borda">Adição<p>{{($refracao->refracao['refracao_atual']['ref_atual_oe_adicao']) ? $refracao->refracao['refracao_atual']['ref_atual_oe_adicao'] : '-' }}</p></div></div>
                                                    </div>
                                                    <div><b>Observação</b></div>
                                                    <div class="row">
                                                        <div class="col-md-12"><div class="cards-borda"><p>{{($refracao->refracao['refracao_atual']['ref_atual_obs']) ? $refracao->refracao['refracao_atual']['ref_atual_obs'] : '-' }}</p></div></div>
                                                    </div>
                                                </div>
                                                <div class="item-lista">
                                                    <div class="titulo-center"><b>Acuidade visual</b></div>
                                                    <div><b>Olho direito</b></div>
                                                    <div class="row">
                                                        <div class="col-md-6"><div class="cards-borda">S/C<p @if(isset($refracao->refracao['acuidade_visual']['acuidade_od_sc_ck'])) style="margin: 0px;" @endif>{{(isset($refracao->refracao['acuidade_visual']['acuidade_od_sc'])) ? $refracao->refracao['acuidade_visual']['acuidade_od_sc'] : '-' }}</p>@if(isset($refracao->refracao['acuidade_visual']['acuidade_od_sc_ck'])) <p>Parcial</p>@endif</div></div>
                                                        <div class="col-md-6"><div class="cards-borda">C/C<p @if(isset($refracao->refracao['acuidade_visual']['acuidade_od_cc_ck'])) style="margin: 0px;" @endif>{{(isset($refracao->refracao['acuidade_visual']['acuidade_od_cc'])) ? $refracao->refracao['acuidade_visual']['acuidade_od_cc'] : '-' }}</p>@if(isset($refracao->refracao['acuidade_visual']['acuidade_od_cc_ck'])) <p>Parcial</p>@endif</div></div>
                                                    </div>
                                                    <div><b>Olho esquerdo</b></div>
                                                    <div class="row">
                                                        <div class="col-md-6"><div class="cards-borda">S/C<p @if(isset($refracao->refracao['acuidade_visual']['acuidade_oe_sc_ck'])) style="margin: 0px;" @endif>{{(isset($refracao->refracao['acuidade_visual']['acuidade_oe_sc'])) ? $refracao->refracao['acuidade_visual']['acuidade_oe_sc'] : '-' }}</p>@if(isset($refracao->refracao['acuidade_visual']['acuidade_oe_sc_ck'])) <p>Parcial</p>@endif</div></div>
                                                        <div class="col-md-6"><div class="cards-borda">C/C<p @if(isset($refracao->refracao['acuidade_visual']['acuidade_oe_cc_ck'])) style="margin: 0px;" @endif>{{(isset($refracao->refracao['acuidade_visual']['acuidade_oe_cc'])) ? $refracao->refracao['acuidade_visual']['acuidade_oe_cc'] : '-' }}</p>@if(isset($refracao->refracao['acuidade_visual']['acuidade_oe_cc_ck'])) <p>Parcial</p>@endif</div></div>
                                                    </div>
                                                </div>
                                                <div class="item-lista">
                                                    <div class="titulo-center"><b>Refração estática</b></div>
                                                    <div class="titulo-center"><b>Longe</b></div>
                                                    <div><b>Olho direito</b></div>
                                                    <div class="row">
                                                        <div class="col-md-3"><div class="cards-borda">Esférico<p>{{($refracao->refracao['refracao_estatica']['ref_estatica_l_od_esferico']) ? $refracao->refracao['refracao_estatica']['ref_estatica_l_od_esferico'] : '-' }}</p></div></div>
                                                        <div class="col-md-3"><div class="cards-borda">Cilíndrico<p>{{($refracao->refracao['refracao_estatica']['ref_estatica_l_od_cilindrico']) ? $refracao->refracao['refracao_estatica']['ref_estatica_l_od_cilindrico'] : '-' }}</p></div></div>
                                                        <div class="col-md-3"><div class="cards-borda">Eixo<p>{{($refracao->refracao['refracao_estatica']['ref_estatica_l_od_eixo']) ? $refracao->refracao['refracao_estatica']['ref_estatica_l_od_eixo'] : '-' }}</p></div></div>
                                                        <div class="col-md-3"><div class="cards-borda">Av<p @if(isset($refracao->refracao['refracao_estatica']['ref_estatica_l_od_av_ck'])) style="margin: 0px;" @endif>{{(isset($refracao->refracao['refracao_estatica']['ref_estatica_l_od_av'])) ? $refracao->refracao['refracao_estatica']['ref_estatica_l_od_av'] : '-' }}</p>@if(isset($refracao->refracao['refracao_estatica']['ref_estatica_l_od_av_ck'])) <p>Parcial</p>@endif</div></div>
                                                    </div>
                                                    <div><b>Olho esquerdo</b></div>
                                                    <div class="row">
                                                        <div class="col-md-3"><div class="cards-borda">Esférico<p>{{($refracao->refracao['refracao_estatica']['ref_estatica_l_oe_esferico']) ? $refracao->refracao['refracao_estatica']['ref_estatica_l_oe_esferico'] : '-' }}</p></div></div>
                                                        <div class="col-md-3"><div class="cards-borda">Cilíndrico<p>{{($refracao->refracao['refracao_estatica']['ref_estatica_l_oe_cilindrico']) ? $refracao->refracao['refracao_estatica']['ref_estatica_l_oe_cilindrico'] : '-' }}</p></div></div>
                                                        <div class="col-md-3"><div class="cards-borda">Eixo<p>{{($refracao->refracao['refracao_estatica']['ref_estatica_l_oe_eixo']) ? $refracao->refracao['refracao_estatica']['ref_estatica_l_oe_eixo'] : '-' }}</p></div></div>
                                                        <div class="col-md-3"><div class="cards-borda">Av<p @if(isset($refracao->refracao['refracao_estatica']['ref_estatica_l_oe_av_ck'])) style="margin: 0px;" @endif>{{(isset($refracao->refracao['refracao_estatica']['ref_estatica_l_oe_av'])) ? $refracao->refracao['refracao_estatica']['ref_estatica_l_oe_av'] : '-' }}</p>@if(isset($refracao->refracao['refracao_estatica']['ref_estatica_l_oe_av_ck'])) <p>Parcial</p>@endif</div></div>
                                                    </div>
                                                    <div class="titulo-center"><b>Perto</b></div>
                                                    <div><b>Olho direito</b></div>
                                                    <div class="row">
                                                        <div class="col-md-6"><div class="cards-borda">Adição<p>{{(isset($refracao->refracao['refracao_estatica']['ref_estatica_p_od_adicao'])) ? $refracao->refracao['refracao_estatica']['ref_estatica_p_od_adicao'] : '-' }}</p></div></div>
                                                        <div class="col-md-6"><div class="cards-borda">Jaeger<p>{{(isset($refracao->refracao['refracao_estatica']['ref_estatica_p_od_jaeger'])) ? $refracao->refracao['refracao_estatica']['ref_estatica_p_od_jaeger'] : '-' }}</p></div></div>
                                                    </div>
                                                    <div><b>Olho esquerdo</b></div>
                                                    <div class="row">
                                                        <div class="col-md-6"><div class="cards-borda">Adição<p>{{(isset($refracao->refracao['refracao_estatica']['ref_estatica_p_oe_adicao'])) ? $refracao->refracao['refracao_estatica']['ref_estatica_p_oe_adicao'] : '-' }}</p></div></div>
                                                        <div class="col-md-6"><div class="cards-borda">Jaeger<p>{{(isset($refracao->refracao['refracao_estatica']['ref_estatica_p_oe_jaeger'])) ? $refracao->refracao['refracao_estatica']['ref_estatica_p_oe_jaeger'] : '-' }}</p></div></div>
                                                    </div>
                                                </div>
                                                <div class="item-lista">
                                                    <div class="titulo-center"><b>Refração dinâmica</b></div>
                                                    <div class="titulo-center"><b>Longe</b></div>
                                                    <div><b>Olho direito</b></div>
                                                    <div class="row">
                                                        <div class="col-md-3"><div class="cards-borda">Esférico<p>{{($refracao->refracao['refracao_dinamica']['ref_dinamica_l_od_esferico']) ? $refracao->refracao['refracao_dinamica']['ref_dinamica_l_od_esferico'] : '-' }}</p></div></div>
                                                        <div class="col-md-3"><div class="cards-borda">Cilíndrico<p>{{($refracao->refracao['refracao_dinamica']['ref_dinamica_l_od_cilindrico']) ? $refracao->refracao['refracao_dinamica']['ref_dinamica_l_od_cilindrico'] : '-' }}</p></div></div>
                                                        <div class="col-md-3"><div class="cards-borda">Eixo<p>{{($refracao->refracao['refracao_dinamica']['ref_dinamica_l_od_eixo']) ? $refracao->refracao['refracao_dinamica']['ref_dinamica_l_od_eixo'] : '-' }}</p></div></div>
                                                        <div class="col-md-3"><div class="cards-borda">Av<p @if(isset($refracao->refracao['refracao_dinamica']['ref_dinamica_l_od_av'])) style="margin: 0px;" @endif>{{(isset($refracao->refracao['refracao_dinamica']['ref_dinamica_l_od_av_ck'])) ? $refracao->refracao['refracao_dinamica']['ref_dinamica_l_od_av'] : '-' }}</p>@if(isset($refracao->refracao['refracao_dinamica']['ref_dinamica_l_od_av_ck'])) <p>Parcial</p>@endif</div></div>
                                                    </div>
                                                    <div><b>Olho esquerdo</b></div>
                                                    <div class="row">
                                                        <div class="col-md-3"><div class="cards-borda">Esférico<p>{{($refracao->refracao['refracao_dinamica']['ref_dinamica_l_oe_esferico']) ? $refracao->refracao['refracao_dinamica']['ref_dinamica_l_oe_esferico'] : '-' }}</p></div></div>
                                                        <div class="col-md-3"><div class="cards-borda">Cilíndrico<p>{{($refracao->refracao['refracao_dinamica']['ref_dinamica_l_oe_cilindrico']) ? $refracao->refracao['refracao_dinamica']['ref_dinamica_l_oe_cilindrico'] : '-' }}</p></div></div>
                                                        <div class="col-md-3"><div class="cards-borda">Eixo<p>{{($refracao->refracao['refracao_dinamica']['ref_dinamica_l_oe_eixo']) ? $refracao->refracao['refracao_dinamica']['ref_dinamica_l_oe_eixo'] : '-' }}</p></div></div>
                                                        <div class="col-md-3"><div class="cards-borda">Av<p @if(isset($refracao->refracao['refracao_dinamica']['ref_dinamica_l_oe_av'])) style="margin: 0px;" @endif>{{(isset($refracao->refracao['refracao_dinamica']['ref_dinamica_l_oe_av_ck'])) ? $refracao->refracao['refracao_dinamica']['ref_dinamica_l_oe_av'] : '-' }}</p>@if(isset($refracao->refracao['refracao_dinamica']['ref_dinamica_l_oe_av_ck'])) <p>Parcial</p>@endif</div></div>
                                                    </div>
                                                    <div class="titulo-center"><b>Perto</b></div>
                                                    <div><b>Olho direito</b></div>
                                                    <div class="row">
                                                        <div class="col-md-6"><div class="cards-borda">Adição<p>{{(isset($refracao->refracao['refracao_dinamica']['ref_dinamica_p_od_adicao'])) ? $refracao->refracao['refracao_dinamica']['ref_dinamica_p_od_adicao'] : '-' }}</p></div></div>
                                                        <div class="col-md-6"><div class="cards-borda">Jaeger<p>{{(isset($refracao->refracao['refracao_dinamica']['ref_dinamica_p_od_jaeger'])) ? $refracao->refracao['refracao_dinamica']['ref_dinamica_p_od_jaeger'] : '-' }}</p></div></div>
                                                    </div>
                                                    <div><b>Olho esquerdo</b></div>
                                                    <div class="row">
                                                        <div class="col-md-6"><div class="cards-borda">Adição<p>{{(isset($refracao->refracao['refracao_dinamica']['ref_dinamica_p_oe_adicao'])) ? $refracao->refracao['refracao_dinamica']['ref_dinamica_p_oe_adicao'] : '-' }}</p></div></div>
                                                        <div class="col-md-6"><div class="cards-borda">Jaeger<p>{{(isset($refracao->refracao['refracao_dinamica']['ref_dinamica_p_oe_jaeger'])) ? $refracao->refracao['refracao_dinamica']['ref_dinamica_p_oe_jaeger'] : '-' }}</p></div></div>
                                                    </div>
                                                </div>
                                                <div class="item-lista">
                                                    <div class="titulo-center"><b>Prescrição de óculos</b></div>
                                                    <div><b>Olho direito</b></div>
                                                    <div class="row">
                                                        <div class="col-md-3"><div class="cards-borda">Esférico<p>{{($refracao->refracao['prescricao_oculos']['prescricao_od_esferico']) ? $refracao->refracao['prescricao_oculos']['prescricao_od_esferico'] : '-' }}</p></div></div>
                                                        <div class="col-md-3"><div class="cards-borda">Cilíndrico<p>{{($refracao->refracao['prescricao_oculos']['prescricao_od_cilindrico']) ? $refracao->refracao['prescricao_oculos']['prescricao_od_cilindrico'] : '-' }}</p></div></div>
                                                        <div class="col-md-3"><div class="cards-borda">Eixo<p>{{($refracao->refracao['prescricao_oculos']['prescricao_od_eixo']) ? $refracao->refracao['prescricao_oculos']['prescricao_od_eixo'] : '-' }}</p></div></div>
                                                        <div class="col-md-3"><div class="cards-borda">Adição<p>{{($refracao->refracao['prescricao_oculos']['prescricao_od_adicao']) ? $refracao->refracao['prescricao_oculos']['prescricao_od_adicao'] : '-' }}</p></div></div>
                                                    </div>
                                                    <div><b>Olho esquerdo</b></div>
                                                    <div class="row">
                                                        <div class="col-md-3"><div class="cards-borda">Esférico<p>{{($refracao->refracao['prescricao_oculos']['prescricao_oe_esferico']) ? $refracao->refracao['prescricao_oculos']['prescricao_oe_esferico'] : '-' }}</p></div></div>
                                                        <div class="col-md-3"><div class="cards-borda">Cilíndrico<p>{{($refracao->refracao['prescricao_oculos']['prescricao_oe_cilindrico']) ? $refracao->refracao['prescricao_oculos']['prescricao_oe_cilindrico'] : '-' }}</p></div></div>
                                                        <div class="col-md-3"><div class="cards-borda">Eixo<p>{{($refracao->refracao['prescricao_oculos']['prescricao_oe_eixo']) ? $refracao->refracao['prescricao_oculos']['prescricao_oe_eixo'] : '-' }}</p></div></div>
                                                        <div class="col-md-3"><div class="cards-borda">Adição<p>{{($refracao->refracao['prescricao_oculos']['prescricao_oe_adicao']) ? $refracao->refracao['prescricao_oculos']['prescricao_oe_adicao'] : '-' }}</p></div></div>
                                                    </div>
                                                    <div><b>DP (mm)</b></div>
                                                    <div class="row">
                                                        <div class="col-md-12"><div class="cards-borda"><p>{{($refracao->refracao['prescricao_oculos']['prescricao_dp']) ? $refracao->refracao['prescricao_oculos']['prescricao_dp'] : '-' }}</p></div></div>
                                                    </div>
                                                    <div><b>Observação</b></div>
                                                    <div class="row">
                                                        <div class="col-md-12"><div class="cards-borda"><p>{{($refracao->refracao['prescricao_oculos']['prescricao_obs']) ? $refracao->refracao['prescricao_oculos']['prescricao_obs'] : '-' }}</p></div></div>
                                                    </div>
                                                    <div class="imprimir_footer">
                                                        <a href="javascript:newPopup('{{route('agendamento.refracao.imprimirRefracao', $refracao)}}')">
                                                            <button type="button" class="btn btn-secondary waves-effect text-left">
                                                                Imprimir
                                                            </button>
                                                        </a>
                                                    </div>
                                                </div>
                                            @endforeach
                                            
                                        </div>
                                    </div>
                                @endif
                            @endcan

                            @can('habilidade_instituicao_sessao', 'visualizar_atestado')
                                
                                @if (sizeof($item->atestado) > 0)
                                    <div id="accordian-atestado-{{$item->id}}" style="margin: 10px;">
                                        <a class="card-header text-decoration-none" id="atestado_resumo_{{$item->id}}" style="padding: 0px">
                                            <button class="btn btn-info" data-toggle="collapse" data-target="#collapse-atestado-{{$item->id}}" aria-expanded="@if ($tipo_resumo == '2')
                                                true
                                            @else
                                                false
                                            @endif" aria-controls="collapse-atestado-{{$item->id}}">
                                                <h5 class="m-b-0" style="color: white; font-size: 17px;">Atestados</h5>
                                            </button>
                                        </a>
                                        <div id="collapse-atestado-{{$item->id}}" class="collapse @if ($tipo_resumo == '2') show @endif" aria-labelledby="resumos_atestado_{{$item->id}}" data-parent="#accordian-atestado-{{$item->id}}">
                                            @foreach ($item->atestado as $keyp => $atestado)
                                                <div class="item-lista">
                                                    <div class="item">#{{$keyp+1}} <a href="javascript:newPopup('{{route('agendamento.atestado.imprimirAtestado', $atestado)}}')">
                                                        <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                                                    data-toggle="tooltip" data-placement="top" data-original-title="Imprimir">
                                                            <i class="ti-printer"></i>
                                                        </button>
                                                    </a></div>
                                                    <p class="texto">{!!$atestado->atestado['obs']!!}</p>
                                                    <div class="imprimir_footer">
                                                        <a href="javascript:newPopup('{{route('agendamento.atestado.imprimirAtestado', $atestado)}}')">
                                                            <button type="button" class="btn btn-secondary waves-effect text-left">
                                                                Imprimir
                                                            </button>
                                                        </a>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            @endcan

                            @can('habilidade_instituicao_sessao', 'visualizar_relatorio')
                                
                                @if (sizeof($item->relatorio) > 0)
                                    <div id="accordian-relatorio-{{$item->id}}" style="margin: 10px;">
                                        <a class="card-header text-decoration-none" id="relatorio_resumo_{{$item->id}}" style="padding: 0px">
                                            <button class="btn btn-info" data-toggle="collapse" data-target="#collapse-relatorio-{{$item->id}}" aria-expanded="@if ($tipo_resumo == '2')
                                                true
                                            @else
                                                false
                                            @endif" aria-controls="collapse-relatorio-{{$item->id}}">
                                                <h5 class="m-b-0" style="color: white; font-size: 17px;">Relatórios</h5>
                                            </button>
                                        </a>
                                        <div id="collapse-relatorio-{{$item->id}}" class="collapse @if ($tipo_resumo == '2') show @endif" aria-labelledby="resumos_relatorio_{{$item->id}}" data-parent="#accordian-relatorio-{{$item->id}}">
                                            @foreach ($item->relatorio as $keyp => $relatorio)
                                                <div class="item-lista">
                                                    <div id="list-item-1" class="item">#{{$keyp+1}} <a href="javascript:newPopup('{{route('agendamento.relatorio.imprimirRelatorio', $relatorio)}}')">
                                                        <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                                                    data-toggle="tooltip" data-placement="top" data-original-title="Imprimir">
                                                            <i class="ti-printer"></i>
                                                        </button>
                                                    </a></div>
                                                    <p class="texto">{!!$relatorio->relatorio['obs']!!}</p>
                                                    <div class="imprimir_footer">
                                                        <a href="javascript:newPopup('{{route('agendamento.relatorio.imprimirRelatorio', $relatorio)}}')">
                                                            <button type="button" class="btn btn-secondary waves-effect text-left">
                                                                Imprimir
                                                            </button>
                                                        </a>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            @endcan

                            @can('habilidade_instituicao_sessao', 'visualizar_exame')
                                
                                @if (sizeof($item->exame) > 0)
                                    <div id="accordian-exame-{{$item->id}}" style="margin: 10px;">
                                        <a class="card-header text-decoration-none" id="exame_resumo_{{$item->id}}" style="padding: 0px">
                                            <button class="btn btn-info" data-toggle="collapse" data-target="#collapse-exame-{{$item->id}}" aria-expanded="@if ($tipo_resumo == '2')
                                                true
                                            @else
                                                false
                                            @endif" aria-controls="collapse-exame-{{$item->id}}">
                                                <h5 class="m-b-0" style="color: white; font-size: 17px;">Exames</h5>
                                            </button>
                                        </a>
                                        <div id="collapse-exame-{{$item->id}}" class="collapse @if ($tipo_resumo == '2') show @endif" aria-labelledby="resumos_exame_{{$item->id}}" data-parent="#accordian-exame-{{$item->id}}">
                                            @foreach ($item->exame as $keyp => $exame)
                                                <div class="item-lista">
                                                    <h4 id="list-item-1" class="item">#{{$keyp+1}} <a href="javascript:newPopup('{{route('agendamento.exame.imprimirExame', $exame)}}')">
                                                        <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                                                    data-toggle="tooltip" data-placement="top" data-original-title="Imprimir">
                                                            <i class="ti-printer"></i>
                                                        </button>
                                                    </a></h4>
                                                    <p class="texto">{!!$exame->exame['obs']!!}</p>
                                                    <div class="imprimir_footer">
                                                        <a href="javascript:newPopup('{{route('agendamento.exame.imprimirExame', $exame)}}')">
                                                            <button type="button" class="btn btn-secondary waves-effect text-left">
                                                                Imprimir
                                                            </button>
                                                        </a>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            @endcan
                            @can('habilidade_instituicao_sessao', 'visualizar_encaminhamento')
                                
                                @if (sizeof($item->encaminhamento) > 0)
                                    <div id="accordian-encaminhamento-{{$item->id}}" style="margin: 10px;">
                                        <a class="card-header text-decoration-none" id="encaminhamento_resumo_{{$item->id}}" style="padding: 0px">
                                            <button class="btn btn-info" data-toggle="collapse" data-target="#collapse-encaminhamento-{{$item->id}}" aria-expanded="@if ($tipo_resumo == '2')
                                                true
                                            @else
                                                false
                                            @endif" aria-controls="collapse-encaminhamento-{{$item->id}}">
                                                <h5 class="m-b-0" style="color: white; font-size: 17px;">Encaminhamentos</h5>
                                            </button>
                                        </a>
                                        <div id="collapse-encaminhamento-{{$item->id}}" class="collapse @if ($tipo_resumo == '2') show @endif" aria-labelledby="resumos_encaminhamento_{{$item->id}}" data-parent="#accordian-encaminhamento-{{$item->id}}">
                                            @foreach ($item->encaminhamento as $keyp => $encaminhamento)
                                                <div class="item-lista">
                                                    <h4 id="list-item-1" class="item">#{{$keyp+1}} <a href="javascript:newPopup('{{route('agendamento.encaminhamento.imprimirEncaminhamento', $encaminhamento)}}')">
                                                        <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                                                    data-toggle="tooltip" data-placement="top" data-original-title="Imprimir">
                                                            <i class="ti-printer"></i>
                                                        </button>
                                                    </a></h4>
                                                    <p class="texto">{!!$encaminhamento->encaminhamento['obs']!!}</p>
                                                    <div class="imprimir_footer">
                                                        <a href="javascript:newPopup('{{route('agendamento.encaminhamento.imprimirEncaminhamento', $encaminhamento)}}')">
                                                            <button type="button" class="btn btn-secondary waves-effect text-left">
                                                                Imprimir
                                                            </button>
                                                        </a>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            @endcan
                            @can('habilidade_instituicao_sessao', 'visualizar_laudo')
                                
                                @if (sizeof($item->laudo) > 0)
                                    <div id="accordian-laudo-{{$item->id}}" style="margin: 10px;">
                                        <a class="card-header text-decoration-none" id="laudo_resumo_{{$item->id}}" style="padding: 0px">
                                            <button class="btn btn-info" data-toggle="collapse" data-target="#collapse-laudo-{{$item->id}}" aria-expanded="@if ($tipo_resumo == '2')
                                                true
                                            @else
                                                false
                                            @endif" aria-controls="collapse-laudo-{{$item->id}}">
                                                <h5 class="m-b-0" style="color: white; font-size: 17px;">Laudos</h5>
                                            </button>
                                        </a>
                                        <div id="collapse-laudo-{{$item->id}}" class="collapse @if ($tipo_resumo == '2') show @endif" aria-labelledby="resumos_laudo_{{$item->id}}" data-parent="#accordian-laudo-{{$item->id}}">
                                            @foreach ($item->laudo as $keyp => $laudo)
                                                <div class="item-lista">
                                                    <h4 id="list-item-1" class="item">#{{$keyp+1}} <a href="javascript:newPopup('{{route('agendamento.laudo.imprimirLaudo', $laudo)}}')">
                                                        <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                                                    data-toggle="tooltip" data-placement="top" data-original-title="Imprimir">
                                                            <i class="ti-printer"></i>
                                                        </button>
                                                    </a></h4>
                                                    <p class="texto">{!!$laudo->laudo['obs']!!}</p>
                                                    <div class="imprimir_footer">
                                                        <a href="javascript:newPopup('{{route('agendamento.laudo.imprimirLaudo', $laudo)}}')">
                                                            <button type="button" class="btn btn-secondary waves-effect text-left">
                                                                Imprimir
                                                            </button>
                                                        </a>
                                                    </div>
                                                </div>
                                            @endforeach
                                            
                                        </div>
                                    </div>
                                @endif
                            @endcan
                            @can('habilidade_instituicao_sessao', 'visualizar_conclusao')
                                
                                @if (sizeof($item->conclusao) > 0)
                                    <div id="accordian-conclusao-{{$item->id}}" style="margin: 10px;">
                                        <a class="card-header text-decoration-none" id="conclusao_resumo_{{$item->id}}" style="padding: 0px">
                                            <button class="btn btn-info" data-toggle="collapse" data-target="#collapse-conclusao-{{$item->id}}" aria-expanded="@if ($tipo_resumo == '2')
                                                true
                                            @else
                                                false
                                            @endif" aria-controls="collapse-conclusao-{{$item->id}}">
                                                <h5 class="m-b-0" style="color: white; font-size: 17px;">Conclusão</h5>
                                            </button>
                                        </a>
                                        <div id="collapse-conclusao-{{$item->id}}" class="collapse @if ($tipo_resumo == '2') show @endif" aria-labelledby="resumos_conclusao_{{$item->id}}" data-parent="#accordian-conclusao-{{$item->id}}">
                                            @foreach ($item->conclusao as $keyp => $conclusao)
                                                <div class="item-lista">
                                                    <div class="item">#{{$keyp+1}} <a href="javascript:newPopup('{{route('agendamento.conclusao.imprimirConclusao', $conclusao)}}')">
                                                        <button type="button" class="btn btn-xs btn-secondary" aria-haspopup="true" aria-expanded="false"
                                                                    data-toggle="tooltip" data-placement="top" data-original-title="Imprimir">
                                                            <i class="ti-printer"></i>
                                                        </button>
                                                    </a></div>
                                                    <p class="texto">Motivo: {{$conclusao->motivo->descricao}}</p>
                                                    <p class="texto">{!!$conclusao->conclusao['obs']!!}</p>
                                                    <div class="imprimir_footer">
                                                        <a href="javascript:newPopup('{{route('agendamento.conclusao.imprimirConclusao', $conclusao)}}')">
                                                            <button type="button" class="btn btn-secondary waves-effect text-left">
                                                                Imprimir
                                                            </button>
                                                        </a>
                                                    </div>
                                                </div>
                                            @endforeach
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