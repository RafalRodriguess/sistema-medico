@extends('admin.layout')

@section('conteudo')
    @component('components/page-title', [
        'titulo' => "Editar hablidade perfil #{$perfil_usuario->id} {$perfil_usuario->nome}",
        'breadcrumb' => [
            'Administradores' => route('usuarios.index'),
            'Habilidades',
        ],
    ])
    @endcomponent

    <div class="row">
      <div class="col-12">
          <div class="card">
              <div class="card-body">
                  <div class="row pesquisar">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Habilidade</label>
                          <input type="text" name="pesquisa" class="form-control" placeholder="Pesquise por habilidades" autocomplete="off">
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="form-group">
                          <label>Categoria</label>
                          <select class="form-control" name="categoria_pesquisa" id="categoria_pesquisa">
                              <option value="0">Todos</option>
                              <option value="1">Administração</option>
                              <option value="2">Cadastros</option>
                          </select>
                        </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>

  <div class="card">
      <div class="card-body">
          <form action="{{ route('perfis-usuarios-instituicoes.habilidade', [$perfil_usuario]) }}" method="post">
              @csrf

              <div class="form-group text-right">
                <button type="button" id="" class='btn btn-primary permitir_tudo'>Permitir Tudo</button>
                <button type="button" id="" class='btn btn-primary negar_tudo'>Negar Tudo</button>
                <a href="{{ route('perfis-usuarios-instituicoes.index') }}">
                        <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                </a>
                <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
            </div>

            <hr>

              @foreach ($habilidades as $hab_grupo)
              <div class="row habilidade-grupo grupo-{{$hab_grupo->id}}">

                  <div class="col-12">
                      <div class="card">
                          <div class="card-body">
                              <div class="row">
                                  <div class="col-md-10">
                                    <div class="form-group">
                                      <h5 style="font-size: 17px!important;">{{ $hab_grupo->nome }}</h5>
                                      <input type="hidden" name="categoria" id="categoria" value="{{ $hab_grupo->categoria }}">
                                    </div>
                                  </div>
                                  <div class="col-md-2">
                                    <button type="submit" class="btn waves-effect waves-light btn-block btn-info">
                                      Salvar Alteração
                                    </button>
                                  </div>

                              </div>
                              <hr>
                              <div class="ibox-content">

                                  @foreach ($hab_grupo->instituicaoHabilidades as $hab)
                                    @php($habilidade = in_array($hab->id, array_column($selecionadas, 'habilidade_id')))
                                      <div class="row habilidade" style="margin-bottom: 30px!important">
                                          <div class="col-md-6">
                                              <h3 style="font-size: 17px!important">{{ $hab->nome }}</h3>
                                              <p style="font-size: 15px!important;">{{ $hab->descricao }}</p>
                                          </div>
                                          <div class="demo-radio-button col-md-6">
                                              <div class="row">
                                                  <div class="col-md-6">
                                                      <input class="permitir" name="habilidades[{{ $hab->id }}]" type="radio" id="radio_{{ $hab->nome_unico }}_1" value="1" @if ($habilidade) checked @endif/>
                                                      <label for="radio_{{ $hab->nome_unico }}_1">Permitir</label>
                                                  </div>
                                                  <div class="col-md-6">
                                                      <input class="negar" name="habilidades[{{ $hab->id }}]" type="radio" id="radio_{{ $hab->nome_unico }}_2" value="0" @if (!$habilidade) checked @endif/>
                                                      <label for="radio_{{ $hab->nome_unico }}_2">Negar</label>
                                                  </div>
                                              </div>
                                          </div>
                                          @if (!empty($hab->sensivel))
                                            <div class="col-md-6">
                                              <h6 style="color: red">Esta habilidade é considerada "sensível", pois permite ao usuário alterar ou excluir registro fora do processo normal de trabalho</h6>
                                            </div>
                                          @endif

                                      </div>
                                  @endforeach
                                  <div class="row habilidade" style="margin-bottom: 30px!important">
                                      <div class="col-md-6">
                                      </div>
                                      <div class="demo-radio-button col-md-6">
                                          <div class="row">
                                              <div class="col-md-6">
                                                  <input class="permitir" name="grupo_habilidades[{{ $hab_grupo->id }}]" type="radio" id="radio_{{ $hab_grupo->id }}_1" value="1" />
                                                  <label for="radio_{{ $hab_grupo->id }}_1">Permitir Todos</label>
                                              </div>
                                              <div class="col-md-6">
                                                  <input class="negar" name="grupo_habilidades[{{ $hab_grupo->id }}]" type="radio" id="radio_{{ $hab_grupo->id }}_2" value="0" />
                                                  <label for="radio_{{ $hab_grupo->id }}_2">Negar Todos</label>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
              @endforeach


              <div class="form-group text-right">
                      <button type="button" id="" class='btn btn-primary permitir_tudo'>Permitir Tudo</button>
                      <button type="button" id="" class='btn btn-primary negar_tudo'>Negar Tudo</button>
                      <a href="{{ route('perfis-usuarios-instituicoes.index') }}">
                              <button type="button" class="btn btn-secondary waves-effect waves-light m-r-10"><i class="mdi mdi-arrow-left-bold"></i> Voltar</button>
                      </a>
                      <button type="submit" class="btn btn-success waves-effect waves-light m-r-10"><i class="mdi mdi-check"></i> Salvar</button>
              </div>
          </form>
      </div>
  </div>
@endsection

