@push('content-col-classes')
    align-self-start
@endpush
<div class="meeting-days position-relative p-4">
    <div class="left-arrow direction d-flex d-md-none" data-js="changeDay" data-dir="prev">
        <img class="icon icon-40" src="img/arrow.svg" alt="предыдущий день">
    </div>
    <div class="right-arrow direction d-flex d-md-none" data-js="changeDay" data-dir="next">
        <img class="icon icon-40" src="img/arrow.svg" alt="следующий день">
    </div>

    <p class="text-center text-md-start meeting-days-date fs-10" data-js="date">{{ $date }}</p>
    <ul class="meeting-days-menu justify-content-center justify-content-md-between">
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
<div class="row mt-2 mt-md-4 px-0 px-lg-4 meeting-filters">
    <div class="fs-4 col px-4 p-lg-0 fw-bold time d-none d-md-flex">
        Сейчас&nbsp;<span data-js="clock">{{ $time }}</span>
    </div>
    <div class="col-12 col-lg-3 mt-3 mt-lg-0 px-0 pl-lg-3 d-flex justify-content-end view-buttons">
        <select class="form-control rounded-0 w-100" data-js="selectCity">
            <option value="0">Все города</option>
            @foreach($cities as $cityName)
                <option value="{{ $cityName }}" {{ $cityName == $city ? 'selected="selected"' : '' }}>{{ $cityName }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-12 col-lg-3 mt-3 mt-lg-0 px-0 pl-lg-3 d-flex justify-content-end view-buttons">
        <select class="form-control rounded-0 w-100" data-js="selectLocation">
            <option value="0">Все районы</option>
            @foreach($locations as $locationName)
                <option value="{{ $locationName }}" {{ $locationName == $location ? 'selected="selected"' : '' }}>{{ $locationName }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-12 col-lg-3 mt-3 mt-lg-0 px-0 pl-lg-3 d-flex justify-content-end view-buttons">
        <select class="form-control rounded-0 w-100" data-js="switchView">
            <option value="list">Списком</option>
            <option value="week">Вся неделя</option>
            <option value="map">На карте</option>
        </select>
    </div>
</div>
<div id="map" class="mt-4"></div>
<div class="all-meetings">
    @include('site::widgets.partials.meetings', ['meetingDays' => $meetingDays])
</div>
<script src="{{ asset('js/site-meetings.js') }}"></script>