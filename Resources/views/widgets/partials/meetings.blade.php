@foreach($meetingDays as $meetingDay)
    @php
        if(
            $meetingDay->meeting->type == \App\Enums\MeetingsType::OnlyStream ||
            (
                $meetingDay->meeting->type == \App\Enums\MeetingsType::LiveAndStream &&
                in_array($meetingDay->online, [1, 2])
            )
        ) {
            $online = true;
        } else {
            $online = false;
        }
    @endphp
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

            <span class="group-info-row group-address">
                @if($online)
                    <a rel="noopener" href="{{ $meetingDay->meeting->link }}" target="_blank">{!! str_replace(' ', '&nbsp;', $meetingDay->meeting->link_text) !!}</a>
                    @if(!empty($meetingDay->meeting->password))
                        <span class="d-inline-block ms-3">Код:&nbsp;{{ $meetingDay->meeting->password  }}</span>
                    @endif
                @else
                    <span class="icon icon-address top-0 me-3 d-none d-md-inline-block"></span>
                    {!! str_replace(' ', '&nbsp;', $meetingDay->meeting->address) . ((!empty($meetingDay->meeting->address_description)) ? ", " . str_replace(' ', '&nbsp;', $meetingDay->meeting->address_description) : '') !!}
                @endif
            </span>
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
                <button class="btn-href">{{ $online ? 'Онлайн' : $meetingDay->meeting->location }}</button>
            </p>
            <p class="meeteng-description-text fs-16 fs-md-14 fs-xl-16">
                {{ $meetingDay->meetingDayFormat->title }}
                <button class="btn-href d-inline-block" tabindex="0" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-content="{{ $meetingDay->meetingDayFormat->description }}">
                    <i class="bi bi-info-square" role="img" aria-label="Что это значит?"></i>
                </button>
            </p>
            @if(!empty($meetingDay->format_second))
                <p class="meeteng-description-text fs-16 fs-md-14 fs-xl-16">
                    {{ $meetingDay->format_second }}
                </p>
            @endif
        </div>
    </div>
@endforeach