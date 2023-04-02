<div class="modal inmodal fade bs-example-modal-lg" id="modalConclusaoResumo" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">Atendimento #{{$agendamento->id}}<p><small>Realizado em {{ date('d/m/Y H:i', strtotime($agendamento->created_at) ) }}</small></p> </h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <a class="list-group-item list-group-item-action menu-lista" href="#list-item-1">{{ date('d/m/Y', strtotime($conclusao->created_at) ) }} - {{$conclusao->usuario->nome}}</a>
                <div class="item-lista">
                    <h4 id="list-item-1" class="item">Conclusão</h4>
                    <p class="texto">Motivo: {{$conclusao->motivo->descricao}}</p>
                    <p class="texto">{!!$conclusao->conclusao['obs']!!}</p>
                </div>
            </div>
            <div class="modal-footer">
                <a href="javascript:newPopup('{{route('agendamento.conclusao.imprimirConclusao', $conclusao)}}')">
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