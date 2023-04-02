<div class="modal inmodal fade bs-example-modal-lg" id="modalProntuarioResumo" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">Atendimento #{{$agendamento->id}}<p><small>Realizado em {{ date('d/m/Y H:i', strtotime($agendamento->created_at) ) }}</small></p> </h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <a class="list-group-item list-group-item-action menu-lista" href="#list-item-1">{{ date('d/m/Y', strtotime($prontuario->created_at) ) }} - {{$prontuario->usuario->nome}}</a>
                <div class="item-lista">
                    <h4 id="list-item-1" class="item">Prontuário</h4>
                    @if ($prontuario->prontuario['tipo'] == 'old')
                        @if ($obs)
                            <p class="texto">{!!$obs!!}</p>
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
                        @if (array_key_exists('exame_fisico', $prontuario->prontuario))
                            @if ($prontuario->prontuario['exame_fisico'] != "")    
                                <p><b>Exame fisico:</b></p>
                                <p>{{$prontuario->prontuario['exame_fisico']}}</p>
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
                </div>
            </div>
            <div class="modal-footer">
                <a href="javascript:newPopup('{{route('agendamento.prontuario.imprimirProntuario', $prontuario)}}')">
                    <button type="button" class="btn btn-secondary waves-effect text-left">
                        Imprimir
                    </button>
                </a>
                <button type="button" class="btn btn-danger waves-effect text-left" data-dismiss="modal">Fechar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>