@extends('instituicao.layout')

@section('conteudo')
    @component('components/page-title', [
        'titulo' => "Editar Parcelas #{$instituicao->id} {$instituicao->nome}",
        'breadcrumb' => [
            'Parcelas' => route('instituicao.parcelas.edit'),
            'Editar',
        ],
    ])
    @endcomponent

    <div class="row">
        <div class="col-6">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('instituicao.parcelas.update') }}" method="post">
                    @method('put')
                    @csrf
                        <h4 class="card-title">Parcelas</h4>
                        <div class="form-group">
                            <label class="control-label">Número máximo de parcelas</label>
                            <input type="text" id="max_parcela" name="max_parcela" value="{{ old('max_parcela', $instituicao->max_parcela) }}" class="form-control @if($errors->has('max_parcela')) form-control-danger @endif">
                        </div>
                        <div class="form-group">
                            <label class="control-label">Número de parcelas gratuitas</label>
                            <input type="text" id="free_parcela" name="free_parcela" value="{{ old('free_parcela', $instituicao->free_parcela) }}" class="form-control @if($errors->has('free_parcela')) form-control-danger @endif">
                        </div>
                        <div class="form-group">
                            <label class="control-label">Juro por parcela</label>
                            <input type="text" id="valor_parcela" name="valor_parcela" value="{{ old('valor_parcela', $instituicao->valor_parcela) }}" class="form-control @if($errors->has('valor_parcela')) form-control-danger @endif">
                        </div>
                        <div class="form-group">
                            <label class="control-label">Valor mínimo parcela</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">R$</span>
                                </div>
                                <input type="text" id="valor_minimo" alt="money" name="valor_minimo" value="{{ old('valor_minimo', $instituicao->valor_minimo) }}" class="form-control @if($errors->has('valor_minimo')) form-control-danger @endif">
                            </div>
                        </div>
                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div><p class="text-muted m-t-0"><code><h3>Taxas:</h3></code></p>
            <p class="text-muted m-t-0"><code>A partir de 1 parcela: </code>Serão descontados <code>{{str_replace('.',',',$instituicao->taxa_tectotum + 3.79)}}%</code> + <code>R$1,20</code> por venda no cartão</p>
            <p class="text-muted m-t-0"><code>A partir de 2 parcela: </code>Serão descontados <code>{{str_replace('.',',',$instituicao->taxa_tectotum + 4.19)}}%</code> + <code>R$1,20</code> por venda no cartão</p>
            <p class="text-muted m-t-0"><code>A partir de 7 parcela: </code>Serão descontados <code>{{str_replace('.',',',$instituicao->taxa_tectotum + 4.59)}}%</code> + <code>R$1,20</code> por venda no cartão</p>
            </div>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Simulação</h4>

                        <div class="form-group">
                            <label class="control-label">Valor da compra</label>
                            <input type="text" id="compra"  value="0" class="form-control">
                        </div>


                        <div class="form-group text-right">
                            <button id='simular' type="button" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Simular</button>
                        </div>

                        <hr>

                        <div id='simulacoes'>


                        </div>

                </div>
            </div>
        </div>
    </div>

    <template>
        <div class="row">
            <div class="col-12">
            <h5>Compras com <strong class='num_parcelas'></strong> parcelas </h5>
            </div>
            <div class="col-3">
                <div class="form-group">
                    <label class="control-label">Valor de cada parcela </label>
                    <input type="text"  value="0" class="form-control valor_parcela">
                </div>
            </div>
            <div class="col-3">
                <div class="form-group">
                    <label class="control-label">Valor total ao consumidor </label>
                    <input type="text"  value="0" class="form-control valor_total">
                </div>
            </div>
            <div class="col-3">
                <div class="form-group">
                    <label class="control-label">Taxa </label>
                    <input type="text"  value="0" class="form-control taxa">
                </div>
            </div>
            <div class="col-3">
                <div class="form-group">
                    <label class="control-label">Total recebido (incluindo Taxas) </label>
                    <input type="text"  value="0" class="form-control total_taxa">
                </div>
            </div>
        </div>
        <hr>
    </template>

@endsection

@push('scripts')
    <script>
        $( document ).ready(function() {

            $("#max_parcela").TouchSpin({
                min: 1,
                max: 30,
                step: 1,
                initval: 0
            }).on('change',function(){
                $("#free_parcela").trigger("touchspin.updatesettings", {max: this.value});
            })

            $("#free_parcela").TouchSpin({
                min: 1,
                max: {{$instituicao->max_parcela}},
                step: 1,
                initval: 0
            })



            $("#valor_parcela").TouchSpin({
                min: 0,
                max: 99,
                step: 0.1,
                decimals: 2,
                boostat: 5,
                maxboostedstep: 1,
                initval: 0,
                prefix: '%'
            })

            $("#compra").maskMoney({prefix:'R$ ', thousands:'.',decimal:',', affixesStay: false});

            function initMaskMoney(selector) {
                $(selector).maskMoney({prefix:'R$ ', thousands:'.', decimal:',', affixesStay: true}).trigger('mask.maskMoney');
            }

            $('#simular').on('click',function(){
            $('#simulacoes').html('');
            let parcelas = $("#max_parcela").val();
            let parcelas_free = $("#free_parcela").val();
            let valor_compra = $("#compra").val().replace('.','').replace(",", ".");
            let juros = $("#valor_parcela").val();

            var template = document.getElementsByTagName("template")[0];


            for (let index = 0; index < parcelas; index++) {

                var blocoHtml = template.content.cloneNode(true);
                blocoHtml.querySelectorAll(".num_parcelas")[0].textContent = index+1;
                blocoHtml.querySelectorAll(".valor_parcela")[0].textContent = index+1;
                var valor = (valor_compra * ( 1 + ((juros  * index) /100))).toFixed(2)

                if(index+1 <= parcelas_free){
                    valor = valor_compra
                }

                let taxa_pagarme=0;
                if(index+1 >= 7){
                    taxa_pagarme = 4.59;
                }else if(index+1 >= 2){
                    taxa_pagarme = 4.19;
                }else if(index+1 == 1){
                    taxa_pagarme = 3.79;
                }

                var taxa = (valor * (({{$instituicao->taxa_tectotum}} + taxa_pagarme) /100))  + 1.20

                blocoHtml.querySelectorAll(".valor_total")[0].value = valor;
                blocoHtml.querySelectorAll(".valor_parcela")[0].value = (valor / (index + 1)).toFixed(2);
                blocoHtml.querySelectorAll(".taxa")[0].value = taxa.toFixed(2)
                blocoHtml.querySelectorAll(".total_taxa")[0].value = (valor-taxa).toFixed(2)


                $('#simulacoes').append(blocoHtml)
                initMaskMoney('.valor_parcela')
                initMaskMoney('.valor_total')
                initMaskMoney('.taxa')
                initMaskMoney('.total_taxa')

                // for x in params[free_installments]..params[max_installments]
                // | installment = x
                // | amount = params[amount] * (1 + interest_rate *   / 100))
                // | installment_amount = amount / x

            }
        });

        });



    </script>
@endpush
