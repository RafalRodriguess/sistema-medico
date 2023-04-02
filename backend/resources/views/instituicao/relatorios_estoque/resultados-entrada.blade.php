<div class="card">
    <div class="card-header">
        <div class="input-group justify-content-between">
            <h3 class="d-block">Relatório das entradas de estoque
                <span class="post-tooltip" data-toggle="tooltip" data-placement="right" title=""
                    data-original-title="Relatório gerado, clique nos itens abaixo para expandir e exibir as entradas de estoque que foram registradas para cada um dos produtos"><i
                        class="fas fa-question-circle"></i></span>
            </h3>
            <h3 class="d-block ml-3">{{ (new \DateTime($data_inicio))->format('d/m/Y') }} @if ($data_inicio != $data_fim)
                    - {{ (new \DateTime($data_fim))->format('d/m/Y') }}
                @endif
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
                href="#result-items-container-{{ $key }}" role="button" aria-expanded="false"
                aria-controls="result-items-container-{{ $key }}">
                <div class="result-header">
                    <div class="row col-12 align-items-center">
                        <div class="col">
                            <h4 class="my-0 mr-3 my-2">Entrada de estoque #{{ $resultado->id }}</h4>
                        </div>
                        <div class="col-md-8 row">
                            @if (!empty($resultado->fornecedor))
                                <div class="col my-2">
                                    <div class="d-flex align-items-center">
                                        <h4 class="my-0 mr-2">Fornecedor: </h4>
                                        <span>{{ $resultado->fornecedor->nome_fantasia }}</span>
                                    </div>
                                </div>
                            @endif
                            <div class="col my-2">
                                <div class="d-flex align-items-center">
                                    <h4 class="my-0 mr-2">Estoque: </h4>
                                    <span>{{ $resultado->estoque->descricao }}</span>
                                </div>
                            </div>
                            <div class="col my-2">
                                <div class="d-flex align-items-center">
                                    <h4 class="my-0 mr-2">Data: </h4>
                                    @php
                                        $data = new \DateTime($resultado->data_hora_entrada);
                                    @endphp
                                    <span>{{ $data->format('d/m/Y') . ' às ' . $data->format('H:i') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="border bg-light mt-2 collapse" id="result-items-container-{{ $key }}">
                    <div class="result-expand">
                        <div class="result-subitem bg-white">
                            <div>Descricao</div>
                            <div>Lote</div>
                            <div>Quantidade</div>
                            <div>Valor de custo</div>
                            <div>Valor de venda</div>
                        </div>
                        @if (!empty($resultado->estoqueEntradaProdutos))
                            @foreach ($resultado->estoqueEntradaProdutos as $estoqueEntradaProduto)
                                <div class="result-subitem">
                                    <div>{{ $estoqueEntradaProduto->produto->descricao }}</div>
                                    <div>{{ $estoqueEntradaProduto->lote }}</div>
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
