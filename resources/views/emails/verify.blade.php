
{{--@component('mail::message')--}}

{{--<div class="container">--}}
{{--    <div class="row justify-content-center">--}}
{{--        <div class="col-md-8">--}}
{{--            <div class="card">--}}
{{--                <div class="card-header">{{ __('Подтверждение почты') }}</div>--}}

{{--                <div class="card-body">--}}
                    <h5>Здравствуйте {{$user->last_name}} {{$user->first_name}} {{$user->patronymic}}!</h5>

                    <p>
                        Вы были зарегистрированы на сайте
                        <a href="{{env('APP_URL')}}">{{env('APP_NAME')}}</a>
                    </p>
                    <p>Ваш аккаунт прикреплен к {{$hospital_full_name}}.</p>
                    <p>Вам назначена роль - {{$role}}.</p>
                    <br>
                    <p>Нажмите на кнопку ниже, чтобы подтвердить почту.</p>

                    @component('mail::button', ['url' => $url])
                        Подтвердить почту
                    @endcomponent

                    <br>
                    <p>Если кнопа не работает, перейдите по ссылке {{ $url }}</p>

{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}


{{--@endcomponent--}}
