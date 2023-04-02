<style>
    .dropbtn {
        background-color: #ffffff;
        color: black;
        padding: 16px;
        font-size: 16px;
        border: none;
        cursor: pointer;
    }
    
    .dropdown-new {
      position: relative;
      display: inline-block;
    }
    
    .dropdown-new-content {
      display: none;
      position: absolute;
      background-color: #f9f9f9;
      min-width: 160px;
      box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
      z-index: 1;
    }
    
    .dropdown-new-content a {
      color: black;
      padding: 12px 16px;
      text-decoration: none;
      display: block;
    }
    
    .dropdown-new-content a:hover {background-color: #f1f1f1}
    
    .dropdown-new:hover .dropdown-new-content {
      display: block;
    }
    
    .dropdown-new:hover .dropbtn {
      background-color: #ffffff;
    }
    </style>

<div class="row">
    <div class="col-md-8">
        <h5>Atendimentos</h5>
    </div>
    <div class="col-md-4">
        <button type="button" class="btn btn-primary waves-effect waves-light m-r-10 upload-arquivo" aria-haspopup="true" aria-expanded="false"
        data-toggle="tooltip" data-placement="top" data-original-title="Upload de arquivos" style="float: right"><i
            class="fas fa-upload"></i>
        </button>
    </div>
</div>
<hr>
<div class="row">
    <div class="col-md-12">
        <h5>Pastas</h5>
    </div>
    @foreach ($pastas as $item)
        <div class="col-md-12 pasta_{{$item->id}}">
            <div class="row">
                <div class="col-md-8">
                    <i class="far fa-folder"></i> {{$item->nome}}
                </div>
                <div class="col-md-4">
                    <div class="dropdown-new">
                        <button class="dropbtn" style="width: 100%; text-align: center;"><i class="fas fa-ellipsis-v"></i></button>
                        
                        <div class="dropdown-new-content">
                            <a class="arquivos-pasta" href="javascript:void(0)" data-id="{{$item->id}}"><i class="fas fa-folder"></i> Arquivos</a>
                            <a class="upload-pasta" href="javascript:void(0)" data-id="{{$item->slug}}"><i class="fas fa-upload"></i> Upload</a>
                            @if (count($item->arquivo) == 0)                            
                                <li><a class="excluir-pasta" href="javascript:void(0)" data-id="{{$item->id}}"><i class="fas fa-trash"></i> Excluir</a> </li>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<script>
    $(".upload-pasta").on('click', function(){
        var id = $(this).attr('data-id');
        addArquivo(id)
    })

    $(".upload-arquivo").on('click', function(){
        addArquivo(null)
    })
    
    $(".arquivos-pasta").on('click', function(e){
        e.stopPropagation()
        e.preventDefault()
        
        var pasta_id = $(this).attr('data-id');
        var paciente_id = $("#paciente_id").val();

        $.ajax({
            url: "{{route('agendamento.arquivo.getArquivosPasta', ['paciente' => 'paciente_id', 'pasta' => 'pasta_id'])}}".replace('paciente_id', paciente_id).replace('pasta_id', pasta_id),
            type: "POST",
            data: {'_token': '{{csrf_token()}}'},
            beforeSend: () => {
                $('.loading').css('display', 'block');
                $('.loading').find('.class-loading').addClass('loader')
            },
            success: (result) => {
                $(".lista-arquivos").html('')
                $(".lista-arquivos").html(result)
            },
            complete: () => {
                $('.loading').css('display', 'none');
                $('.loading').find('.class-loading').removeClass('loader') 
            }
        })
    })
    
    $(".excluir-pasta").on('click', function(e){
        e.stopPropagation()
        e.preventDefault()
        
        var pasta_id = $(this).attr('data-id');
        var paciente_id = $("#paciente_id").val();

        Swal.fire({
            title: "Excluir!",
            text: 'Deseja excluir a pasta ?',
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            cancelButtonText: "Não, cancelar!",
            confirmButtonText: "Sim, confirmar!",
        }).then(function(result) {
            if(result.value){
                $.ajax({
                    url: "{{route('agendamento.arquivo.excluirPasta', ['paciente' => 'paciente_id', 'pasta' => 'pasta_id'])}}".replace('paciente_id', paciente_id).replace('pasta_id', pasta_id),
                    type: "POST",
                    data: {'_token': '{{csrf_token()}}'},
                    beforeSend: () => {
                        $('.loading').css('display', 'block');
                        $('.loading').find('.class-loading').addClass('loader')
                    },
                    success: (result) => {
                        $.toast({
                            heading: 'Sucesso',
                            text: 'Arquivo excluido com sucesso!',
                            position: 'top-right',
                            loaderBg: '#ff6849',
                            icon: 'success',
                            hideAfter: 9000,
                            stack: 10
                        });
                        $(".pasta_"+pasta_id).remove()
                    },
                    complete: () => {
                        $('.loading').css('display', 'none');
                        $('.loading').find('.class-loading').removeClass('loader') 
                    }
                })
            }
        })
    })


</script>