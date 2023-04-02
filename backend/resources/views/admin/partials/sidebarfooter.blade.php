

<!-- item--><a href="" class="link" data-toggle="tooltip" title="Settings"><i class="ti-settings"></i></a>
<!-- item--><a href="" class="link" data-toggle="tooltip" title="Email"><i class="mdi mdi-gmail"></i></a>
{{-- <!-- item--><a href="" class="link" data-toggle="tooltip" title="Logout"><i class="mdi mdi-power"></i></a> --}}
<a href="{{ route('admin.logout') }}" class="link" title="Logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
    <i class="mdi mdi-power"></i>
</a>
<form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
    @csrf
</form>