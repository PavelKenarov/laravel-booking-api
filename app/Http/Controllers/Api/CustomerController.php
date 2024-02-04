<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CustomerController extends Controller
{
    public function store(Request $request): mixed
    {
        $validated = $this->validateCustomer($request);
        if(!empty($validated->headers))
            return $validated;

        $customer = $this->createCustomer($validated);
        if(empty($customer->id))
            return $customer;

        return response()->json([
            'success' => true,
            'message' => 'Customer created successfully',
            'data' => $customer,
        ], 201);
    }

    public function update(Request $request, Customer $customer): mixed
    {
        $validated = $this->validateCustomer($request);
        if(!empty($validated->headers))
            return $validated;

        $customer = $this->updateCustomer($validated, $customer);
        if(empty($customer->id))
            return $customer;

        return response()->json([
            'success' => true,
            'message' => 'Customer updated successfully',
            'data' => $customer,
        ], 201);
    }

    public function index(Customer $customer)
    {
        $get = Customer::with('bookings')->find($customer->id);
        $bookings = [];
        if(!empty($get->bookings)){
            foreach ($get->bookings as $booking)
                $bookings[] = $booking;
        }

        return response()->json(['success' => true, 'customer' => $customer, 'bookings' => $bookings], 201);
    }

    public function remove(Customer $customer)
    {
        $customer->delete();
        return response()->json(['success' => true, 'message' => 'Customer deleted successfully'], 201);
    }

    private function validateCustomer(Request $request): mixed
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|min:2|max:255',
                'email' => 'required|email|unique:customer|max:255',
                'phone' => 'required|regex:/^[0-9]{10}$/',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->validator->errors()->getMessages(),
            ], 422);
        }

        return $validatedData;
    }

    private function createCustomer($validatedData): mixed
    {
        try {
            $customer = Customer::create($validatedData);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->validator->errors()->getMessages(),
            ], 422);
        }

        return $customer;
    }

    private function updateCustomer($validatedData, Customer $customer): mixed
    {
        try {
            $customer->update($validatedData);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->validator->errors()->getMessages(),
            ], 422);
        }

        return $customer;
    }
}
