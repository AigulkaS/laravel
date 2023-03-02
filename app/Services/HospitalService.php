<?php

namespace App\Services;

use App\Http\Resources\HospitalResource;
use App\Models\Hospital;
use App\Models\HospitalRoom;
use App\Models\Today;
use Illuminate\Support\Facades\DB;

class HospitalService {

    public function store($data) {
        try {
            DB::beginTransaction();
            $rooms = $data['hospital_rooms'];
            unset($data['hospital_rooms']);

            if($rooms == 'null') {
                unset($data['geo_lat']);
                unset($data['geo_lon']);
            }

            $hospital = Hospital::create($data);

            if ($rooms != 'null') {
                $newRooms = $this->getNewRooms($rooms);
                $hospital->rooms()->saveMany($newRooms);
            } 
            
            // $todayData = [
            //     'hospital_id' => $hospital->id,
            // ];
            // Today::create($todayData);
            
            DB::commit();

        } catch(\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }

        return $hospital;
    }

    public function update($hospital, $data) {
        try {
            DB::beginTransaction();

            $rooms = $data['hospital_rooms'];
            unset($data['hospital_rooms']);

            if($rooms == 'null') {
                unset($data['geo_lat']);
                unset($data['geo_lon']);
            }

            $hospital->update($data);
            $hospital->fresh();

            if($rooms != 'null') {
                $updatedRooms = $this->getUpdatedRooms($rooms);
                $hospital->rooms()->saveMany($updatedRooms);
            } 

            DB::commit();
        } catch(\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        } 

        return $hospital;
    }

    public function delete($hospital) {
        try {
            DB::beginTransaction();

            $hospital->rooms()->delete();
            // $hospital->today()->delete();
            $hospital->delete();

            DB::commit();
        } catch(\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        } 

        return "delete successfully";
    }

    private function getNewRooms($rooms) {
        $newRooms = [];
        foreach ($rooms as $room) {
            $newRooms[] = HospitalRoom::create($room);
        }
        return $newRooms;
    }

    private function getUpdatedRooms($rooms) {
        $newRooms = [];
        foreach ($rooms as $room) {
            if (!isset($room['id'])) {
                $room = HospitalRoom::create($room);
                $newRooms[] = $room;
            } else {
                $newRoom = HospitalRoom::find($room['id']);
                $newRoom->update($room);
                $newRoom->fresh();
                $newRooms[] = $newRoom;
            }
        }
        return $newRooms;
    }

    public function disable($data) {
        try {
            DB::beginTransaction();
            $room = HospitalRoom::find($data['room_id']);
            unset($data['room_id']);
            $room->update($data);
            $room->fresh();
            DB::commit();
        } catch(\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        } 
        return  $room;
    }
}