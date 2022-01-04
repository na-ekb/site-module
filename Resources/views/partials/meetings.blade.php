<div class="meeteng-days position-relative p-4">
    <div class="left-arrow direction d-flex d-md-none">
        <img class="icon icon-40" src="img/arrow.svg" alt="">
    </div>
    <div class="right-arrow direction d-flex d-md-none">
        <img class="icon icon-40" src="img/arrow.svg" alt="">
    </div>

    <p class="text-center text-md-start meeteng-days-date fs-10">01 сентабря 2020</p>
    <ul class="meeteng-days-menu justify-content-center justify-content-md-between">
        <li class="active">
            <button class="fs-4 fs-md-3 fs-lg-3 fw-bold" href="#">Вторник</button>
        </li>
        <li><button class="fs-6 fs-md-8 fs-lg-6 d-none d-md-block ">Среда</button></li>
        <li><button class="fs-6 fs-md-8 fs-lg-6 d-none d-md-block">Четверг</button></li>
        <li><button class="fs-6 fs-md-8 fs-lg-6 d-none d-md-block">Пятница</button></li>
        <li><button class="fs-6 fs-md-8 fs-lg-6 d-none d-md-block">Суббота</button></li>
        <li><button class="fs-6 fs-md-8 fs-lg-6 d-none d-md-block">Воскресенье</button></li>
        <li><button class="fs-6 fs-md-8 fs-lg-6 d-none d-md-block">Понедельник</button></li>
    </ul>
    <div class="mob-time d-md-none text-center d-md-none mt-3">
        <p>17:32</p>
    </div>
</div>
<div class="row">
    <div class="fs-4 col px-4 fw-bold time d-none d-md-flex">
        Время 23:02
    </div>
    <div class="col px-0 px-md-4 d-flex justify-content-end">
        <button class="fs-6 fs-md-8 fs-lg-6 active">Списком</button>
        <button class="fs-6 fs-md-8 ms-3 fs-lg-6">На карте</button>
    </div>
</div>
@foreach($meetingDays as $meetingDay)
<div class="meeteng-group gray-group p-4 flex-column flex-md-row my-3">
    <div class="meeteng-group-all-time">
        <div class="meeteng-group-time">
            <div class="meeteng-group-time-block">
                <span>{{ $meetingDay->time[0] }}</span>
            </div>
            <div class="meeteng-group-time-block">
                <span>{{ $meetingDay->time[1] }}</span>
            </div>
            <div class="meeteng-group-time-center">
                <span>:</span>
            </div>
            <div class="meeteng-group-time-block">
                <span>{{ $meetingDay->time[3] }}</span>
            </div>
            <div class="meeteng-group-time-block">
                <span>{{ $meetingDay->time[4] }}</span>
            </div>
        </div>
        @php
            $hours = floor($meetingDay->duration / 60);
            $minutes = $meetingDay->duration % 60;
        @endphp
        <p> {{ morphos\Russian\pluralize($hours, 'час') }} @if($minutes > 0) {{ morphos\Russian\pluralize($minutes, 'минута') }} @endif </p>
    </div>
    <div class="meeteng-group-info align-items-center align-items-md-start px-5">

        <div class="group-info-row group-name blue">
            <span class="icon icon-minilogo top-0 me-3 d-none d-md-inline-block"></span>
            {{ $meetingDay->meeting->title }}
        </div>

        <div class="group-info-row group-address">
            <span class="icon icon-address top-0 me-3 d-none d-md-inline-block"></span>
            {{ $meetingDay->meeting->address }}@if(!empty($meetingDay->meeting->address_description)), {{ $meetingDay->meeting->address_description }} @endisset
        </div>
        @if(!empty($meetingDay->meeting->description))
        <div class="group-info-row group-map icon-text">
            <span class="icon icon-path top-0 me-3 d-none d-md-inline-block"></span>
            <button class="btn-href" onclick="showPath('')">
                Как пройти
            </button>
        </div>
        @endif
    </div>
    <div class="meeteng-description">
        <p class="meeteng-description-title fs-16 fs-md-14 fs-xl-16">
            <button class="btn-href">Уралмаш</button>
        </p>
        <p class="meeteng-description-text fs-16 fs-md-14 fs-xl-16">
            {{ $meetingDay->meetingDayFormat->title }}
            <button class="btn-href" data-bs-toggle="tooltip" data-bs-trigger="hover focus" data-bs-content="{{ $meetingDay->meetingDayFormat->description }}">
                <i class="bi bi-info-square" role="img" aria-label="Что означает закрытая?"></i>
            </button>

            <br>
            {{ $meetingDay->format_second }}
            asdasdas
        </p>
    </div>
</div>
@endforeach