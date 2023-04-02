@extends('layouts.material')

@section('navbar')
    @include('comercial.partials.navbar')
@endsection

@includeIf(session('comercial'),  'sidebar view')
@section('sidebar-nav')
    @include('comercial.partials.sidebar')
@endsection

@section('sidebar-footer')
    @include('comercial.partials.sidebarfooter')
@endsection
