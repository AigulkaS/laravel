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
                'start' => $room->start,
                'end' => $room->end,
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


        $now = DateTime::createFromFormat('Y-m-d H:i:s', $dateTime);
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
            $dateTime = DateTime::createFromFormat('Y-m-d H:i:s', $dateTime);

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
        $geo_lat = floatval($data['geo_lat']);
        $geo_lon = floatval($data['geo_lon']);
        $nowHour = $dateTime->format('H');

        $bookings = Booking::where('date_time', $dateTime->format('Y-m-d H:00:00'))->get();

        $hospitalsData=[];
        $hospitals = Hospital::where('type',1)
                            ->whereHas('rooms', function ($query) {
                                $query->where('condition', 1);
                            })->get();
        foreach($hospitals as $hospital) {
            foreach($hospital->rooms as $room) {
                if ($room->start == null ||
                    ($nowHour>= substr($room->start, 0, 2) && $nowHour < substr($room->end, 0, 2)
                        && substr($room->end, 0, 2) - $nowHour > 1)) {

                    $finded = false;

                    foreach($bookings as $booking) {
                        if ($booking->hospital_id == $hospital->id && $booking->room_id == $room->id) {
                            $finded = true;
                            break;
                        }
                    }


                    if (!$finded) {

                        $hospitalsData[] = [
                            'hospital_id' => $hospital->id,
                            'room_id' => $room->id,
                            'geo_lat' => $hospital->geo_lat,
                            'geo_lon' => $hospital->geo_lon,
                        ];
                    }

                }
            }
        }

        if (count($hospitalsData)==0) {
            $bookings = Booking::where('date_time', $dateTime->format('Y-m-d H:00:00'))
                    ->where('status', 2)
                    ->get();
            if ($bookings->isEmpty()) {
                $hospitals = Hospital::where('type',1)
                            ->whereHas('rooms', function ($query) {
                                $query->where('condition', 1);
                            })->get();
                foreach($hospitals as $hospital) {
                    foreach($hospital->rooms as $room) {
                        if ($room->start == null ||
                            ($nowHour>= substr($room->start, 0, 2) && $nowHour < substr($room->end, 0, 2)
                             && substr($room->end, 0, 2) - $nowHour > 1)) {
                            $addHospital = true;
                            if ($room->end != null) {
                                $roomDate = $dateTime->format('Y-m-d').' '.$room->end.':00';
                                $bookingLast = Booking::where('date_time', $roomDate)->first();
                                $addHospital = $bookingLast == null ? true : false;
                            }
                            if ($addHospital) {
                                $hospitalsData[] = [
                                    'hospital_id' => $hospital->id,
                                    'room_id' => $room->id,
                                    'geo_lat' => $hospital->geo_lat,
                                    'geo_lon' => $hospital->geo_lon,
                                ];
                            }

                        }
                    }
                }
            } else {
                foreach($bookings as $booking) {
                    $room = $booking->room;
                    if ($room->start == null ||
                            ($nowHour>= substr($room->start, 0, 2) && $nowHour < substr($room->end, 0, 2)
                             && substr($room->end, 0, 2) - $nowHour > 1)) {
                        $addHospital = true;
                        if ($room->end != null) {
                            $roomDate = $dateTime->format('Y-m-d').' '.$room->end.':00';
                            $bookingLast = Booking::where('date_time', $roomDate)->first();
                            $addHospital = $bookingLast == null ? true : false;
                        }
                        if ($addHospital) {
                            $hospitalsData[] = [
                                'hospital_id' => $booking->hospital_id,
                                'room_id' => $room->id,
                                'geo_lat' => $booking->hospital->geo_lat,
                                'geo_lon' => $booking->hospital->geo_lon,
                            ];
                        }
                    }
                }
            }
        }


        $mainHospital = null;
        if(count($hospitalsData) > 1) {
            $min = null;
            foreach($hospitalsData as $hospitalData) {
                $dist = $this->getDistanceBetweenPoints($geo_lat, $geo_lon, floatval($hospitalData['geo_lat']), floatval($hospitalData['geo_lon']));
                if ($min == null || $dist < $min) {
                    $min = $dist;
                    $mainHospital = $hospitalData;
                }
            }
        } else {
            $mainHospital = $hospitalsData[0];
        }

        $storeData = [
            'hospital_id' => $mainHospital['hospital_id'],
            'room_id' => $mainHospital['room_id'],
            'status' => $data['disease_id'] == 1 ? 1 : 2,
            'disease_id' => $data['disease_id'],
            'condition_id' => $data['condition_id'],
            'user_id' => auth('sanctum')->user()->id,
            // 'user_id' => 1,

        ];

        $done = false;
        $finded = false;
        $bookingHours = 0;
        $bookings = [];
        try {
            DB::beginTransaction();
            $updateBookings =[];
            do {
                loop:
                $storeData['date_time'] = $dateTime->format('Y-m-d H:00:00');

                $booking = Booking::where('date_time', $dateTime->format('Y-m-d H:00:00'))
                    ->where('hospital_id', $mainHospital['hospital_id'])
                    ->where('room_id', $mainHospital['room_id'])
                    ->first();

                if ($booking != null) {
                    if ($booking->status == 1) {
                        $dateTime->add(new DateInterval('PT1H'));
                        $done = true;
                        goto loop;
                    } else {
                        if ($storeData['disease_id'] == 1) {

                            $updateBookings[] = $booking;
                        } else {

                            $dateTime->add(new DateInterval('PT1H'));
                            $done = true;
                            goto loop;
                        }

                    }
                } else {

                    $finded = true;
                }

                if ($bookingHours<2) {
                    $booking = Booking::create($storeData);
                    $bookingHours++;
                    $bookings[]=$booking;
                }

                if ($finded && ($bookingHours == 2 || ($done && $bookingHours == 1))) {
                    break;
                } else {
                    $dateTime->add(new DateInterval('PT1H'));
                }
            } while(0);

            foreach($updateBookings as $updateBooking) {
                $room = $updateBooking->room;
                $hour = $dateTime->format('H');
                if ($room->start == null ||
                    ($hour>= substr($room->start, 0, 2) && $hour < substr($room->end, 0, 2))) {
                    $updateData = [
                        "date_time" => $dateTime->format('Y-m-d H:00:00')
                    ];
                    $updateBooking->update($updateData);
                    $updateBooking->fresh();
                    $bookings[]=$updateBooking;
                } else {
                    $updateBooking->delete();
                }
                $dateTime->add(new DateInterval('PT1H'));
            }
            DB::commit();
        } catch(\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }

        return [
            'hours' => $bookingHours,
            'bookings' => collect($bookings)
        ];
    }

    public function storeNew3333($data) {
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
                    if($room->condition == 1 && ($room->start == null ||
                    ($dateTime->format('H')>= substr($room->start, 0, 2) &&
                        $dateTime->format('H') < substr($room->end, 0, 2)))) {
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

            if(count($hospitals) > 0) {
                $arrHospitals = collect($hospitals)->toArray();
                $hospitals = [];
                foreach($arrHospitals as $hospital) {
                    unset($hospital['rooms']);
                    $hospitals[]= $hospital;
                }
            }
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
            'user_id' => auth('sanctum')->user()->id, // auth('sanctum')->user()->id $data['user_id']

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
                        // if($booking->status == 0) {
                        //     $room_id = $room->id;
                        //     break;
                        // } else
                        if($booking->status == 1){
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

        if($data['disease_id'] == 1) {
            $storeData['status'] = 1;
        } else {
            $storeData['status'] = 2;
        }
        $bookings = [];

        try {
            DB::beginTransaction();
            $updateBookings =[];
            for ($i = 0; $i < $bookingHours; $i++) {
                $storeData['date_time'] = $dateTime->format('Y-m-d H:00:00');
                $booking = Booking::where('date_time', $dateTime->format('Y-m-d H:00:00'))
                    ->where('hospital_id', $hospital->id)
                    ->where('room_id', $storeData['room_id'])
                    ->first();

                if ($booking != null) {
                    $updateBookings[] = $booking;
                    // $booking->delete();
                }
                $booking = Booking::create($storeData);
                $bookings[]=$booking;
                $dateTime->add(new DateInterval('PT1H'));
            }

            $dateTimeNew = new DateTime($dateTime->format('Y-m-d H:00:00'));
            $bookedNull = false;
            do {
                $booking = Booking::where('date_time', $dateTimeNew->format('Y-m-d H:00:00'))
                    ->where('hospital_id', $hospital->id)
                    ->where('room_id', $storeData['room_id'])
                    ->first();

                if ($booking != null) {
                    $updateBookings[] = $booking;
                    $dateTimeNew->add(new DateInterval('PT1H'));
                } else {
                    $bookedNull = true;
                }
            } while(!$bookedNull);

            foreach($updateBookings as $updateBooking) {
                $updateData = [
                    "date_time" => $dateTime->format('Y-m-d H:00:00')
                ];
                $updateBooking->update($updateData);
                $updateBooking->fresh();
                $bookings[]=$updateBooking;
                $dateTime->add(new DateInterval('PT1H'));
            }
            DB::commit();
        } catch(\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
        return [
            'hours' => $bookingHours,
            'bookings' => collect($bookings)
        ];
    }

    public function update($data) {
        $bookings = [];
        $dateTimes = $data['date_times'];
        if ($data['status'] == 0) {
            try {
                DB::beginTransaction();
                foreach ($dateTimes as $dateTime) {
                    $booking = Booking::where('date_time', $dateTime)
                    ->where('hospital_id', $data['hospital_id'])
                    ->where('room_id', $data['room_id'])
                    ->first();

                    if ($booking != null) {
                        $booking->status = 0;
                        $bookings[]=$booking;
                        $booking->delete();
                    }
                }
                DB::commit();
            } catch(\Exception $e) {
                DB::rollBack();
                return $e->getMessage();
            }
            return collect($bookings);
        } else {
            $storeData = [
                'hospital_id' => $data['hospital_id'],
                'room_id' => $data['room_id'],
                // 'user_id' => 1,
                'user_id' => auth('sanctum')->user()->id,
                'status' => $data['status'],
            ];

            try {
                DB::beginTransaction();
                foreach ($dateTimes as $dateTime) {
                    $booking = Booking::where('date_time', $dateTime)
                    ->where('hospital_id',$storeData['hospital_id'])
                    ->where('room_id', $storeData['room_id'])
                    ->first();

                    if ($booking != null) {
                        $booking->delete();

                    }
                    $storeData['date_time'] = $dateTime;

                    $booking = Booking::create($storeData);

                    $bookings[]=$booking;

                }

                DB::commit();
            } catch(\Exception $e) {
                DB::rollBack();
                return $e->getMessage();
            }
            return collect($bookings);
        }
    }

    public function getMess($hours, $bookings) {
        $data =[];
        $fios = [];
        $phones = [];
        $emails = [];
        if ($hours == 2) {
            $forTwo = false;
            $first = DateTime::createFromFormat('Y-m-d H:i:s', $bookings[0]->date_time);
            $second = DateTime::createFromFormat('Y-m-d H:i:s', $bookings[1]->date_time);
            $timeStr = $first->format('d.m.y H:i - ');
            $second->add(new DateInterval('PT1H'));

            $timeStr .= $second->format('H:i');
            if ($second->format('H') == 8) {
                $forTwo = true;
            }

            $hour = $first->format('H');
            if ($hour >=0 && $hour <8) {
                $first = $first->modify('-1 day');
            }

//            $fios = [];
//            $phones = [];
//            $emails = [];
            $operator = Operator::where('hospital_id', $bookings[0]->hospital_id)
                                ->where('date',$first->format('Y-m-d 00:00:00'))->first();
            if ($operator != null) {
                $fios = [
                    $operator->surgeon->last_name . ' ' . $operator->surgeon->first_name . ' ' . $operator->surgeon->patronymic,
                    $operator->cardiologist->last_name . ' ' . $operator->cardiologist->first_name . ' ' . $operator->cardiologist->patronymic
                ];
                $phones = [
                    $operator->surgeon->phone,
                    $operator->cardiologist->phone,
                ];
                $emails = [
                    $operator->surgeon->email,
                    $operator->cardiologist->email,
                ];
            }

            if ($forTwo) {
                $operator = Operator::where('hospital_id', $bookings[0]->hospital_id)
                                ->where('date',$second->format('Y-m-d 00:00:00'))->first();
                if ($operator != null) {
                    $fios[] = $operator->surgeon->last_name . ' ' . $operator->surgeon->first_name . ' ' . $operator->surgeon->patronymic;
                    $fios[] = $operator->cardiologist->last_name . ' ' . $operator->cardiologist->first_name . ' ' . $operator->cardiologist->patronymic;
                    $phones[] = $operator->surgeon->phone;
                    $phones[] = $operator->cardiologist->phone;
                    $emails[] = $operator->surgeon->email;
                    $emails[] = $operator->cardiologist->email;
                }
            }

            $data = [
                'fio' => $fios,
                'phone' => $phones,
                'email' => $emails,
                'hospital' => $bookings[0]->hospital->short_name,
                'address' => $bookings[0]->hospital->address,
                'disease' =>$bookings[0]->disease->name,
                'condition' => $bookings[0]->condition->name,
                'time' => $timeStr,
            ];
        } else {
            $first = DateTime::createFromFormat('Y-m-d H:i:s', $bookings[0]->date_time);
            $timeStr = $first->format('d.m.y H:i - ');
            $first->add(new DateInterval('PT1H'));
            $timeStr .= $first->format('H:i');
            $first = $first->modify('-1 hour');

            $hour = $first->format('H');
            if ($hour >=0 && $hour <8) {
                $first = $first->modify('-1 day');
            }
            $operator = Operator::where('hospital_id', $bookings[0]->hospital_id)
                                ->where('date',$first->format('Y-m-d 00:00:00'))->first();
            if ($operator != null) {
                $fios = [
                    $operator->surgeon->last_name . ' ' . $operator->surgeon->first_name . ' ' . $operator->surgeon->patronymic,
                    $operator->cardiologist->last_name . ' ' . $operator->cardiologist->first_name . ' ' . $operator->cardiologist->patronymic
                ];
                $phones = [
                    $operator->surgeon->phone,
                    $operator->cardiologist->phone,
                ];
                $emails = [
                    $operator->surgeon->email,
                    $operator->cardiologist->email,
                ];
            }
            $data = [
                'fio' => $fios,
                'phone' => $phones,
                'email' => $emails,
                'hospital' => $bookings[0]->hospital->short_name,
                'address' => $bookings[0]->hospital->address,
                'disease' =>$bookings[0]->disease->name,
                'condition' => $bookings[0]->condition->name,
                'time' => $timeStr,
            ];
        }
        return $data;
    }
}
