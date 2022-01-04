<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    @include('site::layouts.master.partials.header')
    <body>
        @include('site::layouts.master.partials.menu')

        @section('sidebar')
            This is the master sidebar.
        @show

        <div class="content container">
            <div class="row">
                <div class="col">
                @yield('content')
                </div>
            </div>
        </div>
    </body>
    @include('site::layouts.master.partials.footer')
</html>