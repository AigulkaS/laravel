<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Hospital;
use DateInterval;
use DateTime;

class BookingService {

    public function getAllHospitalsInfo() {
        $hospitals = Hospital::all();

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
                    'name' => $room->name,
                    'val' => $val,
                ];
            }

            $data[] = [
                'hospital_id' => $hospital->id,
                'hospital_name' => $hospital->short_name,
                'surgeon_id' => $hospital->today->surgeon_id,
                'surgeon_last_name' => $hospital->today->surgeon->last_name,
                'surgeon_first_name' => $hospital->today->surgeon->first_name,
                'surgeon_patronymic' => $hospital->today->surgeon->patronymic,
                'cardiologist_id' => $hospital->today->cardiologist_id,
                'cardiologist_last_name' => $hospital->today->cardiologist->last_name,
                'cardiologist_first_name' => $hospital->today->cardiologist->first_name,
                'cardiologist_patronymic' => $hospital->today->cardiologist->patronymic,
                'rooms' => $arrRoom,
            ];
        }
        return $data;
    }

    public function getHospitalInfoById($hospital_id) {
        
        $hospitals = Hospital::where('id', $hospital_id)->get();
        $hospital = $hospitals[0];
        $rooms = $hospital->rooms;

        $arrRoom=[];
        foreach($rooms as $room) {
            $val = [];
            $start = new DateTime();
            $start->setTime(0,0,0);

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
            } while($i <24);

            $arrRoom[] = [
                'name' => $room->name,
                'val' => $val,
            ];
        }

        return [
            'hospital_id' => $hospital->id,
            'hospital_name' => $hospital->short_name,
            'surgeon_id' => $hospital->today->surgeon_id,
            'surgeon_last_name' => $hospital->today->surgeon->last_name,
            'surgeon_first_name' => $hospital->today->surgeon->first_name,
            'surgeon_patronymic' => $hospital->today->surgeon->patronymic,
            'cardiologist_id' => $hospital->today->cardiologist_id,
            'cardiologist_last_name' => $hospital->today->cardiologist->last_name,
            'cardiologist_first_name' => $hospital->today->cardiologist->first_name,
            'cardiologist_patronymic' => $hospital->today->cardiologist->patronymic,
            'rooms' => $arrRoom,
        ];
    }


    // public function store($data) {
    //     $booking = Booking::create($data);
    //     return $booking;
    // }

    // public function update($booking, $data) {
    //     $booking->update($data);
    //     return $booking->fresh();
    // }

    // public function delete($booking) {
    //     try {
    //         $booking->delete();
    //     } catch(\Exception $e) {
    //         DB::rollBack();
    //         return $e->getMessage();
    //     } 
    //     return "delete successfully";
    // }
}