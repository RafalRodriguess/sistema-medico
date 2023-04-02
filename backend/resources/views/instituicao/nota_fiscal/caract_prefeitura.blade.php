<div class="modal-header">
    <h4 class="modal-title" id="myLargeModalLabel">Características da prefeitura ({{$instituicao->cidade}}/{{$instituicao->estado}})</h4>
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
</div>

<div class="modal-body">
    <div class="card">
        <div class="card-body ">
            <p><strong>Tipo de autenticacão: </strong> {{App\NfeEnotas::getTipoAutTexto($caracteristicas['tipoAutenticacao'])}}</p>
            <p><strong>Exige assinatura digital: </strong> {{App\NfeEnotas::getAssDigitalTexto($caracteristicas['tipoAutenticacao'])}}</p>
            
            @if($caracteristicas['helpTipoAutenticacao'])
                <div class="border pt-3 pl-2 mb-3">
                    @foreach($caracteristicas['helpTipoAutenticacao'] as $item)
                        <p>{{$item}}</p>
                    @endforeach
                </div>
            @endif

            <p><strong>Suporta Cancelamento: </strong> {{ $caracteristicas['tipoAutenticacao'] ? "Sim" : "Não"}}</p>
            <p><strong>Usa regime especial e tributação: </strong> {{ $caracteristicas['usaRegimeEspecialTributacao'] ? "Sim" : "Não"}}</p>
            <p><strong>Usa codigo de serviço municipal: </strong> {{ $caracteristicas['usaCodigoServicoMunicipal'] ? "Sim" : "Não"}}</p>
            <p><strong>Usa descrição de serviço: </strong> {{ $caracteristicas['usaDescricaoServico'] ? "Sim" : "Não"}}</p>
            <p><strong>Usa CNAE: </strong> {{ $caracteristicas['usaCNAE'] ? "Sim" : "Não"}}</p>
            <p><strong>Usa lista de serviço: </strong> {{ $caracteristicas['usaItemListaServico'] ? "Sim" : "Não"}}</p>
            

            <h5>Ajuda</h5>
            <p><strong>Inscrição municipal: </strong> {{$caracteristicas['helpInscricaoMunicipal']}}</p>
            <p><strong>Regime especial de tributação: </strong> {{$caracteristicas['helpRegimeEspecialTributacao']}}</p>
            <p><strong>Código de serviço municipal: </strong> {{$caracteristicas['helpCodigoServicoMunicipal']}}</p>
            <p><strong>Descrição de serviço municipal: </strong> {{$caracteristicas['helpDescricaoServico']}}</p>
            <p><strong>CNAE: </strong> {{$caracteristicas['helpCNAE']}}</p>
            <p><strong>Item lista de serviço: </strong> {{$caracteristicas['helpItemListaServico']}}</p>
        </div>
    </div>
</div>

<script>

</script>