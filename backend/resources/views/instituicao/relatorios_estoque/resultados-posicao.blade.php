<div class="card">
    <div class="card-header">
        <div class="input-group justify-content-between">
            <h3 class="d-block">Relatório da posição de estoque
                <span class="post-tooltip" data-toggle="tooltip" data-placement="right" title=""
                    data-original-title="Relatório gerado, clique nos itens abaixo para expandir e exibir as entradas de estoque que foram registradas para cada um dos produtos"><i
                        class="fas fa-question-circle"></i></span>
            </h3>
        </div>
        <div class="context-buttons hide-print mt-3">
            <button id="expand-all-button" class="btn btn-info waves-effect waves-light mr-2">Expandir todos</button>
            <button id="collapse-all-button" class="btn btn-info waves-effect waves-light">Colapsar todos</button>
        </div>
    </div>
    <div class="card-body">
        @foreach ($resultados as $key => $resultado)
            <a class="result-item border p-2 d-block" data-toggle="collapse"
                href="#result-items-container-{{$key}}" role="button" aria-expanded="false"
                aria-controls="result-items-container-{{$key}}">
                <div class="result-header">
                    <div class="row col-12 align-items-center">
                        <div class="col">
                            <h4 class="my-0 mr-3 my-2">Produto #{{ $resultado->id }} - {{$resultado->descricao}}</h4>
                        </div>
                        <div class="col">
                            <h4 class="my-0 mr-3 my-2">Quantidade em estoque: {{ str_replace('.',',',$resultado->totalEmEstoque()) }} {{ $resultado->unidade ? "({$resultado->unidade->descricao})" : '' }}</h4>
                        </div>
                    </div>
                </div>
                <div class="border bg-light mt-2 collapse" id="result-items-container-{{$key}}">
                    <div class="result-expand">
                        <div class="result-subitem bg-white">
                            <div>Entrada</div>
                            <div>Descricao</div>
                            <div>Lote</div>
                            <div>Estoque</div>
                            <div>Quantidade</div>
                            <div>Valor de custo</div>
                            <div>Valor de venda</div>
                        </div>
                        @if (!empty($resultado->estoqueEntradas))
                            @foreach ($resultado->estoqueEntradas as $estoqueEntradaProduto)
                                <div class="result-subitem">
                                    <div>#{{ $estoqueEntradaProduto->id_entrada }}</div>
                                    <div>{{ $estoqueEntradaProduto->produto->descricao }}</div>
                                    <div>{{ $estoqueEntradaProduto->lote }}</div>
                                    <div>{{ $estoqueEntradaProduto->entradaTrashed->estoqueTrashed->descricao }}</div>
                                    <div>
                                        {{ $estoqueEntradaProduto->quantidade . ' ' . ($estoqueEntradaProduto->produto->unidade ? $estoqueEntradaProduto->produto->unidade->descricao : '') }}
                                    </div>
                                    <div>R$ {{ tobrl($estoqueEntradaProduto->valor_custo) }}</div>
                                    <div>R$ {{ tobrl($estoqueEntradaProduto->valor_venda) }}</div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </a>
        @endforeach
    </div>
</div>
