import { Popover, Modal, Collapse } from 'bootstrap';
import { gsap } from 'gsap';
import { Flip } from 'gsap/Flip';
import { TextPlugin } from 'gsap/TextPlugin';
import axios from 'axios';

var jsDebug = getQueryVariable('jsDebug') || false;

Object.defineProperty(String.prototype, 'capitalize', {
    value: function() {
        return this.charAt(0).toUpperCase() + this.slice(1);
    },
    enumerable: false
});

gsap.registerPlugin(Flip);
gsap.registerPlugin(TextPlugin);

document.addEventListener('DOMContentLoaded', function(){
    popoverRegister();
    addMap();

    findAndCall('[data-js="clock"]', false, (clockEls) => {
        updateClocks(clockEls);
        window.setInterval(function(){
            updateClocks(clockEls);
        },10000);
    });

    findAndCall('[data-js="date"]', false, (dateEls) => {
        updateDates(dateEls);
        window.setInterval(function(){
            updateDates(dateEls);
        },10000);
    });

    findAndCall('[data-js="filterDay"]', true, (chooseDayEl) => {
        chooseDayEl.onclick = (ev) => {
            let city = document.querySelector('[data-js="selectCity"]').value;
            changeDayOfWeek(parseInt(ev.target.dataset.day), city);
        };
    });

    findAndCall('[data-js="changeDay"]', true, (changeDayEl) => {
        changeDayEl.onclick = (ev) => {
            let city = document.querySelector('[data-js="selectCity"]').value;
            if (typeof ev.target.dataset.dir == 'undefined') {
                changeDayMobile(ev.target.parentElement.dataset.dir, city);
            } else {
                changeDayMobile(ev.target.dataset.dir, city);
            }
        };
    });

    findAndCall('[data-js="switchView"]', true, (switchViewEl) => {
        switchViewEl.onchange = (ev) => {
            if (ev.target.value === 'list') {
                switchToList();
            } else if (ev.target.value === 'week') {
                switchToWeek();
            } else {
                switchToMap();
            }
        };
    });

    buttonsListRegister();

    Object.defineProperty(window, 'chosenDate', {
        get: function(){
            return this._chosenDate;
        },
        set: function(val){
            this._chosenDate = val;
            window.history.replaceState(null, '', getFullUrl());
        }
    });
    if (getQueryVariable('date') === undefined) {
        window._chosenDate = new Date();
    } else {
        window._chosenDate = new Date(getQueryVariable('date'));
    }
});

function updateClocks(els) {
    let now = new Date();
    let formatter = new Intl.DateTimeFormat('ru-RU', {
        hour: '2-digit',
        minute: '2-digit',
        timeZone: window.timezone
    });
    let formattedDate = formatter.format(now);
    els.map((timeEl) => {
        timeEl.innerHTML = formattedDate;
    });
}

function updateDates(els) {
    setTimeout(() => {
        let nowTz = new Date(new Date().toLocaleString('en', {timeZone: window.timezone}));
        let chosenDay = parseInt(document.querySelector('.meeting-days-menu>li.active>button').dataset.day)
        if (nowTz.getDay() === chosenDay) {
            let formatter = new Intl.DateTimeFormat('ru-RU', {
                day:        'numeric',
                month:      'long',
                year:       'numeric',
                timeZone:   window.timezone
            });
            let formattedDate = formatter.format(nowTz);
            els.map((dateEl) => {
                dateEl.innerHTML = formattedDate.replace(/\s*г\./, "");
            });
        }
    }, 300);
}

