@php
    $image_url = null;
    try {
        $image_url = \Storage::cloud()->url($instituicao_usuario->imagem);
    } catch (\Exception $e) {
    }
@endphp
@if (!empty($image_url))
    <figure class="user-image">
        <img src="{{ $image_url }}" alt="user image">
    </figure>
@else
    @php
        $binhash = md5($usuario->id, true);
        $numhash = unpack('N2', $binhash);
        $hash = substr($numhash[1] . $numhash[2], 0, 3);
        if ($hash > 360) {
            $size = (int) ($hash / 360);
            $hash -= 360 * $size;
        }
    @endphp
    <figure class="user-image shadow-sm" style="background-color: hsla({{ $hash }}, 90%, 45%, 1);">
        <div class="round-user-icon">{{ strtoupper($usuario->nome[0]) }}</div>
    </figure>
@endif
