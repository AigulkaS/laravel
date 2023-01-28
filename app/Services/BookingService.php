<?php

namespace App\Services;

use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Models\Hospital;
use App\Models\Today;
use DateInterval;
use DateTime;
use Illuminate\Support\Facades\DB;

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
                    'id' => $room->id,
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
                'id' => $room->id,
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

    public function getNearestHospital($data) {
        

        $geo_lat = floatval($data['geo_lat']);
        
        $geo_lon = floatval($data['geo_lon']);
        
        $dateTime = new DateTime();
        $dateTime = $dateTime->format('Y-m-d H:00:00');

        $hospitals = Hospital::all()->toArray();

        $arrBookedHospitals = [];
        $bookings = Booking::where('date_time', $dateTime)
                    ->where(function ($query) {
                        $query->where('status', 1)
                              ->orWhere('status', 2);
                    })
                    ->get();
        // dump( $dateTime);     
        // dump('booked');     
        // dump( $bookings) ;     
        foreach($bookings as $booking) {
            
            $hospital = $booking->hospital;
            
            
            $otherRoom = false;
            // dump('count($hospital->rooms');
            // dump(count($hospital->rooms));
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
        // dump('arrBookedHospitals');
        // dump($arrBookedHospitals);
        
        foreach($arrBookedHospitals as $bookedHospital) {
            $key = array_search($bookedHospital, $hospitals);
            unset($hospitals[$key]);
        }
        // dump('hospitals');
        // dd($hospitals);

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
            'dispatcher_id' => $data['dispatcher_id'],
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
        
        $today = Today::where('hospital_id', $hospital->id)->first();

        $storeData['surgeon_id'] = $today->surgeon_id;
        $storeData['cardiologist_id'] = $today->cardiologist_id;
        if($data['disease_id'] == 1) {
            $storeData['status'] = 1;
        } else {
            $storeData['status'] = 2;
        }
        $bookings = [];

        try {
            DB::beginTransaction();
            for ($i = 0; $i < $data['booking_hours']; $i++) {
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
            'disease_id' => $data['status'] == 0 ? 2 : $data['status'],
            'status' => $data['status'],
            'room_id' => $data['room_id'],
        ];
        $today = Today::where('hospital_id', $data['hospital_id'])->first();

        $storeData['surgeon_id'] = $today->surgeon_id;
        $storeData['cardiologist_id'] = $today->cardiologist_id;
        $storeData['dispatcher_id'] = $storeData['surgeon_id'];

        $dateTime = new DateTime($data['date_time']);
        $bookings = [];

        try {
            DB::beginTransaction();
            for ($i = 0; $i < $data['booking_hours']; $i++) {
                $storeData['date_time'] = $dateTime->format('Y-m-d H:00:00');
                dump(' $storeData[date_time]');
                dump( $storeData['date_time']);

                $booking = Booking::where('date_time', $storeData['date_time'])
                    ->where('hospital_id',$storeData['hospital_id'])
                    ->where('room_id', $storeData['room_id'])
                    ->first();
                dump(' $booking');
                dump( $booking);
                if ($booking == null) {
                    $booking = Booking::create($storeData);
                } else {
                    dump(' $storeData');
                    dump( $storeData);
                    $booking->update($storeData);
                    dump( 'updated');
                    $booking->fresh();
                }
            
                $bookings[]=$booking;
                $dateTime->add(new DateInterval('PT1H'));
            }
            dump('$bookings');
            dd($bookings);
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