function changeDayOfWeek(day, city = null) {
    if (typeof window.currentBtnAnim !== 'undefined' || typeof window.chosenBtnAnim !== 'undefined') {
        return;
    }

    let currentEl = document.querySelector(`.meeting-days-menu>li.active`);
    let currentButton = document.querySelector(`.meeting-days-menu>li.active>button`);
    let chosenEl = document.querySelector(`.meeting-days-menu [data-js="filterDay"][data-day="${day}"]`).closest('li');
    let chosenButton = document.querySelector(`.meeting-days-menu [data-js="filterDay"][data-day="${day}"]`)

    let now;
    if (currentButton.dataset.day === day) {
        now = window.chosenDate;
    } else {
        now = new Date();
        let formatter = new Intl.DateTimeFormat('ru-RU', {
            day:        'numeric',
            month:      'long',
            year:       'numeric',
            timeZone:   window.timezone
        });
        let dayNow = now.getDay();

        findAndCall('[data-js="date"]', false, (dateEls) => {
            let distance;
            if ((day >= dayNow || day === 0) && dayNow !== 0) {
                if (day === 0) {
                    day = 7;
                }
                distance = day - dayNow;
            } else {
                distance = dayNow === 0 ? day : day + (7 - dayNow);
            }
            now.setDate(now.getDate() + distance);
            let formattedDate = formatter.format(now);
            dateEls.map((dateEl) => {
                dateEl.innerHTML = formattedDate.replace(/\s*г\./, "");
            });
        });

        window.chosenDate = now;
    }

    updateMeetingsDate(now, city);

    window.currentBtnAnim = gsap.to(currentButton, {
        duration: 0.3,
        'font-weight': 100,
        'font-size': '1rem',
        onStart: () => {
            currentButton.classList.add('scale-after-100');
        },
        onComplete: () => {
            currentButton.classList.remove('scale-after-100');
            currentEl.classList.remove('active');
            delete window.currentBtnAnim;
        }
    });

    window.chosenBtnAnim = gsap.to(chosenButton, {
        duration: 0.3,
        'font-weight': 700,
        'font-size': '1.5rem',
        onStart: () => {
            chosenButton.classList.add('scale-after-0');
        },
        onComplete: () => {
            chosenButton.classList.remove('scale-after-0');
            chosenEl.classList.add('active');
            delete window.chosenBtnAnim;
        }
    });
}

function changeDayMobile(dir, city = null) {
    if (typeof window.currentBtnAnim !== 'undefined' || typeof window.chosenBtnAnim !== 'undefined') {
        return;
    }

    let date = window.chosenDate;

    if (dir === 'next') {
        date.setDate(date.getDate() + 1);
    } else {
        date.setDate(date.getDate() - 1);
    }

    window.chosenDate = date;

    updateMeetingsDate(date, city);


    let formatter = new Intl.DateTimeFormat('ru-RU', {
        day:        'numeric',
        month:      'long',
        year:       'numeric',
        timeZone:   window.timezone
    });

    let formattedDate = formatter.format(date);
    gsap.to('[data-js="date"]', {duration: 0.3, text: formattedDate.replace(/\s*г\./, ""), ease: 'power2'});

    let currentEl = document.querySelector(`.meeting-days-menu>li.active`);
    let chosenEl = document.querySelector(`.meeting-days-menu [data-js="filterDay"][data-day="${date.getDay()}"]`).closest('li');

    window.currentBtnAnim = gsap.to(currentEl, {
        duration: 0.3,
        display: 'none',
        onComplete: () => {
            currentEl.classList.remove('active');
            delete window.currentBtnAnim;
        }
    });

    window.chosenBtnAnim = gsap.to(chosenEl, {
        duration: 0.3,
        display: 'list-item',
        onComplete: () => {
            chosenEl.classList.add('active');
            delete window.chosenBtnAnim;
        }
    });
}

