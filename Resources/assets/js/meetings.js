import { Popover, Modal } from 'bootstrap';
import { gsap } from 'gsap';
import { Flip } from 'gsap/Flip';
import { TextPlugin } from 'gsap/TextPlugin';
import axios from 'axios';

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

    let timeEls = [].slice.call(document.querySelectorAll('[data-js="clock"]'));
    if (timeEls.length > 0) {
        updateClocks(timeEls);
        window.setInterval(function(){
            updateClocks(timeEls);
        },10000);
    }

    let dateEls = [].slice.call(document.querySelectorAll('[data-js="date"]'));
    if (dateEls.length > 0) {
        updateDates(dateEls);
        window.setInterval(function(){
            updateDates(dateEls);
        },10000);
    }

    let chooseDayEls = [].slice.call(document.querySelectorAll('[data-js="filterDay"]'));
    if (chooseDayEls.length > 0) {
        chooseDayEls.map((chooseDayEl) => {
            chooseDayEl.onclick = (ev) => {
                changeDayOfWeek(parseInt(ev.target.dataset.day));
            };
        });
    }

    let changeDayEls = [].slice.call(document.querySelectorAll('[data-js="changeDay"]'));
    if (changeDayEls.length > 0) {
        changeDayEls.map((changeDayEl) => {
            changeDayEl.onclick = (ev) => {
                if (typeof ev.target.dataset.dir == 'undefined') {
                    changeDayMobile(ev.target.parentElement.dataset.dir);
                } else {
                    changeDayMobile(ev.target.dataset.dir);
                }
            };
        });
    }

    let switchViewEls = [].slice.call(document.querySelectorAll('[data-js="switchView"]'));
    if (switchViewEls.length > 0) {
        switchViewEls.map((switchViewEl) => {
            switchViewEl.onclick = (ev) => {
                if (ev.target.dataset.view === 'list') {
                    switchToList();
                } else {
                    switchToMap();
                }
            };
        });
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
        let chosenDay = parseInt(document.querySelector('.meeteng-days-menu>li.active>button').dataset.day)
        if (parseInt(document.querySelector('.meeteng-days-menu>li:first-of-type>button').dataset.day) !== nowTz.getDay()) {
            let days = gsap.utils.toArray('.meeteng-days-menu>li');
            let state = Flip.getState(days);
            let ul = days[0].parentNode;
            let first = ul.children.item(0);
            ul.removeChild(first);
            ul.appendChild(first);
            Flip.from(state, {duration: 0.3});
        }
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

function changeDayOfWeek(day) {
    if (typeof window.currentBtnAnim !== 'undefined' || typeof window.chosenBtnAnim !== 'undefined') {
        return;
    }

    let currentEl = document.querySelector(`.meeteng-days-menu>li.active`);
    let currentButton = document.querySelector(`.meeteng-days-menu>li.active>button`);
    let chosenEl = document.querySelector(`.meeteng-days-menu [data-js="filterDay"][data-day="${day}"]`).closest('li');
    let chosenButton = document.querySelector(`.meeteng-days-menu [data-js="filterDay"][data-day="${day}"]`)

    if (currentButton.dataset.day == day) {
        return;
    }

    let now = new Date();
    let formatter = new Intl.DateTimeFormat('ru-RU', {
        day:        'numeric',
        month:      'long',
        year:       'numeric',
        timeZone:   window.timezone
    });
    let dayNow = now.getDay();
    let dateEls = [].slice.call(document.querySelectorAll('[data-js="date"]'));
    if (dateEls.length > 0) {
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
    }

    updateMeetingsDate(now);

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

function changeDayMobile(dir) {
    if (typeof window.currentBtnAnim !== 'undefined' || typeof window.chosenBtnAnim !== 'undefined') {
        return;
    }

    let now;

    if (typeof window.mobileDate == 'undefined') {
        now = new Date();
    } else {
        now = new Date(window.mobileDate);
    }

    if (dir === 'next') {
        now.setDate(now.getDate() + 1);
    } else {
        now.setDate(now.getDate() - 1);
    }

    window.mobileDate = now;

    updateMeetingsDate(now);

    let formatter = new Intl.DateTimeFormat('ru-RU', {
        day:        'numeric',
        month:      'long',
        year:       'numeric',
        timeZone:   window.timezone
    });

    let formattedDate = formatter.format(now);
    gsap.to('[data-js="date"]', {duration: 0.3, text: formattedDate.replace(/\s*г\./, ""), ease: 'power2'});

    let currentEl = document.querySelector(`.meeteng-days-menu>li.active`);
    let chosenEl = document.querySelector(`.meeteng-days-menu [data-js="filterDay"][data-day="${now.getDay()}"]`).closest('li');

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

    console.log(now.toLocaleDateString('ru', { weekday: 'long' }).capitalize());
}

function updateMeetingsDate(date) {
    let backendFormatter = new Intl.DateTimeFormat('ru', {
        timeZone: window.timezone
    });

    axios.get(route('widgetAction', {name: 'meetings', action: 'partialMeetings'}), {
        params: {
            date: backendFormatter.format(date)
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

        let meetings = gsap.utils.toArray('.all-meetings>.meeteng-group');

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
                let oldMeetings = document.querySelectorAll('.all-meetings>.meeteng-group:not(.new-meeting)');
                oldMeetings.forEach((el) => {
                    el.parentElement.removeChild(el);
                });

                let newMeetings = document.querySelectorAll('.all-meetings>.meeteng-group.new-meeting');
                newMeetings.forEach((el) => {
                    el.classList.remove('new-meeting');
                });

                popoverRegister();
            }
        });
    }).catch((error) => {
        // handle error
        console.log(error);
    });
}

function popoverRegister() {
    let popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    let popoverList = popoverTriggerList.map((popoverTriggerEl) => {
        return new Popover(popoverTriggerEl, {});
    });
}

function switchToList() {
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

function addMap() {
    let s = document.createElement('script');
    s.type = 'text/javascript';
    s.async = true;
    s.src = `https://api-maps.yandex.ru/2.1/?apikey=5d5281d2-41f9-469d-9d1e-568c70c5e947&lang=ru_RU`;
    let fs = document.getElementsByTagName('script')[0];
    fs.parentNode.insertBefore(s, fs);
}

function switchToMap() {
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
                    height: '400px',
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
}