@push('scripts')
<script>
  $('[name="pesquisa"]').on('keyup', function(){
      const pesquisa_habilidade = $(this);

      $('[name="categoria_pesquisa"]').each((index,element) => {

          var $this = $(element).val();

          $('.habilidade-grupo [name="categoria"]').each((index, element) => {
          var habilidade_grupo = $(element).val();

          if($this != 0){

              if ($(element).val() == $this) {

                 $(element).closest('.habilidade-grupo').removeClass('hide');


                      if(pesquisa_habilidade.val() != ""){
                          $('.ibox-content h3').each((index, element) => {
                              var verifica_habilidade = $(element).closest('.card-body').find('[name="categoria"]').val()

                              if(habilidade_grupo == verifica_habilidade){
                                  if (habilidade_grupo == $this) {
                                    if (($(element).text()).indexOf((pesquisa_habilidade.val())) != -1) {
                                      $(element).closest('.habilidade').removeClass('hide');
                                    }else{
                                      $(element).closest('.habilidade').addClass('hide');

                                    }
                                  }
                              }
                          });



                          $('.habilidade-grupo').each(function (index, element) {

                              var verifica_grupo = $(element).find('[name="categoria"]').val();

                              if (habilidade_grupo == verifica_grupo) {
                                const numFilhos = $(element).find('.habilidade').length;
                                const numFilhosHidden = $(element).find('.habilidade.hide').length;

                                if(numFilhos === numFilhosHidden){
                                  $(element).addClass('hide');
                                }else{
                                  $(element).removeClass('hide');
                                }
                              }
                          })


                      }

              }else{
                 $(element).closest('.habilidade-grupo').addClass('hide');
              }
          }else{
              $(element).closest('.habilidade-grupo').removeClass('hide');


              $('.ibox-content h3').each((index, element) => {
                if (($(element).text()).indexOf((pesquisa_habilidade.val())) != -1) {
                  $(element).closest('.habilidade').removeClass('hide');
                }else{
                  $(element).closest('.habilidade').addClass('hide');
                }
              });

              $('.habilidade-grupo').each(function (index, element) {
                const numFilhos = $(element).find('.habilidade').length;
                const numFilhosHidden = $(element).find('.habilidade.hide').length;

                if(numFilhos === numFilhosHidden){
                  $(element).addClass('hide');
                }else{
                  $(element).removeClass('hide');
                }
              })


          }
      });

      })

    });


  $('.permitir_tudo').on('click', function(){
      $('.permitir').prop("checked", "checked")
  })

  $('.negar_tudo').on('click', function(){
      $('.negar').prop("checked", "checked")
  })

  $('input[name*="grupo_habilidades"]').on('change',function(ev){
      split = this.id.split('_')
      if(split[2]=='1'){
          $('.grupo-'+split[1]).find('.permitir').prop("checked", "checked")
      }else if(split[2]=='2'){
          $('.grupo-'+split[1]).find('.negar').prop("checked", "checked")
      }
  })



  $('#categoria_pesquisa').on('change', function(){
      const $this = $(this);

      $('.habilidade-grupo [name="categoria"]').each((index, element) => {
          var habilidade_grupo = $(element).val();

          if($this.val() != 0){

              if ($(element).val() == $this.val()) {

                 $(element).closest('.habilidade-grupo').removeClass('hide');
                 $('[name="pesquisa"]').each((index,element) => {

                      var pesquisa_habilidade = $(element).val();

                      if(pesquisa_habilidade != ""){
                          $('.ibox-content h3').each((index, element) => {
                              var verifica_habilidade = $(element).closest('.card-body').find('[name="categoria"]').val()

                              if(habilidade_grupo == verifica_habilidade){
                                  if (habilidade_grupo == $this.val()) {
                                    if (($(element).text()).indexOf((pesquisa_habilidade)) != -1) {
                                      $(element).closest('.habilidade').removeClass('hide');
                                    }else{
                                      $(element).closest('.habilidade').addClass('hide');

                                    }
                                  }
                              }
                          });



                          $('.habilidade-grupo').each(function (index, element) {

                              var verifica_grupo = $(element).find('[name="categoria"]').val();

                              if (habilidade_grupo == verifica_grupo) {
                                const numFilhos = $(element).find('.habilidade').length;
                                const numFilhosHidden = $(element).find('.habilidade.hide').length;

                                if(numFilhos === numFilhosHidden){
                                  $(element).addClass('hide');
                                }else{
                                  $(element).removeClass('hide');
                                }
                              }
                          })


                      }
                  })
              }else{
                 $(element).closest('.habilidade-grupo').addClass('hide');
              }
          }else{
              $(element).closest('.habilidade-grupo').removeClass('hide');
              $('[name="pesquisa"]').each((index,element) => {

                      var pesquisa_habilidade = $(element).val();

                      if(pesquisa_habilidade != ""){
                          $('.ibox-content h3').each((index, element) => {
                            if (($(element).text()).indexOf((pesquisa_habilidade)) != -1) {
                              $(element).closest('.habilidade').removeClass('hide');
                            }else{
                              $(element).closest('.habilidade').addClass('hide');
                            }
                          });

                          $('.habilidade-grupo').each(function (index, element) {

                              var verifica_grupo = $(element).find('[name="categoria"]').val();

                              if (habilidade_grupo == verifica_grupo) {
                                const numFilhos = $(element).find('.habilidade').length;
                                const numFilhosHidden = $(element).find('.habilidade.hide').length;

                                if(numFilhos === numFilhosHidden){
                                  $(element).addClass('hide');
                                }else{
                                  $(element).removeClass('hide');
                                }
                              }
                          })
                      }

                  })
          }
      });

    });
</script>
@endpush
