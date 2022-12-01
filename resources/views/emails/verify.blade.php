
{{--@component('mail::message')--}}

{{--<div class="container">--}}
{{--    <div class="row justify-content-center">--}}
{{--        <div class="col-md-8">--}}
{{--            <div class="card">--}}
{{--                <div class="card-header">{{ __('Подтверждение почты') }}</div>--}}

{{--                <div class="card-body">--}}
                    <h5>Здравствуйте!</h5>
                    <p>Нажмите на кнопку ниже, чтобы подтвердить почту.</p>

                    @component('mail::button', ['url' => $url])
                        Подтвердить почту
                    @endcomponent

                    <p></p>
                    <p>Если кнопа не работает, перейдите по ссылке {{ $url }}</p>
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}


{{--@endcomponent--}}
