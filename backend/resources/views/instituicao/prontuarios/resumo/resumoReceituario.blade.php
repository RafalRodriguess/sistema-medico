<div class="modal inmodal fade bs-example-modal-lg" id="modalReceituarioResumo" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">Atendimento #{{$agendamento->id}} <p><small>Realizado em {{ date('d/m/Y H:i', strtotime($agendamento->created_at) ) }}</small></p></h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                @if ($receituario->estrutura == "livre")
                    <a class="list-group-item list-group-item-action menu-lista" href="#list-item-1">{{ date('d/m/Y', strtotime($receituario->created_at) ) }} - {{$receituario->usuario->nome}}</a>
                    <div class="item-lista">
                        <h4 id="list-item-1" class="item">Receituário</h4>
                        <p class="texto">{!! ($receituario->receituario['receituario']) !!}</p>
                    </div>
                @else   
                    <a class="list-group-item list-group-item-action menu-lista" href="#list-item-1">{{ date('d/m/Y', strtotime($receituario->created_at) ) }} - {{$receituario->usuario->nome}}</a>
                    <div class="item-lista">
                        <h4 id="list-item-1" class="item">Receituário</h4>
                        @foreach ($receituario->receituario['medicamentos'] as $item)
                            <p class="texto">Medicamento: {{$item['medicamento']['nome']}} - {{$item['quantidade']}}</p>
                            @if ($item['medicamento']['composicao'] != null)
                            <h6 style="margin-left: 10px">Composição:</h6>
                                @foreach ($item['medicamento']['composicao'] as $composicao)
                                    <p style="margin-left: 15px">{{$composicao['substancia']}} ------ {{$composicao['concentracao']}}</p>
                                @endforeach
                            @endif
                            <p></p>
                            <p class="texto">Posologia: {{$item['posologia']}}</p>
                            <hr style="width: 100%">
                        @endforeach
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                <a href="javascript:newPopup('{{route('agendamento.receituario.imprimirReceituario', $receituario)}}')">
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