<div class="row">
    <div class="col-12 col-md-6  text-center text-md-start">
        <span class="phone-text">
            <span class="icon icon-phone"></span>
            Телефон
        </span>
        <br>
        <span class="phone">
            <a href="tel:+79222961212">+7 (922) 296 12 12</a>
        </span>
    </div>
    <div class="col-12 col-md-6 text-center text-md-end mt-4 mt-md-0">
        <span class="email-text">
            <span class="icon icon-mail"></span>
            E-mail
        </span>
        <br>
        <span class="email">
            <a href="mailto:mail@na-ekb.ru">mail@na-ekb.ru</a>
        </span>
    </div>
</div>
<form id="feedback" class="form-control mt-5 pt-4" method="post" action="{{ route('widgetAction', ['name' => 'feedback', 'action' => 'sendFeedback']) }}">
    @csrf
    <div class="row">
        <div class="mail col-12 px-3">Написать письмо</div>
        <div class="col-12 col-md-6 mb-3 px-3">
            <label for="feedbackName" class="form-label">Как к вам обращаться</label>
            <input type="text" name="name" class="form-control" id="feedbackName" tabindex="1" placeholder="Василий" value="{{ old('name', '') }}">
        </div>
        <div class="col-12 col-md-6 px-3">
            <label for="feedbackEmail" class="form-label">Email для ответа</label>
            <input type="email" name="email" class="form-control" id="feedbackEmail" tabindex="2" placeholder="mail@example.com" value="{{ old('email', '') }}">
        </div>
        <div class="col-12 px-3 mt-4 mt-md-1">
            <textarea class="form-control" name="msg" tabindex="3" placeholder="Письмо (это поле обязательно к заполнению)" required>{{ old('msg', '') }}</textarea>
            <label for="fz" class="w-100 mt-3">
                &NoBreak;&NoBreak;&NoBreak;&NoBreak;&NoBreak;Согласие на использование личной информации, согласно 152-ФЗ.
                Без соглясия, мы, формально, не можем принимать от вас ни имя, ни e-mail.
                Эти данные ни где не сохраняются, а используются исключительно для ответа вам.
                <input type="checkbox" name="fz" id="fz" tabindex="4" required>
            </label>
            <span class="feedback-err w-100">
                @if($errors->any())
                    {!! implode('', $errors->all('<div>:message</div>')) !!}
                @endif
            </span>
            <span class="feedback-suc">
                @if(!empty($success))
                    <div>{{ $success }}</div>
                @endif
            </span>
        </div>
        <div class="col-12 p-0 m-0">
            <button class="w-100" tabindex="5">Отправить</button>
        </div>
    </div>
</form>