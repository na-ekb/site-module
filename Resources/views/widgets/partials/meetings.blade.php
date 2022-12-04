@forelse($meetingDays as $meetingDay)
    @php
        if(empty($meetingDay->meeting) || (empty($meetingDay->meeting->type) && $meetingDay->meeting->type != 0)) {
            continue;
        }
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
    <div class="meeting-group gray-group p-4 flex-column flex-md-row my-3">
        <div class="meeting-group-all-time">
            <div class="meeting-group-time">
                <div class="meeting-group-time-block">
                    <span>{{ $meetingDay->time[0] }}</span>
                </div>
                <div class="meeting-group-time-block">
                    <span>{{ $meetingDay->time[1] }}</span>
                </div>
                <div class="meeting-group-time-center">
                    <span>:</span>
                </div>
                <div class="meeting-group-time-block">
                    <span>{{ $meetingDay->time[3] }}</span>
                </div>
                <div class="meeting-group-time-block">
                    <span>{{ $meetingDay->time[4] }}</span>
                </div>
            </div>
            @php
                $hours = floor($meetingDay->duration / 60);
                $minutes = $meetingDay->duration % 60;
            @endphp
            <p> {{ morphos\Russian\pluralize($hours, 'час') }} @if($minutes > 0) {{ morphos\Russian\pluralize($minutes, 'минута') }} @endif </p>
        </div>
        <div class="meeting-group-info align-items-center align-items-md-start px-1 px-md-5">
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
                    <div class="d-flex flex-column align-items-center align-items-md-start">
                        <div>
                            <button type="button" class="btn-href" data-js="selectCity" data-city="{{ $meetingDay->meeting->city }}">{!! str_replace(' ', '&nbsp;', $meetingDay->meeting->city) !!}</button>
                        </div>
                        {!! str_replace(' ', '&nbsp;', $meetingDay->meeting->address) . ((!empty($meetingDay->meeting->address_description)) ? ", " . str_replace(' ', '&nbsp;', mb_lcfirst($meetingDay->meeting->address_description)) : '') !!}
                    </div>
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
        <div class="meeting-description">
            <p class="meeting-description-title d-flex justify-content-center justify-content-md-end  col-4 col-md-12 order-sm-1 order-md-1 fs-16 fs-md-14 fs-xl-16">
                <button class="btn-href">{{ $online ? 'Онлайн' : $meetingDay->meeting->location }}</button>
            </p>
            <p class="meeting-description-text d-flex justify-content-center justify-content-md-end col-4 col-md-12 order-sm-3 order-md-2 order-lg-3 fs-16 fs-md-14 fs-xl-16">
                {{ $meetingDay->meetingDayFormat->title }}
                <button class="btn-href d-inline-block ms-1" tabindex="0" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-content="{{ $meetingDay->meetingDayFormat->description }}">
                    <i class="bi bi-info-square" role="img" aria-label="Что это значит?"></i>
                </button>
            </p>
            @if(!empty($meetingDay->format_second))
                <p class="meeting-description-text d-flex justify-content-center justify-content-md-end col-4 col-md-12 order-sm-2 order-md-3 fs-16 fs-md-14 fs-xl-16">
                    {{ $meetingDay->format_second }}
                </p>
            @endif
        </div>
    </div>
@empty
    <div class="meeting-group gray-group p-4 flex-column flex-md-row my-3 justify-content-center">
        <div>
            Собраний по такому фильтру не найдено.
        </div>
    </div>
@endforelse