function updateMeetingsDate(date, city = null) {
    let backendFormatter = new Intl.DateTimeFormat('ru', {
        timeZone: window.timezone
    });

    axios.get(route('widgetAction', {name: 'meetings', action: 'partialMeetings'}), {
        params: {
            date: backendFormatter.format(date),
            city: city
        }
    }).then((response) => {
        let allMeetings = document.querySelector('.all-meetings');
        let el = document.createElement( 'div');
        el.innerHTML = response.data;
        while (el.firstChild) {
            let dNoneEl = el.firstChild;
            dNoneEl.classList.add('meeting-invisible');
            dNoneEl.classList.add('new-meeting');
            allMeetings.append(el.firstChild);
            el.removeChild(el.firstChild);
        }

        let meetings = gsap.utils.toArray('.all-meetings>.meeting-group');

        let state = Flip.getState(meetings);

        for (let i = 0; i < meetings.length; i++) {
            meetings[i].classList.toggle('meeting-invisible');
        }

        Flip.from(state, {
            duration: 1,
            absolute: true,
            ease: "power1.inOut",
            scale: true,
            onComplete: () => {
                let oldMeetings = document.querySelectorAll('.all-meetings>.meeting-group:not(.new-meeting)');
                oldMeetings.forEach((el) => {
                    el.parentElement.removeChild(el);
                });

                let newMeetings = document.querySelectorAll('.all-meetings>.meeting-group.new-meeting');
                newMeetings.forEach((el) => {
                    el.classList.remove('new-meeting');
                });

                popoverRegister();
            }
        });

        buttonsListRegister();
    }).catch((error) => {
        // handle error
        console.log(error);
    });
}

function popoverRegister() {
    findAndCall('[data-bs-toggle="popover"]', true, (popoverTriggerEl) => {
        return new Popover(popoverTriggerEl, {});
    });
}

function switchToList() {
    findAndCall('[data-js="selectCity"]', true, (selectCityEl) => {
        selectCityEl.disabled = false;
    });
    findAndCall('[data-js="selectLocation"]', true, (selectCityEl) => {
        selectCityEl.disabled = false;
    });
    findAndCall('[data-js="filterDay"]', true, (selectCityEl) => {
        selectCityEl.disabled = false;
    });
    findAndCall('[data-js="changeDay"]', true, (selectCityEl) => {
        selectCityEl.style.zIndex = '1';
    });

    let buttonListEl = document.querySelector('.view-buttons>[data-view="list"]');
    gsap.to(buttonListEl, {
        duration: 0.15,
        scale: 0,
        display: 'none',
        onComplete: () => {
            let buttonMapEl = document.querySelector('.view-buttons>[data-view="map"]');
            gsap.to(buttonMapEl, {
                duration: 0.15,
                scale: 1,
                display: 'inline-block'
            });
        }
    });

    let mapEl = document.querySelector('#map');
    gsap.to(mapEl, {
        duration: 0.15,
        scale: 0,
        height: 0,
        onComplete: () => {
            let allMeetingsEm = document.querySelector('.all-meetings');
            gsap.to(allMeetingsEm, {
                duration: 0.15,
                scale: 1,
                height: 'auto'
            });
        }
    });
}

function buttonsListRegister() {
    findAndCall('[data-js="selectCity"]', true, (selectCityEl) => {
        if (selectCityEl.nodeName === 'BUTTON') {
            selectCityEl.onclick = (ev) => {
                let select = document.querySelector('[data-js="selectCity"]');
                select.value = ev.target.dataset.city;
                select.dispatchEvent(new Event('change'));
            };
        } else {
            selectCityEl.onchange = (ev) => {
                let day = document.querySelector('.meeting-days-menu>li.active>button').dataset.day;
                window.history.replaceState(null, '', getFullUrl());
                changeDayOfWeek(day, ev.target.value);
            };
        }
    });
}

function switchToWeek() {
    findAndCall('[data-js="selectCity"]', true, (selectCityEl) => {
        selectCityEl.disabled = true;
    });
    findAndCall('[data-js="selectLocation"]', true, (selectCityEl) => {
        selectCityEl.disabled = true;
    });
    findAndCall('[data-js="filterDay"]', true, (selectCityEl) => {
        selectCityEl.disabled = true;
    });
    findAndCall('[data-js="changeDay"]', true, (selectCityEl) => {
        selectCityEl.style.zIndex = '1';
    });
}

