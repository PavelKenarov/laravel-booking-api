<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\Booking;
use App\Models\Payment;


class PaymentController extends Controller
{
    public function show(Payment $payment)
    {
        return response()->json(['success' => true, 'payment' => $payment], 201);
    }

    public function store(Request $request)
    {
        $validated = $this->validatePayment($request);
        if(!empty($validated->headers))
            return $validated;

        $payment = $this->createPayment($validated);
        if(empty($payment->id))
            return $payment;

        return response()->json([
            'success' => true,
            'message' => 'Payment created successfully',
            'data' => $payment,
        ], 201);
    }

    public function update(Request $request, Payment $payment): mixed
    {
        $validated = $this->validatePayment($request);
        if(!empty($validated->headers))
            return $validated;

        $payment = $this->updatePayment($validated, $payment);
        if(empty($payment->id))
            return $payment;

        return response()->json([
            'success' => true,
            'message' => 'Payment updated successfully',
            'data' => $payment,
        ], 201);
    }

    public function remove(Payment $payment)
    {
        $payment->delete();
        return response()->json(['success' => true, 'message' => 'Payment deleted successfully'], 201);
    }

    private function validatePayment(Request $request): mixed
    {

        try {
            $validatedData = $request->validate([
                'booking_id' => 'required|exists:booking,id',
                'payment_date' => 'required|date',
                'amount' => 'required|numeric|min:0',
                'status' => 'required|in:' . Payment::STATUS_SUCCEED . ',' . Payment::STATUS_PENDING . ',' . Payment::STATUS_FAILED,
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->validator->errors()->getMessages(),
            ], 422);
        }

        return $validatedData;
    }

    private function createPayment($validatedData): mixed
    {
        try {
            $payment = Payment::create($validatedData);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->validator->errors()->getMessages(),
            ], 422);
        }

        return $payment;
    }

    private function updatePayment($validatedData, Payment $payment): mixed
    {
        try {
            $payment->update($validatedData);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->validator->errors()->getMessages(),
            ], 422);
        }

        return $payment;
    }
}
