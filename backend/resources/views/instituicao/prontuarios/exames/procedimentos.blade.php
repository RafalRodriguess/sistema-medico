
    <style>
        .button-procedimento:not(:hover) {color: #7460ee!important}
        .button-procedimento:not(:disabled):not(.disabled).active {color: white!important}
        .scrolling-pagination-teste::-webkit-scrollbar-track
        {
            -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
            border-radius: 10px;
            background-color: #F5F5F5;
        }

        .scrolling-pagination-teste::-webkit-scrollbar
        {
            width: 7px;
            background-color: #F5F5F5;
        }

        .scrolling-pagination-teste::-webkit-scrollbar-thumb
        {
            border-radius: 10px;
            -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,.3);
            background-color: #c1c1c1;
        }
    </style>
    <div class="modal-header">
        <h4 class="modal-title" id="myLargeModalLabel">Procedimentos</h4>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    </div>
  
    <div class="row modal-body" id="">

        <div class="col-sm-12">
            <div class="row " id="selectVariosProc">
                <div class="col-sm form-group">
                    <input type="text" class="form-control" name="busca" placeholder="Procurar procedimento" autocomplete="off">
                </div>
                <div class="col-md-12 pesquisa-procedimento-exame">
                    @include('instituicao.prontuarios.exames.lista_procedimento')
                </div>
                
            </div>
        </div>
    </div>

    <div class="modal-footer" style="width: 100%">
        <button type="button" class="btn btn-default waves-effect close" data-dismiss="modal">Fechar</button>
      
        <button type="button" class="btn btn-secondary waves-effect waves-light selectProc" style="background-color: #009688;border:#009688;color: #fff;">
            <span class="btn-label"><i class="fa fa-check"></i></span>Escolher proceimentos selecionados
        </button>
        {{-- <button type="submit" class="btn btn-danger waves-effect waves-light">Salvar</button> --}}
    </div>

    
    <script>
        
        $(".pesquisa-procedimento-exame").on('click', '.button-procedimento', function(e){
            e.stopImmediatePropagation()
            e.stopPropagation()
            e.preventDefault()

            var found = false;
            procedimentos_exames.map((element) =>  {if(parseInt(element[1]) == parseInt($(this).attr('data-id'))) {
                found = true;
            }})

            if(found == false){
                procedimentos_exames.push([$(this).attr('data-descricao'), $(this).attr('data-id')])
            }
            // console.log(procedimentos_exames)
            $(this).addClass('active');
        })


        $(".selectProc").on("click", function(){
            for (let index = 0; index < procedimentos_exames.length; index++) {
                const element = procedimentos_exames[index];
                
                $('.summernoteExame').summernote('editor.pasteHTML', '<p>'+element[0]+'</p>');
            }

            $('#modalPacotesProcedimentos').modal('hide');
            $('#modalInserirAgenda').modal('show');
        })

        $("[name^='busca']").keyup(function(){
            busca = $(this).val().toLowerCase();
            $.ajax("{{ route('agendamento.exame.getSelectProcedimentosDescricao') }}", {
                    method: "get",
                    data: {
                        descricao: busca,
                        procedimentos_exames: procedimentos_exames,
                    },
                    beforeSend: () => {
                        // $('.loading').css('display', 'block');
                        // $('.loading').find('.class-loading').addClass('loader')
                        $(".pesquisa-procedimento-exame").html('')
                    },
                    success: function (response) {
                        $(".pesquisa-procedimento-exame").html(response)
                    },
                    complete: () => {
                        // $('.loading').css('display', 'none');
                        // $('.loading').find('.class-loading').removeClass('loader')
                    },
            })

            $("#selectVariosProc").find(".btn-proc").each((index, element) => {
                // console.log($(element).text().trim(), busca, $(element).text().trim().includes(busca));

                if($(element).text().trim().toLowerCase().includes(busca)){
                    $(element).parents('.proc_btn_hidden').css('display', 'block');
                }else{
                    $(element).parents('.proc_btn_hidden').css('display', 'none');
                }

            })


        })

    </script>