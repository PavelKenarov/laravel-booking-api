<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class RoomController extends Controller
{
    public function update(Request $request, Room $room): mixed
    {
        $validated = $this->validateRoom($request);
        if(!empty($validated->headers))
            return $validated;

        $room = $this->updateRoom($validated, $room);
        if(empty($room->id))
            return $room;

        return response()->json([
            'success' => true,
            'message' => 'Room updated successfully',
            'data' => $room,
        ], 201);
    }

    public function store(Request $request): mixed
    {
        $validated = $this->validateRoom($request);
        if(!empty($validated->headers))
            return $validated;

        $room = $this->createRoom($validated);
        if(empty($room->id))
            return $room;

        return response()->json([
            'success' => true,
            'message' => 'Room created successfully',
            'data' => $room,
        ], 201);

/*
        try {
            $validatedData = $request->validate([
                'number' => 'required|integer',
                'name' => 'required|string',
                'price' => 'required|numeric',
                'type' => 'required|in:' . Room::TYPE_ONE . ',' . Room::TYPE_STUDIO . ',' . Room::TYPE_TWO,
            ]);
        } catch (ValidationException $e){
            return response()->json([
                'success' => false,
                'errors' => $e->validator->errors()->getMessages(),
            ], 422);
        }

        try {
            $room = Room::create($validatedData);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->validator->errors()->getMessages(),
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'Room created successfully',
            'data' => $room,
        ], 201);*/
    }

    public function show(Room $room)
    {
        $get = Room::with('bookings')->find($room->id);
        $bookings = [];
        if(!empty($get->bookings)){
            foreach ($get->bookings as $booking)
                $bookings[] = $booking;
        }

        return response()->json(['success' => true, 'room' => $room, 'bookings' => $bookings], 201);
    }

    public function remove(Room $room)
    {
        $room->delete();
        return response()->json(['success' => true, 'message' => 'Room deleted successfully'], 201);
    }

    private function validateRoom(Request $request): mixed
    {
        try {
            $validatedData = $request->validate([
                'number' => 'required|integer',
                'name' => 'required|string',
                'price' => 'required|numeric',
                'type' => 'required|in:' . Room::TYPE_ONE . ',' . Room::TYPE_STUDIO . ',' . Room::TYPE_TWO,
            ]);
        } catch (ValidationException $e){
            return response()->json([
                'success' => false,
                'errors' => $e->validator->errors()->getMessages(),
            ], 422);
        }

        return $validatedData;
    }

    private function createRoom($validatedData): mixed
    {
        try {
            $room = Room::create($validatedData);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->validator->errors()->getMessages(),
            ], 422);
        }

        return $room;
    }

    private function updateRoom($validatedData, Room $room): mixed
    {
        try {
            $room->update($validatedData);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->validator->errors()->getMessages(),
            ], 422);
        }

        return $room;
    }

}
