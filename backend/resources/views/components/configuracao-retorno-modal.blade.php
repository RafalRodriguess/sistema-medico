@php
    $valores = collect($valores ?? []);
    $old = collect(old('configuracoes_retorno', []))->where('grupo','=', $grupo_id);
@endphp
<div class="modal fade" id="{{ $modal_id }}" tabindex="-1" role="dialog" aria-labelledby="{{ $modal_id }}_title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="{{ $modal_id }}_title">{{ $title }}</h5>
        </div>
        <div class="modal-body row">
            @foreach ($options as $id => $option)
                <div class="pr-4 col-md-6 form-group d-flex flex-column justify-content-end">
                    <div class="d-flex flex-wrap-revert align-items-center">
                        <input type="hidden" default_name="configuracoes_retorno[{{ ($id + 1) . $grupo_id}}][campo]" value="{{ $id }}">
                        <input type="hidden" default_name="configuracoes_retorno[{{ ($id + 1) . $grupo_id}}][grupo]" value="{{ $grupo_id }}">
                        <input type="checkbox" class="form-control checkbox switch-checkbox" @if($valores->where('campo', '=', $id)->count() > 0 || $old->where('campo', '=', $id)->count() > 0) checked="checked" @endif>
                        <label class="form-control-label ml-2 mb-0">{{ $option }}</label>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
        </div>
      </div>
    </div>
  </div>
