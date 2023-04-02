<div class="modal inmodal" id="modalPrestadores" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                Visualizar Prestadores
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <small aria-hidden="true"><i class="fa fa-times"></i></small>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <form action="" id="salvarModalPrestadores" method="POST">
                        @csrf
                        <input type="hidden" name="usuario_id" id="usuario_id" value="{{$usuario->id}}">
                        <div class="form-group @if($errors->has('visualizar_prestador')) has-danger @endif">
                            <label class="form-control-label">Prestadores *</label>
                            <select name="visualizar_prestador[]" id="visualizar_prestador" class="form-control select2modal @if($errors->has('visualizar_prestador')) form-control-danger @endif" multiple style="width: 100%">
                                <option value="" @if (in_array('', $prestadoresIds))
                                    selected
                                @endif >Todos</option>
                                @foreach ($prestadores as $prestador)
                                    <optgroup label="{{ $prestador->descricao }}">
                                        @foreach ($prestador->prestadoresInstituicao as $prestadoresInstituicao)
                                            {{-- @if ($prestadoresInstituicao->ativo == 1) --}}
                                                <option {{(in_array($prestadoresInstituicao->id, $prestadoresIds))? 'selected': '' }}  value="{{ $prestadoresInstituicao->id }}">{{ $prestadoresInstituicao->prestador->nome }}</option>
                                            {{-- @endif --}}
                                        @endforeach
                                    </optgroup>

                                @endforeach
                            </select>
                            @if($errors->has('visualizar_prestador'))
                                <div class="form-control-feedback">{{ $errors->first('visualizar_prestador') }}</div>
                            @endif
                        </div>
                        <div class="form-groupn text-right" style="margin-top: 10px">
                            <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10" class="close" data-dismiss="modal" aria-label="Close"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                            <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
                        </div>
                    </form>
                </div>
            </div>


        </div>
    </div>
</div>

<script>
    $(".select2modal").select2().on('select2:select', function(e) {
        // console.log('select');
        // console.log(e.params.data.id); //This will give you the id of the selected attribute
        // console.log(e.params.data.text); //This will give you the text of the selected
        if(e.params.data.id == ""){
            $("#visualizar_prestador").find("option").attr("selected", false);
            $("#visualizar_prestador").val('').trigger('change');
        }else{
            $("#visualizar_prestador option[value='']").attr("selected", false);
            $("#visualizar_prestador").trigger('change');
        }
    })

    $("#salvarModalPrestadores").on('submit', function(e){
        e.stopPropagation();
        e.preventDefault();

        var form = new FormData($('#salvarModalPrestadores')[0])

        var id = $("#usuario_id").val();

        $.ajax("{{ route('instituicao.instituicoes_usuarios.salvarPrestadores', ['usuario' => 'usuarioId']) }}".replace('usuarioId', id), {
            method: "POST",
            data: form,
            contentType: false,
            processData: false,
            beforeSend: () => {
                $('.loading').css('display', 'block');
                $('.loading').find('.class-loading').addClass('loader')
            },
            success: function (response) {

                $.toast({
                    heading: "Sucesso",
                    text: "Visualizar prestadores editado com sucesso",
                    position: 'top-right',
                    loaderBg: '#ff6849',
                    icon: 'success',
                    hideAfter: 3000,
                    stack: 10
                });

                $("#modalPrestadores").modal('hide')

            },
            complete: () => {
                $('.loading').css('display', 'none');
                $('.loading').find('.class-loading').removeClass('loader')
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
</script>

