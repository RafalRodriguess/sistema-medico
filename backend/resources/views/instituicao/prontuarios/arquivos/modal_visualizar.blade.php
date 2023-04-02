<div class="modal fade" id="modal-view-documento" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    {{$arquivo->nome}} 
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body bg-dark p-0">
                <object data="{{ Storage::disk('public')->url($arquivo->diretorio) }}" 
                    width="100%" height="auto" class="m-0 p-0"></object>
            </div>
            <div class="modal-footer">
                <a href="{{route('agendamento.arquivo.baixarArquivo', [$paciente, $arquivo])}}">
                    <button type="button" class="btn btn-success download-arquivo" data-id="{{$arquivo->id}}">Baixar <i class="mdi mdi-download"></i></button>
                </a>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>