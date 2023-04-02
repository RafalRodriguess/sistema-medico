<div id="modalVisualizar" class="modal fade bs-example-modal-lg" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Concluir procedimento</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="concluirProcedimentoOdontologicoModal">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="orcamento_id" id="orcamento_id" value="{{($orcamento) ? $orcamento->id : ''}}">

                    <table class="table color-table info-table">
                        <thead>
                            <tr>
                                <th>Aprovado</th>
                                <th>Dente</th>
                                <th>Procedimento</th>
                                <th>Região</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orcamento->itens as $key => $item)
                                <tr @if ($item->concluido == 1)
                                    style="background: #02ff024d"
                                @endif>
                                    <td style="text-align: center">
                                        <div class="row" style="height: 35px;">
                                            <div class="form-group dados_parcela col-md-2" style="margin-top: 7px;">
                                                <input type="checkbox" name="orcamento[]" id="orcamento_{{$item->id}}" value="{{$item->id}}" class="filled-in" @if ($item->concluido == 1)
                                                checked disabled
                                                @endif><label for="orcamento_{{$item->id}}" ></label>
                                            </div>
                                            <div class="col-md-2">
                                                @can('habilidade_instituicao_sessao', 'cancelar_procedimento_concluido_odontologico')
                                                    @if ($item->concluido == 1)
                                                        <button type="button" class="btn btn-outline-danger cancelar_aprovacao_procedimento_modal" style="border: 0px;
                                                        box-shadow: none" data-id="{{$item->id}}"><i class="fas fa-times-circle"></i></button>
                                                    @endif
                                                @endcan
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{$item->dente_id}}</td>
                                    <td>{{$item->procedimentos->procedimentoInstituicao->procedimento->descricao}}</td>
                                    <td>
                                        @if ($item->regiao)
                                            {{($item->regiao) ? $item->regiao->descricao : ''}}
                                        @elseif(count($item->regiaoProcedimento) > 0)
                                            @foreach ($item->regiaoProcedimento as $keyR => $regiao)
                                                @if ($keyR == 0)
                                                    {{$regiao->descricao}}
                                                @else
                                                        / {{$regiao->descricao}}
                                                @endif
                                            @endforeach
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                        
                    
                </div>
                <div class="modal-footer">
                    {{-- <div class="row"> --}}
                        <div class="form-groupn col-md-6 text-left pb-2">
                            <label class="form-control-label">Beneficiario</label>
                            <select name="prestador_id" id="prestador_id" class="form-control select2ConcluirOdontologico" style="width: 100%">
                                <option value="">Selecione um beneficiario</option>
                                @foreach ($prestadores as $item)
                                    <option value="{{$item->id}}" >{{$item->nome}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-groupn col-md-6 text-right pb-2">
                            <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Fechar</button>
                            <button type="button" class="btn btn-info waves-effect nav-link concluir_procedimento_odontologico_modal">Salvar</button>
                        </div>
                    {{-- </div> --}}
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $(".select2ConcluirOdontologico").select2()
    })
</script>