<?php

namespace App\Services;

use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Models\Hospital;
use App\Models\Operator;
use App\Models\Today;
use DateInterval;
use DateTime;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class BookingService {

    public function getAllHospitalsInfo() {
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

    public function getHospitalInfoById($hospital_id) {

        $hospitals = Hospital::where('id', $hospital_id)->get();
        $hospital = $hospitals[0];
        $rooms = $hospital->rooms;

        $arrRoom=[];
        foreach($rooms as $room) {
            $val = [];
            $start = new DateTime();
            $startHour = $start->format('H');
            if ($startHour >=0 && $startHour <8) {
                $start = $start->modify('-1 day');
            }
            $start->setTime(8,0,0);

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
            } while($i <48);

            $arrRoom[] = [
                'id' => $room->id,
                'condition' =>$room->condition,
                'name' => $room->name,
                'val' => $val,
            ];
        }

        $arrDate=[];
        $now = new DateTime();
        $nowHour = $now->format('H');
        if ($nowHour >=0 && $nowHour <8) {
            $now = $now->modify('-1 day');
        }
        for ($i = 0; $i<2; $i++) {
            $operators = Operator::where('hospital_id', $hospital->id)
                ->where('date', $now->format('Y-m-d 00:00:00'))->get();
            $operator = $operators->isEmpty() ? null : $operators[0];
            $arrDate[] = [
                'date' => $now->format('Y-m-d 00:00:00'),
                'surgeon_id' => $operator == null ? 'default' : $operator->surgeon_id,
                'surgeon_last_name' => $operator == null ? 'default' : $operator->surgeon->last_name,
                'surgeon_first_name' => $operator == null ? 'default' : $operator->surgeon->first_name,
                'surgeon_patronymic' => $operator == null ? 'default' : $operator->surgeon->patronymic,
                'cardiologist_id' => $operator == null ? 'default' : $operator->cardiologist_id,
                'cardiologist_last_name' => $operator == null ? 'default' : $operator->cardiologist->last_name,
                'cardiologist_first_name' => $operator == null ? 'default' : $operator->cardiologist->first_name,
                'cardiologist_patronymic' => $operator == null ? 'default' : $operator->cardiologist->patronymic,
            ];
            $now = $now->modify('+1 day');
        }

        return [
            'hospital_id' => $hospital->id,
            'hospital_name' => $hospital->short_name,
            'dates' => $arrDate,
            'rooms' => $arrRoom,
        ];
    }

    public function getNearestHospital($data) {
        $geo_lat = floatval($data['geo_lat']);

        $geo_lon = floatval($data['geo_lon']);

        $dateTime = new DateTime();
        $dateTime = $dateTime->format('Y-m-d H:00:00');

        // $hospitals = Hospital::all()->toArray();
        $hospitals = Hospital::where('type',1)->get()->toArray();

        $arrBookedHospitals = [];
        $bookings = Booking::where('date_time', $dateTime)
                    ->where(function ($query) {
                        $query->where('status', 1)
                              ->orWhere('status', 2);
                    })
                    ->get();   
        foreach($bookings as $booking) {
            $hospital = $booking->hospital;

            $otherRoom = false;
            if (count($hospital->rooms)>1) {
                foreach($hospital->rooms as $room) {
                    $roomBooking = Booking::where('date_time', $dateTime)
                    ->where('hospital_id', $hospital->id)
                    ->where('room_id', $room->id)
                    ->where(function ($query) {
                        $query->where('status', 1)
                              ->orWhere('status', 2);
                    })
                    ->get();

                    if (count($roomBooking) == 0) {
                        $otherRoom = true;
                        break;
                    }
                }
            }

            if(!$otherRoom) {
                $arrBookedHospitals[] = $hospital;

            }
        }

        $bookedHospitals = collect($arrBookedHospitals)->toArray();

        $arrBookedHospitals = [];
        foreach($bookedHospitals as $bookedHospital) {
            unset($bookedHospital['rooms']);
            $arrBookedHospitals[]= $bookedHospital;
        }

        foreach($arrBookedHospitals as $bookedHospital) {
            $key = array_search($bookedHospital, $hospitals);
            unset($hospitals[$key]);
        }

        if(count($hospitals) == 0) {
            $bookings = Booking::where('date_time', $dateTime)
                    ->where('status', 2)
                    ->get();
            if ($bookings->isEmpty()) {
                $booking = Booking::where('date_time', $dateTime)
                ->where('status', 1)
                ->orderBy('id', 'asc')
                ->first();

                $hospitals[] = $booking->hospital;
            } else {
                foreach($bookings as $booking) {
                    $hospitals[] = $booking->hospital;
                }
            }

            if(count($hospitals) > 0) {
                $arrHospitals = collect($hospitals)->toArray();
                $hospitals = [];
                foreach($arrHospitals as $hospital) {
                    unset($hospital['rooms']);
                    $hospitals[]= $hospital;
                }
            }
        }


        if(count($hospitals) > 1) {
            $min = null;
            $hosp = null;
            foreach($hospitals as $hospital) {
                $dist = $this->getDistanceBetweenPoints($geo_lat, $geo_lon, floatval($hospital['geo_lat']), floatval($hospital['geo_lon']));
                if ($min == null || $dist < $min) {
                    $min = $dist;
                    $hosp = $hospital;
                }
            }
            return $hosp;
        } else {
            $hospitals = array_values($hospitals);
            return $hospitals[0];
        }


    }

    private function getDistanceBetweenPoints($latitude1, $longitude1, $latitude2, $longitude2) {
        $theta = $longitude1 - $longitude2;
        $miles = (sin(deg2rad($latitude1)) * sin(deg2rad($latitude2))) + (cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * cos(deg2rad($theta)));
        $miles = acos($miles);
        $miles = rad2deg($miles);
        $miles = $miles * 60 * 1.1515;
        // $feet = $miles * 5280;
        // $yards = $feet / 3;
        $kilometers = $miles * 1.609344;
        $meters = $kilometers * 1000;
        // return compact('miles','feet','yards','kilometers','meters');
        return $meters;
}

    public function store($data) {
        $storeData = [
            'hospital_id' => $data['hospital_id'],
            'disease_id' => $data['disease_id'],
            'condition_id' => $data['condition_id'],
            'user_id' => $data['user_id'],
        ];
        $hospital = Hospital::find($data['hospital_id']);
        $rooms = $hospital->rooms;
        $dateTime = new DateTime();
        if (count($rooms)>1) {
            $room_id = null;
            foreach($rooms as $room) {
                $booking = Booking::where('date_time', $dateTime->format('Y-m-d H:00:00'))
                    ->where('hospital_id', $hospital->id)
                    ->where('room_id', $room->id)
                    ->first();

                if ($booking == null) {
                    $room_id = $room->id;
                    break;
                } else {
                    if($booking->status == 0) {
                        $room_id = $room->id;
                        break;
                    } else if($booking->status == 1){
                        if ($room_id == null) {
                            $room_id = $room->id;
                        }
                    } else {
                        $room_id = $room->id;
                    }

                }
            }
            $storeData['room_id'] = $room_id;
        }
        else {
            $storeData['room_id'] = $rooms[0]->id;
        }

        $now = new DateTime();
        $nowHour = $now->format('H');
        if ($nowHour >=0 && $nowHour <8) {
            $now = $now->modify('-1 day');
        }
        $operator = Operator::where('hospital_id', $hospital->id)
                ->where('date', $now->format('Y-m-d 00:00:00'))->first();
        // $today = Today::where('hospital_id', $hospital->id)->first();

        $storeData['surgeon_id'] = $operator->surgeon_id;
        $storeData['cardiologist_id'] = $operator->cardiologist_id;
        if($data['disease_id'] == 1) {
            $storeData['status'] = 1;
        } else {
            $storeData['status'] = 2;
        }
        $bookings = [];

        try {
            DB::beginTransaction();
            //! for ($i = 0; $i < $data['booking_hours']; $i++) {
            //! BOOKING FOR 2 HOURS
            for ($i = 0; $i < 2; $i++) {
                $storeData['date_time'] = $dateTime->format('Y-m-d H:00:00');
                $booking = Booking::where('date_time', $storeData['date_time'])
                    ->where('hospital_id', $hospital->id)
                    ->where('room_id', $storeData['room_id'])
                    ->first();
                if ($booking == null) {
                    $booking = Booking::create($storeData);
                } else {
                    $booking->update($storeData);
                    $booking->fresh();
                }

                $bookings[]=$booking;
                $dateTime->add(new DateInterval('PT1H'));
            }
            DB::commit();
        } catch(\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
        return collect($bookings);
    }

    public function update($data) {
        $storeData = [
            'hospital_id' => $data['hospital_id'],
            'condition_id' => $data['condition_id'],
            'disease_id' => $data['status'] == 0 ? 2 : $data['status'],
            'status' => $data['status'],
            'room_id' => $data['room_id'],
            'user_id' => $data['user_id'],
        ];

        $now = new DateTime();
        $nowHour = $now->format('H');
        if ($nowHour >=0 && $nowHour <8) {
            $now = $now->modify('-1 day');
        }
        $operator = Operator::where('hospital_id', $data['hospital_id'])
                ->where('date', $now->format('Y-m-d 00:00:00'))->first();
        // $today = Today::where('hospital_id', $data['hospital_id'])->first();

        $storeData['surgeon_id'] = $operator->surgeon_id;
        $storeData['cardiologist_id'] = $operator->cardiologist_id;
        // $storeData['dispatcher_id'] = $storeData['surgeon_id'];
        // $storeData['user_id'] = auth('sanctum')->user()->id;

        $dateTime = new DateTime($data['date_time']);
        $bookings = [];

        try {
            DB::beginTransaction();
            for ($i = 0; $i < $data['booking_hours']; $i++) {
            //! BOOKING FOR 2 HOURS
            //! for ($i = 0; $i < 2; $i++) {
                $storeData['date_time'] = $dateTime->format('Y-m-d H:00:00');
                $booking = Booking::where('date_time', $storeData['date_time'])
                    ->where('hospital_id',$storeData['hospital_id'])
                    ->where('room_id', $storeData['room_id'])
                    ->first();
                    
                if ($booking == null) {
                    $booking = Booking::create($storeData);
                } else {
                    $booking->update($storeData);
                    $booking->fresh();
                }

                $bookings[]=$booking;
                $dateTime->add(new DateInterval('PT1H'));
            }
            DB::commit();
        } catch(\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
        return collect($bookings);
    }

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
