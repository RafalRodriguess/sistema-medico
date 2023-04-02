<div class="row page-titles">
    <div class="col-md-12 align-self-center">
        <h3 class="text-themecolor">{{ $titulo }}</h3>
        <ol class="breadcrumb">
            @foreach($breadcrumb as $title => $link)
                @if(is_string($title))
                    <li class="breadcrumb-item">
                        <a href="{{ $link }}">{{ $title }}</a>
                    </li>
                @else
                    <li class="breadcrumb-item active">{{ $link }}</li>
                @endif
            @endforeach
        </ol>
    </div>
    <div class="col-md-7 col-4 align-self-center">
        {{ $dados ?? '' }}
    </div>
</div>