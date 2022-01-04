<div class="row">
    <div class="fixed-top d-flex d-md-none top-contacts">
        <a href="mailto:mail@na-ekb.ru">mail@na-ekb.ru</a>
        <a href="tel:+79222961212">+7 922 296 12 12</a>
    </div>
    <nav class="navbar navbar-expand-xl fixed-bottom fixed-md-top navbar-blue mobile-nav">
        <div class="container">
            <a class="navbar-brand" href="/">
                <div class="logo-nav">
                </div>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu" aria-controls="navbarMenu" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarMenu">
                <ul class="navbar-nav me-auto mb-2 mt-4 mt-xl-0">
                    <li class="nav-item"><a @class(['nav-link', 'active' => ($page->slug == 'home')]) href="{{ route('pages') }}">Главная</a></li>
                    <li class="nav-item"><a @class(['nav-link', 'active' => ($page->slug == 'info')]) href="{{ route('pages', ['slug' => 'info']) }}">Информация об АН</a></li>
                    <li class="nav-item"><a @class(['nav-link', 'active' => ($page->slug == 'meetings')]) href="{{ route('pages', ['slug' => 'meetings']) }}">Расписание собраний</a></li>
                    <li class="nav-item"><a @class(['nav-link', 'active' => ($page->slug == 'feedback')]) href="{{ route('pages', ['slug' => 'feedback']) }}">Связь с нами</a></li>
                </ul>
            </div>
        </div>
    </nav>
</div>