function addMap() {
    let s = document.createElement('script');
    s.type = 'text/javascript';
    s.async = true;
    s.src = `https://api-maps.yandex.ru/2.1/?apikey=5d5281d2-41f9-469d-9d1e-568c70c5e947&lang=ru_RU`;
    let fs = document.getElementsByTagName('script')[0];
    fs.parentNode.insertBefore(s, fs);
}

function switchToMap() {
    findAndCall('[data-js="selectCity"]', true, (selectCityEl) => {
        if (selectCityEl.nodeName === 'SELECT') {
            selectCityEl.value = 0;
            selectCityEl.dispatchEvent(new Event('change'));
        }
        selectCityEl.disabled = true;
    });

    //TODO: switch to current day

    findAndCall('[data-js="filterDay"]', true, (selectCityEl) => {
        selectCityEl.disabled = true;
    });
    findAndCall('[data-js="selectLocation"]', true, (selectCityEl) => {
        selectCityEl.disabled = true;
    });
    findAndCall('[data-js="changeDay"]', true, (selectCityEl) => {
        selectCityEl.style.zIndex = '-1000';
    });

    ymaps.ready(function() {
        let buttonMapEl = document.querySelector('.view-buttons>[data-view="map"]');
        gsap.to(buttonMapEl, {
            duration: 0.15,
            scale: 0,
            display: 'none',
            onComplete: () => {
                let buttonListEl = document.querySelector('.view-buttons>[data-view="list"]');
                gsap.to(buttonListEl, {
                    duration: 0.15,
                    scale: 1,
                    display: 'inline-block'
                });
            }
        });

        let allMeetingsEm = document.querySelector('.all-meetings');
        gsap.to(allMeetingsEm, {
            duration: 0.15,
            scale: 0,
            height: 0,
            onComplete: () => {
                let mapEl = document.querySelector('#map');
                gsap.to(mapEl, {
                    duration: 0.15,
                    scale: 1,
                    height: '600px',
                    onComplete: () => {
                        if (typeof window.meetingsMap == 'undefined') {
                            createMap();
                        }
                    }
                });
            }
        });
    });
}

