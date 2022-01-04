<hr>
<br>
<div id="jft">
    <h3>Ежедневные размышления</h3>
    <div class="eg_header">
        {{ $jft->header }}
    </div>
    <div class="eg_quote">
        <em>{{ $jft->quote }}</em>
    </div>
    <div class="eg_quote_from">
        {{ $jft->from }}
    </div>

    <div class="eg_more"><a href="{{ config('Site.site_jft_link') }}" target="_blank">Читать дальше....</a></div>
</div>