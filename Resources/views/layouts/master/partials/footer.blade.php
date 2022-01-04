<footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    @stack('js')
    {!! $meta->scripts ?? '' !!}
    @if(!empty(config('Site.site_footer')))
    <div class="col-12 footer">
        {{ config('Site.site_footer') }}
    </div>
    @endif
</footer>