function createMap() {
    window.meetingsMap = new ymaps.Map('map', {
        controls: [
            'geolocationControl',
            'routeButtonControl',
            'fullscreenControl',
            'zoomControl'
        ],
        center: [56.84072085599738, 60.60003498242182],
        zoom: 10
    });

    /*
    let types = [
        new ymaps.control.ListBoxItem({
            data: {
                content: 'Открытая',
                type: 0
            },
            state: {
                selected: true
            }
        }),
        new ymaps.control.ListBoxItem({
            data: {
                content: 'Закрытая',
                type: 1
            },
            state: {
                selected: true
            }
        })
    ];

    let typesListBox = new ymaps.control.ListBox({
        data: {
            content: 'Формат собрания'
        },
        items: types
    });

    typesListBox.events.add('click', function (e) {
        var item = e.get('target');
        if (item != typesListBox) {
            console.log(!item.state.getAll().selected);
        }
    });

    window.meetingsMap.controls.add(typesListBox);
    */

    axios.get(route('api.get.meetings')).then((response) => {
        let groupsCollection = [];

        let days = [
            'ВС', 'ПН', 'ВТ', 'СР', 'ЧТ', 'ПТ', 'СБ'
        ]

        for (let i = 0; i < response.data.length; i++) {
            let group = response.data[i];
            if (group.type === 2) {
                continue;
            }

            let address = (typeof group.address !== "undefined" ? group.address[window.siteLocale] : '') + (typeof group['address_description'] !== "undefined" && typeof group['address_description'][window.siteLocale] !== "undefined" && group['address_description'][window.siteLocale] !== null ? `, ${group['address_description'][window.siteLocale]}` : '');
            group.address = address;

            let meetingDays = [];
            for (let n = 0; n < group['meeting_days'].length; n++) {
                let meeting = group['meeting_days'][n];

                if (meeting.type !== 'meeting-day-regular' || meeting['day_type'] !== 1) {
                    continue;
                }

                let time = meeting.time.split(':');
                time = `${time[0]}:${time[1]}`;

                let meetingDay = {
                    format: meeting.format,
                    time: time,
                    duration: meeting.duration
                };

                if (typeof meetingDays[meeting.day] !== 'undefined') {
                    meetingDays[meeting.day] = [
                        meetingDays[meeting.day],
                        meetingDay
                    ];
                } else {
                    meetingDays[meeting.day] = meetingDay;
                }
            }

            let balloonContent = '<div class="balloon-blocks">';
            for (let n = 1; n < 8; n++) {
                let meetingDay;
                if (n === 7) {
                    meetingDay = meetingDays['0'];
                } else {
                    meetingDay = meetingDays[n.toString()];
                }

                balloonContent += `<span class="balloon-block ${typeof meetingDay === 'undefined' ? 'none' : ''}">`;
                balloonContent += `<span class="balloon-title"> ${ n === 7 ? days[0] : days[n] } </span>`;
                if (typeof meetingDay !== 'undefined') {
                    if (Array.isArray(meetingDay)) {
                        for (let x = 0; x < meetingDay.length; x++) {
                            balloonContent += `<span class="balloon-time"> ${ meetingDay[x].time } </span>`;
                        }
                    } else {
                        balloonContent += `<span class="balloon-time"> ${ meetingDay.time } </span>`;
                    }
                }
                balloonContent += `</span>`;
            }
            balloonContent += '</div>';

            groupsCollection.push(new ymaps.GeoObject({
                geometry: {
                    type: 'Point',
                    coordinates: [group.lat, group.long]
                },
                properties: {
                    clusterCaption: group.title[window.siteLocale],
                    balloonContentHeader: `<span class="text-blue">${group.title[window.siteLocale]}</span>`,
                    balloonContentFooter: group.address,
                    balloonContentBody: balloonContent
                }
            }, {
                balloonMinWidth: 270,
                iconLayout: 'default#image',
                iconImageHref: 'img/minilogo-map.svg',
                iconImageSize: [30, 30],
                iconImageOffset: [-15, -15]
            }));
        }

        let meetengsClusterer = new ymaps.Clusterer({
            disableClickZoom: true,
            hideIconOnBalloonOpen: false,
            geoObjectHideIconOnBalloonOpen: false,
            hasHint: false,
            clusterIconContentLayout: ymaps.templateLayoutFactory.createClass('<div class="clusterer-icon">{{ properties.geoObjects.length }}</div>'),
            clusterIcons: [
                {
                    href: 'img/cluster.svg',
                    size: [30, 30],
                    offset: [-15, -15]
                },
                {
                    href: 'img/cluster.svg',
                    size: [30, 30],
                    offset: [-15, -15]
                }]
        });
        meetengsClusterer.add(groupsCollection);
        window.meetingsMap.geoObjects.add(meetengsClusterer);
    }).catch((error) => {
        // handle error
        console.log(error);
    });


}

function findAndCall(selector, map, callback) {
    let elements = [].slice.call(document.querySelectorAll(selector));
    if (elements.length > 0) {
        if (map) {
            elements.map((element) => {
                callback(element);
            });
        } else {
            callback(elements);
        }
    }
}

function getQueryVariable(variable) {
    let query = window.location.search.substring(1);
    let vars = query.split('&');
    for (let i = 0; i < vars.length; i++) {
        var pair = vars[i].split('=');
        if (decodeURIComponent(pair[0]) == variable) {
            return decodeURIComponent(pair[1]);
        }
    }

    if (jsDebug) {
        console.log('Query variable %s not found', variable);
    }
}

function getFullUrl() {
    let date = window.chosenDate;
    let citySelect = document.querySelector('[data-js="selectCity"]');

    let params = {
        date: date.toISOString().split('T')[0]
    };

    if (citySelect.value !== '0') {
        params.city = citySelect.value;
    }

    let searchParams = new URLSearchParams(params);
    return (window.location.origin + window.location.pathname + '?' + searchParams);
}