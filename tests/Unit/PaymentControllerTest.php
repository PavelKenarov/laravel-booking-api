<?php

namespace Tests\Unit;

use App\Http\Controllers\Api\PaymentController;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PaymentControllerTest  extends TestCase
{
    private $room;
    private $customer;
    private $booking;

    public function setUp(): void
    {
        parent::setUp();

        DB::beginTransaction();

        $this->createRoom();
        $this->createCustomer();
        $this->createBooking();
    }

    private function createBooking(): void
    {
        $this->booking = Booking::create([
            'room_id' => $this->room->id,
            'customer_id' => $this->customer->id,
            'price' => 255.55,
            'start_date' => '2024-02-12 12:00:00',
            'end_date' => '2024-02-14  12:00:00',
        ]);
    }

    private function createRoom(): void
    {
        $this->room = Room::create([
            'number' => 1,
            'name' => 'Room 1',
            'price' => 333.22,
            'type' => Room::TYPE_STUDIO
        ]);
    }

    private function createCustomer(): void
    {
        $this->customer = Customer::create([
            'name' => 'Test Customer',
            'email' => 'test@customer.com',
            'phone' => '0878123456',
        ]);
    }

    private function createPayment()
    {
        $data = [
            'booking_id' => $this->booking->id,
            'payment_date' => '2024-02-13 13:23:22',
            'amount' => 321.22,
            'status' => Payment::STATUS_PENDING,
        ];

        return Payment::create($data);
    }

    public function testStorePayment()
    {
        $data = [
            'booking_id' => $this->booking->id,
            'payment_date' => '2024-02-13 13:23:22',
            'amount' => 455.66,
            'status' => Payment::STATUS_SUCCEED,
        ];

        $controller = new PaymentController();
        $method = new \ReflectionMethod($controller, 'store');
        $method->setAccessible(true);

        $request = new Request();
        $request->setMethod('POST');
        $request->replace($data);

        $response = $method->invoke($controller, $request);
        $get = json_decode($response->getContent(), true);

        $this->assertTrue($response->getStatusCode() === 201);
        $this->assertTrue($get['success'] === true);

        $check = Payment::where('booking_id', $data['booking_id'])->first();
        $this->assertNotNull($check);
    }

    public function testUpdatePayment()
    {
        $payment = $this->createPayment();
        $this->assertNotNull($payment);

        $data = [
            'booking_id' => $this->booking->id,
            'payment_date' => '2024-02-18 15:43:44',
            'amount' => 456.78,
            'status' => Payment::STATUS_SUCCEED,
        ];

        $controller = new PaymentController();
        $method = new \ReflectionMethod($controller, 'update');
        $method->setAccessible(true);

        $request = new Request();
        $request->setMethod('PUT');
        $request->replace($data);

        $response = $method->invoke($controller, $request, $payment);
        $get = json_decode($response->getContent(), true);

        $this->assertTrue($response->getStatusCode() === 201);
        $this->assertTrue($get['success'] === true);

        $check = Payment::where('booking_id', $data['booking_id'])->first();
        $this->assertNotNull($check);
        $this->assertEquals($data['amount'], $check->amount);
    }

    public function testDeletePayment()
    {
        $payment = $this->createPayment();
        $this->assertNotNull($payment);

        $controller = new PaymentController();
        $method = new \ReflectionMethod($controller, 'remove');
        $method->setAccessible(true);

        $response = $method->invoke($controller, $payment);
        $get = json_decode($response->getContent(), true);

        $this->assertTrue($response->getStatusCode() === 201);
        $this->assertTrue($get['success'] === true);
    }

    public function tearDown(): void
    {
        DB::rollBack();
        parent::tearDown();
    }
}
