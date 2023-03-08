<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Hospital;
use App\Models\Operator;
use DateInterval;
use DateTime;
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

    public function getDateTimeForBook() {
        $dateTime = new DateTime();
        
        $dateTime->add(new DateInterval('PT20M'));
        $dateMinute = $dateTime->format('i');
        $diff = 60 - $dateMinute;
        if ($diff <= 5) {
            $dateTime->add(new DateInterval('PT5M'));
        }

        $dateTime = $dateTime->format('Y-m-d H:00:00');
        return $dateTime;
    }

    public function getNearestHospital($data, $dateTime) {
        $geo_lat = floatval($data['geo_lat']);

        $geo_lon = floatval($data['geo_lon']);

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
        // $start->add(new DateInterval('PT1H'));PT5M
        $storeData = [
            'hospital_id' => $data['hospital_id'],
            'disease_id' => $data['disease_id'],
            'condition_id' => $data['condition_id'],
            'user_id' => $data['user_id'],
        ];
        $hospital = Hospital::find($data['hospital_id']);
        $rooms = $hospital->rooms;
        // $dateTime = new DateTime();
        $dateTime = $data['date_time'];
        if (count($rooms)>1) {
            $room_id = null;
            foreach($rooms as $room) {
                $booking = Booking::where('date_time', $dateTime)
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


        $now = DateTime::createFromFormat('Y-m-d h:i:s', $dateTime);
        // $now = new DateTime();
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
            $dateTime = DateTime::createFromFormat('Y-m-d h:i:s', $dateTime);

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
                    //! +1 hour, what info
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

    public function storeNew($data) {
        $dateTime = new DateTime();
        
        $dateTime->add(new DateInterval('PT20M'));
        // $dateMinute = $dateTime->format('i');
        // $diff = 60 - $dateMinute;
        // if ($diff <= 5) {
        //     $dateTime->add(new DateInterval('PT5M'));
        // }
        // $dateTime = $dateTime->format('Y-m-d H:00:00');

        $geo_lat = floatval($data['geo_lat']);

        $geo_lon = floatval($data['geo_lon']);

        // $hospitals = Hospital::all()->toArray();
        $hospitals = Hospital::where('type',1)
                            ->whereHas('rooms', function ($query) {
                                $query->where('condition', 1);
                            })
                            ->get()->toArray();

        $arrBookedHospitals = [];
        $bookings = Booking::where('date_time', $dateTime->format('Y-m-d H:00:00'))
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
                    if($room->condition == 1) {
                        $roomBooking = Booking::where('date_time', $dateTime->format('Y-m-d H:00:00'))
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
            $bookings = Booking::where('date_time', $dateTime->format('Y-m-d H:00:00'))
                    ->where('status', 2)
                    ->get();
            if ($bookings->isEmpty()) {
                // $hospitals = Hospital::where('type',1)->get()->toArray();
                $hospitals = Hospital::where('type',1)
                            ->whereHas('rooms', function ($query) {
                                $query->where('condition', 1);
                            })
                            ->get()->toArray();
            } else {
                foreach($bookings as $booking) {
                    $hospitals[] = $booking->hospital;
                }
            }


            //!!!! what for
            if(count($hospitals) > 0) {
                $arrHospitals = collect($hospitals)->toArray();
                $hospitals = [];
                foreach($arrHospitals as $hospital) {
                    unset($hospital['rooms']);
                    $hospitals[]= $hospital;
                }
            }

            //!!!!
        }


        $mainHospital = null;
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
            $mainHospital = $hosp;
        } else {
            $hospitals = array_values($hospitals);
            $mainHospital = $hospitals[0];
        }

        $storeData = [
            'hospital_id' => $mainHospital['id'],
            'disease_id' => $data['disease_id'],
            'condition_id' => $data['condition_id'],
            'user_id' => auth('sanctum')->user()->id, // $data['user_id']
           
        ];
        
        $hospital = Hospital::find($mainHospital['id']);
        $rooms = $hospital->rooms;

        if (count($rooms)>1) {
            $room_id = null;
            foreach($rooms as $room) {
                if($room->condition == 1) {
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
            }
            $storeData['room_id'] = $room_id;
        }
        else {
            $storeData['room_id'] = $rooms[0]->id;
        }
        
        $finded = false;
        $bookingHours = 2;
        do {
            $booking = Booking::where('date_time', $dateTime->format('Y-m-d H:00:00'))
                    ->where('hospital_id', $hospital->id)
                    ->where('room_id', $storeData['room_id'])
                    ->first();
            if ($booking == null) {
                $finded = true;
            } else {
                if ($booking->status == 2) {
                    $finded = true;
                } else {
                    $dateTime->add(new DateInterval('PT1H'));
                    $bookingHours = 1;
                }
            }
        } while (!$finded);


        // $now = DateTime::createFromFormat('Y-m-d h:i:s', $dateTime->format('Y-m-d H:00:00'));
        // $nowHour = $now->format('H');
        // if ($nowHour >=0 && $nowHour <8) {
        //     $now = $now->modify('-1 day');
        // }
        // $operator = Operator::where('hospital_id', $hospital->id)
        //         ->where('date', $now->format('Y-m-d 00:00:00'))->first();

        // if($operator != null) {
        //     $storeData['surgeon_id'] = $operator->surgeon_id;
        //     $storeData['cardiologist_id'] = $operator->cardiologist_id;
        // }
        if($data['disease_id'] == 1) {
            $storeData['status'] = 1;
        } else {
            $storeData['status'] = 2;
        }
        $bookings = [];

        try {
            DB::beginTransaction();
            
            for ($i = 0; $i < $bookingHours; $i++) {
                $storeData['date_time'] = $dateTime->format('Y-m-d H:00:00');
                $booking = Booking::where('date_time', $dateTime->format('Y-m-d H:00:00'))
                    ->where('hospital_id', $hospital->id)
                    ->where('room_id', $storeData['room_id'])
                    ->first();

                if ($booking != null) {
                    $booking->delete();
                }
                $booking = Booking::create($storeData);
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
            'user_id' => $data['user_id'],// auth('sanctum')->user()->id
        ];

        $now = new DateTime();
        $nowHour = $now->format('H');
        if ($nowHour >=0 && $nowHour <8) {
            $now = $now->modify('-1 day');
        }
        $operator = Operator::where('hospital_id', $data['hospital_id'])
                ->where('date', $now->format('Y-m-d 00:00:00'))->first();

        if($operator != null) {
            $storeData['surgeon_id'] = $operator->surgeon_id;
            $storeData['cardiologist_id'] = $operator->cardiologist_id;
        }

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

    public function getMess($bookings) {
        $operators = [];
        foreach ($bookings as $booking) {
            $now = DateTime::createFromFormat('Y-m-d h:i:s', $booking->date_time);
            $nowHour = $now->format('H');
            if ($nowHour >=0 && $nowHour <8) {
                $now = $now->modify('-1 day');
            }

            $operator = Operator::where('hospital_id', $booking->hospital_id)
                                ->where('date',$now->format('Y-m-d 00:00:00'))->first();
            $operators[]=$operator;
        }

        if (count($bookings)>1) {

        } else {
            $data = [
                'fio' => $operator->surgeon->last_name,
                'phone' => $operator->surgeon->last_name,
                'email' => $operator->surgeon->last_name,
                'hospital' => $operator->surgeon->last_name,
                'disease' =>$operator->surgeon->last_name,
                'condition' => $operator->surgeon->last_name,
            ];
        }
    }
}
