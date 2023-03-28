<?php

namespace App\Services;

use App\Models\Hospital;
use App\Models\HospitalRoom;
use Illuminate\Support\Facades\DB;

class HospitalService {

    public function store($data) {
        try {
            DB::beginTransaction();
            
            if($data['type'] == 1) {
                $rooms = $data['hospital_rooms'];
                unset($data['hospital_rooms']);
                $newRooms = $this->getNewRooms($rooms);
                $hospital = Hospital::create($data);
                $hospital->rooms()->saveMany($newRooms);
            } else {
                $hospital = Hospital::create($data);
            }
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

            if($data['type'] == 1) {
                $rooms = $data['hospital_rooms'];
                unset($data['hospital_rooms']);
                $updatedRooms = $this->getUpdatedRooms($rooms);
                $hospital->update($data);
                $hospital->fresh();
                $hospital->rooms()->saveMany($updatedRooms);
            } else {
                $hospital->update($data);
                $hospital->fresh();
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
            $hospital->users()->delete();
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
