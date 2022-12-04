<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    @include('site::layouts.master.partials.header')
    <body>
        @include('site::layouts.master.partials.menu')

        @section('sidebar')

        @show

        <div class="content container w-100 @stack('content-classes')">
            <div class="row w-100 @stack('content-row-classes')">
                <div class="col @stack('content-col-classes')">
                @yield('content')
                </div>
            </div>
        </div>
    </body>
    @include('site::layouts.master.partials.footer')
</html>