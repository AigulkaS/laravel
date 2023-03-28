<?php

use App\Models\Booking;
use App\Models\Hospital;
use App\Models\Operator;

getAllHospitalsInfo();

function getAllHospitalsInfo() {
    $now = new DateTime();
    $nowHour = $now->format('H');
    if ($nowHour >=0 && $nowHour <8) {
        $now = $now->modify('-1 day');
    }
    $now = $now->format('Y-m-d 00:00:00');

    // $hospitals = Hospital::all();
    $hospitals = Hospital::where('type',1)->get();

    $data = [];
    foreach($hospitals as $hospital) {
        $rooms = $hospital->rooms;

        $arrRoom=[];
        foreach($rooms as $room) {
            $val = [];
            $start = new DateTime();

            $i = 0;
            do {
                $time = $start->format('Y-m-d H:00:00');

                $bookings = Booking::where('hospital_id', $hospital->id)
                    ->where('room_id', $room->id)
                    ->where('date_time', $time)->get();

                $val[] = $bookings->isEmpty()
                    ? [
                        'time' => $time,
                        'status' => 0,
                    ]
                    : [
                        'time' => $time,
                        'status' => $bookings[0]->status,
                    ];

                $start->add(new DateInterval('PT1H'));
                $i++;
            } while($i <3);

            $arrRoom[] = [
                'id' => $room->id,
                'condition' =>$room->condition,
                'name' => $room->name,
                'start' => $room->start,
                'end' => $room->end,
                'val' => $val,
            ];
        }

        $operators = Operator::where('hospital_id', $hospital->id)
            ->where('date', $now)->get();
        $operator = $operators->isEmpty() ? null : $operators[0];

        $data[] = [
            'hospital_id' => $hospital->id,
            'hospital_name' => $hospital->short_name,
            'surgeon_id' => $operator == null ? 'default' : $operator->surgeon_id,
            'surgeon_last_name' => $operator == null ? 'default' : $operator->surgeon->last_name,
            'surgeon_first_name' => $operator == null ? 'default' : $operator->surgeon->first_name,
            'surgeon_patronymic' => $operator == null ? 'default' : $operator->surgeon->patronymic,
            'cardiologist_id' => $operator == null ? 'default' : $operator->cardiologist_id,
            'cardiologist_last_name' => $operator == null ? 'default' : $operator->cardiologist->last_name,
            'cardiologist_first_name' => $operator == null ? 'default' : $operator->cardiologist->first_name,
            'cardiologist_patronymic' => $operator == null ? 'default' : $operator->cardiologist->patronymic,
            'rooms' => $arrRoom,
        ];
    }
    return $data;
}

