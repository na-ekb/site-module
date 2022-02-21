<hr>
<br>
<div id="jft">
    <h2>Ежедневные размышления</h2>
    <div class="eg_header">
        {!! $jft->header !!}
    </div>
    <div class="eg_quote">
        <em>{!! $jft->quote !!}</em>
    </div>
    <div class="eg_quote_from">
        {!! $jft->from !!}
    </div>

    <div class="eg_more">
        <a href="{{ config('Site.site_jft_link') }}" rel="noopener" target="_blank">Читать дальше....</a>
    </div>
</div>