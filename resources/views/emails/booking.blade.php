<h5>Здравствуйте {{ $data['user']['fio'] }}!</h5>
<p>Вы получили это письмо, потому что указаны дежурным.</p>

<div>{{$data['hospital']}}</div>
<div>{{$data['address']}}</div>
<div><span class="fw-bolder">Диагноз: </span>{{$data['disease']}}</div>
<div><span class="fw-bolder">Состояние пациента: </span>{{$data['condition']}}</div>
<div><span class="fw-bolder">Время бронирование операционной: </span>{{$data['time']}}</div>
