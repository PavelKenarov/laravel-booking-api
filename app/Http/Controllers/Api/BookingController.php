<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class BookingController extends Controller
{
    public function show(Booking $booking)
    {
        $get = Booking::with('payments')->find($booking->id);
        $payments = [];
        if(!empty($get->payments)){
            foreach ($get->payments as $payment)
                $payments[] = $payment;
        }

        return response()->json(['success' => true, 'booking' => $booking, 'payments' => $payments], 201);
    }

    public function store(Request $request)
    {
        $validated = $this->validateBooking($request);
        if(!empty($validated->headers))
            return $validated;

        $booking = $this->createBooking($validated);
        if(empty($booking->id))
            return $booking;

        return response()->json([
            'success' => true,
            'message' => 'Booking created successfully',
            'data' => $booking,
        ], 201);
    }

    public function update(Request $request, Booking $booking): mixed
    {
        $validated = $this->validateBooking($request);
        if(!empty($validated->headers))
            return $validated;

        $booking = $this->updateBooking($validated, $booking);
        if(empty($booking->id))
            return $booking;

        return response()->json([
            'success' => true,
            'message' => 'Customer updated successfully',
            'data' => $booking,
        ], 201);
    }

    public function remove(Booking $booking)
    {
        $booking->delete();
        return response()->json(['success' => true, 'message' => 'Booking deleted successfully'], 201);
    }

    private function validateBooking(Request $request): mixed
    {
        $checkRoomAndCustomer = $this->checkRoomAndCustomer($request);
        if($checkRoomAndCustomer !== true)
            return $checkRoomAndCustomer;

        try {
            $validatedData = $request->validate([
                'start_date' => 'required|date|before_or_equal:end_date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'price' => 'required|numeric|min:0',
                'room_id' => 'required|exists:room,id',
                'customer_id' => 'required|exists:customer,id',
                'unique' => ['customer_id', 'room_id']
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->validator->errors()->getMessages(),
            ], 422);
        }

        return $validatedData;
    }

    private function checkRoomAndCustomer(Request $request): mixed
    {
        $booking = Booking::where('customer_id', $request->input('customer_id'))->where('room_id', $request->input('room_id'))->first();

        if ($booking) {
            return response()->json([
                'success' => false,
                'message' => 'This customer and room combination already exists!',
            ], 422);
        }

        return true;
    }

    private function createBooking($validatedData): mixed
    {
        try {
            $booking = Booking::create($validatedData);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->validator->errors()->getMessages(),
            ], 422);
        }

        return $booking;
    }

    private function updateBooking($validatedData, Booking $booking): mixed
    {
        try {
            $booking->update($validatedData);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->validator->errors()->getMessages(),
            ], 422);
        }

        return $booking;
    }
}