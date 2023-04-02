<style>
    .titulo-center{
        text-align: center
    }
    .cards-borda{
        border: 1px solid;
        border-radius: 5px;
        background: #8080801f;
        color: black;
        padding: 5px;
        margin: 5px;
        border-color: #8080801f;
    }
    .item-lista{
        padding: 15px!important;
    }
</style>

<div class="modal inmodal fade bs-example-modal-lg" id="modalRefracaoResumo" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">Atendimento #{{$agendamento->id}}<p><small>Realizado em {{ date('d/m/Y H:i', strtotime($agendamento->created_at) ) }}</small></p> </h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <a class="list-group-item list-group-item-action menu-lista" href="#list-item-1">{{ date('d/m/Y', strtotime($refracao->created_at) ) }} - {{$refracao->usuario->nome}}</a>
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
                </div>
            </div>
            <div class="modal-footer">
                <a href="javascript:newPopup('{{route('agendamento.refracao.imprimirRefracao', $refracao)}}')">
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