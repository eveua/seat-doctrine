@extends('web::layouts.grids.12')

@push('head')
    <link rel="stylesheet" type="text/css" href="{{ asset('web/doctrine/css/doctrine.css') }}" />
@endpush

@section('page_header')
    @php($routeName = Route::current()->getName())
    <ul class="nav nav-pills doctrine-nav">
        <li class="nav-item">
            <a
                    class="nav-link {{ preg_match('/doctrine\.doctrine.+/', $routeName) ? 'active bg-dark' : ''}}"
                    href="{{ route('doctrine.doctrineList') }}"
            >
                Doctrines
            </a>
        </li>
        <li class="nav-item">
            <a
                    class="nav-link {{ preg_match('/doctrine\.fitting.+/', $routeName) ? 'active bg-dark' : '' }}"
                    href="{{ route('doctrine.fittingList') }}"
            >
                Fittings
            </a>
        </li>
    </ul>
@endsection

@section('full')
    @yield('doctrineContent')
@endsection
