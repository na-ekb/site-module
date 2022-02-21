<div class="meeteng-days position-relative p-4">
    <div class="left-arrow direction d-flex d-md-none" data-js="changeDay" data-dir="prev">
        <img class="icon icon-40" src="img/arrow.svg" alt="предыдущий день">
    </div>
    <div class="right-arrow direction d-flex d-md-none" data-js="changeDay" data-dir="next">
        <img class="icon icon-40" src="img/arrow.svg" alt="следующий день">
    </div>

    <p class="text-center text-md-start meeteng-days-date fs-10" data-js="date">{{ $date }}</p>
    <ul class="meeteng-days-menu justify-content-center justify-content-md-between">
        @foreach($weekdays as $day => $name)
            <li @if($dayOfWeek == $day) class="active" @endif>
                <button data-js="filterDay" data-day="{{ $day }}">{{ $name }}</button>
            </li>
        @endforeach
    </ul>
    <div class="mob-time d-md-none text-center d-md-none mt-3">
        <p data-js="clock">{{ $time }}</p>
    </div>
</div>
<div class="row mt-4">
    <div class="fs-4 col px-4 fw-bold time d-none d-md-flex">
        Время&nbsp;<span data-js="clock">{{ $time }}</span>
    </div>
    <div class="col px-0 px-md-4 d-flex justify-content-end view-buttons">
        <button class="fs-6 fs-md-8 fs-lg-6" data-js="switchView" data-view="list">Списком</button>
        <button class="fs-6 fs-md-8 fs-lg-6 active" data-js="switchView" data-view="map">На карте</button>
    </div>
</div>
<div id="map" class="mt-4"></div>
<div class="all-meetings">
    @include('site::widgets.partials.meetings', ['meetingDays' => $meetingDays])
</div>
<script src="{{ asset('js/site-meetings.js') }}"></script>