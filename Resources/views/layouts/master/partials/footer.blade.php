<footer>
    <link href="{{ asset('css/site.css') }}" rel="stylesheet">
    @stack('css')

    @routes
    <script>
        window.timezone = '{{ config('app.timezone') }}';
        window.siteLocale = localStorage.getItem('locale') === null ? '{!! config('default_lang') !!}' : localStorage.getItem('locale');
    </script>
    <script src="{{ asset('js/site-main.js') }}"></script>
    @stack('js')
    {!! $meta->scripts ?? '' !!}
    @if(!empty(config('Site.site_footer')))
    <div class="col-12 footer">
        {{ config('Site.site_footer') }}
    </div>
    @endif
</footer>