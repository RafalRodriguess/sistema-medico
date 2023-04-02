<div id="modalEditarAgenda" class="modal fade bs-example-modal-lg" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form id="editar_agendamento" action="{{route('instituicao.agendamentoCentroCirurgico.updateAgenda', [$agendamento])}}" method="post">
                @csrf
                @method('post')
                <div class="modal-header">
                    <h4 class="modal-title">Editar agendamento</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="agendamento_editar_id" id="agendamento_editar_id" value="{{$agendamento->id}}">
                    <input type="hidden" name="saida_estoque_id" id="saida_estoque_id" value="{{$agendamento->saida_estoque_id}}">
                    <ul class="nav nav-tabs customtab" role="tablist">
                        <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#dadosGerais" role="tab"><span class="hidden-sm-up"><i class="ti-home"></i></span> <span class="hidden-xs-down">Dados</span></a> </li>
                        <li class="nav-item"> <a class="nav-link tab-dados-complementares" data-toggle="tab" href="#dados_complementares" role="tab"><span class="hidden-sm-up"><i class="ti-user"></i></span> <span class="hidden-xs-down">Dados complementares</span></a> </li>
                        <li class="nav-item"> <a class="nav-link tab-equipamentos-caixas-cirurgicos" data-toggle="tab" href="#equipamentos_caixas_cirurgicos" role="tab"><span class="hidden-sm-up"><i class="ti-user"></i></span> <span class="hidden-xs-down">Equipamentos e caixas cirúrgicos</span></a> </li>
                        <li class="nav-item tab-outras-cirurgias"> <a class="nav-link" data-toggle="tab" href="#outros_cirurgias" role="tab"><span class="hidden-sm-up"><i class="ti-email"></i></span> <span class="hidden-xs-down">Outras cirúrgias</span></a> </li>
                        <li class="nav-item tab-sangues-derivados"> <a class="nav-link" data-toggle="tab" href="#sangues_derivados" role="tab"><span class="hidden-sm-up"><i class="ti-email"></i></span> <span class="hidden-xs-down">Sangue e derivados</span></a> </li>
                        <li class="nav-item tab-produtos"> <a class="nav-link" data-toggle="tab" href="#produtos" role="tab"><span class="hidden-sm-up"><i class="ti-email"></i></span> <span class="hidden-xs-down">Produtos</span></a> </li>
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div class="tab-pane active" id="dadosGerais" role="tabpanel">
                            @include('instituicao.agendamentos_centro_cirurgicos.dados_gerais_editar')
                        </div>
                        <div class="tab-pane p-10" id="dados_complementares" role="tabpanel"></div>
                        <div class="tab-pane p-10" id="equipamentos_caixas_cirurgicos" role="tabpanel"></div>
                        <div class="tab-pane p-10" id="outros_cirurgias" role="tabpanel"></div>
                        <div class="tab-pane p-10" id="sangues_derivados" role="tabpanel"></div>
                        <div class="tab-pane p-10" id="produtos" role="tabpanel"></div>
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <a href="{{ route('instituicao.agendamentoCentroCirurgico.fichaCirurgica', [$agendamento]) }}" target="_blank">
                        <button type="button" class="btn btn-default waves-effect imprimir_ficha_cirurgica" style="color: #7b878d;">Imprimir ficha cirurgica</button>
                    </a>
                    <a href="{{ route('instituicao.agendamentoCentroCirurgico.folhaSala', [$agendamento]) }}" target="_blank">
                        <button type="button" class="btn btn-default waves-effect imprimir_folha_sala" style="color: #7b878d;">Imprimir folha da sala</button>
                    </a>
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-success waves-effect" >Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $("#editar_agendamento").on('submit', function(e){
        e.preventDefault();
        var id = $("#agendamento_editar_id").val()
        var formData = new FormData($(this)[0]);

        $.ajax({
          url: "{{route('instituicao.agendamentoCentroCirurgico.updateAgenda', ['agendamento' => 'agendamentoId'])}}".replace('agendamentoId', id),
          method: "POST",
          data: formData,
          processData: false,
          contentType: false,
          success: function (result) {
            // $("#modalEditarAgenda").modal('hide');
            $.toast({
                heading: 'Sucesso',
                text: 'Agendamento editado com sucesso',
                position: 'top-right',
                loaderBg:'#ff6849',
                icon: 'success',
                hideAfter: 3000,
                stack: 10
            });
          },
          error: function (response) {
            if(response.responseJSON.errors){
                Object.keys(response.responseJSON.errors).forEach(function(key) {
                    $.toast({
                        heading: 'Erro',
                        text: response.responseJSON.errors[key][0],
                        position: 'top-right',
                        loaderBg: '#ff6849',
                        icon: 'error',
                        hideAfter: 9000,
                        stack: 10
                    });

                });
            }
          }
      })
    })

    $('.tab-equipamentos-caixas-cirurgicos').on('click', function(){
        
        if($('#equipamentos_caixas_cirurgicos').hasClass('carregado')){
            return
        }else{
            var id = $("#agendamento_editar_id").val()

            $('#equipamentos_caixas_cirurgicos').addClass('carregado')

            $.ajax({
                url: "{{route('instituicao.agendamentoCentroCirurgico.equipamentosCaixasCirurgicos', ['agendamento' => 'agendamentoId'])}}".replace('agendamentoId', id),
                type: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                beforeSend: () => {
                    $('#equipamentos_caixas_cirurgicos').addClass('loader')
                    $('.loading').css('display', 'block');
                },

                success: function(result) {
                    
                    $('#equipamentos_caixas_cirurgicos').html(result)
                    $('.item_ecc').setMask();
                    $('.select2ecc').select2()
                    $(".item_ecc").removeClass('item_ecc')

                    $('.loading').css('display', 'none');
                    
                },

                complete: () => {
                    $('#equipamentos_caixas_cirurgicos').removeClass('loader') 
                }

            });
        }
    })
    
    $('.tab-outras-cirurgias').on('click', function(){
        
        if($('#outros_cirurgias').hasClass('carregado')){
            return
        }else{
            var id = $("#agendamento_editar_id").val()

            $('#outros_cirurgias').addClass('carregado')

            $.ajax({
                url: "{{route('instituicao.agendamentoCentroCirurgico.outrasCirurgias', ['agendamento' => 'agendamentoId'])}}".replace('agendamentoId', id),
                type: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                beforeSend: () => {
                    $('#outros_cirurgias').addClass('loader')
                    $('.loading').css('display', 'block');
                },

                success: function(result) {
                    
                    $('#outros_cirurgias').html(result)
                    $('.item_oc').setMask();
                    $('.select2oc').select2()
                    $(".item_oc").removeClass('item_oc')

                    $('.loading').css('display', 'none');
                    
                },

                complete: () => {
                    $('#outros_cirurgias').removeClass('loader') 
                }

            });
        }
    })
    
    $('.tab-sangues-derivados').on('click', function(){
        
        if($('#sangues_derivados').hasClass('carregado')){
            return
        }else{
            var id = $("#agendamento_editar_id").val()

            $('#sangues_derivados').addClass('carregado')

            $.ajax({
                url: "{{route('instituicao.agendamentoCentroCirurgico.sanguesDerivados', ['agendamento' => 'agendamentoId'])}}".replace('agendamentoId', id),
                type: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                beforeSend: () => {
                    $('#sangues_derivados').addClass('loader')
                    $('.loading').css('display', 'block');
                },

                success: function(result) {
                    
                    $('#sangues_derivados').html(result)
                    $('.item_sd').setMask();
                    $('.select2sd').select2()
                    $(".item_sd").removeClass('item_sd')

                    $('.loading').css('display', 'none');
                    
                },

                complete: () => {
                    $('#sangues_derivados').removeClass('loader') 
                }

            });
        }
    })
    
    
    $('.tab-produtos').on('click', function(){
        
        if($('#produtos').hasClass('carregado')){
            return
        }else{
            var id = $("#agendamento_editar_id").val()

            $('#produtos').addClass('carregado')

            $.ajax({
                url: "{{route('instituicao.agendamentoCentroCirurgico.produtos', ['agendamento' => 'agendamentoId'])}}".replace('agendamentoId', id),
                type: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                beforeSend: () => {
                    $('#produtos').addClass('loader')
                    $('.loading').css('display', 'block');
                },

                success: function(result) {
                    
                    $('#produtos').html(result)
                    $('.item_prod').setMask();
                    $('.select2prod').select2()
                    $(".item_prod").removeClass('item_prod')

                    $('.loading').css('display', 'none');
                    
                },

                complete: () => {
                    $('#produtos').removeClass('loader') 
                }

            });
        }
    })
    
    $('.tab-dados-complementares').on('click', function(){
        
        if($('#dados_complementares').hasClass('carregado')){
            return
        }else{
            var id = $("#agendamento_editar_id").val()

            $('#dados_complementares').addClass('carregado')

            $.ajax({
                url: "{{route('instituicao.agendamentoCentroCirurgico.dadosComplementares', ['agendamento' => 'agendamentoId'])}}".replace('agendamentoId', id),
                type: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                beforeSend: () => {
                    $('#dados_complementares').addClass('loader')
                    $('.loading').css('display', 'block');
                },

                success: function(result) {
                    
                    $('#dados_complementares').html(result)

                    $('.loading').css('display', 'none');
                    
                },

                complete: () => {
                    $('#dados_complementares').removeClass('loader') 
                }

            });
        }
    })
</script>
