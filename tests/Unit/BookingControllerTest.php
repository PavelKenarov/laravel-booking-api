<?php

namespace Tests\Unit;

use App\Http\Controllers\Api\BookingController;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class BookingControllerTest extends TestCase
{
    private $room;
    private $customer;

    public function setUp(): void
    {
        parent::setUp();

        DB::beginTransaction();

        $this->createRoom();
        $this->createCustomer();
    }

    private function createBooking()
    {
        $data = [
            'room_id' => $this->room->id,
            'customer_id' => $this->customer->id,
            'price' => 255.55,
            'start_date' => '2024-02-12 12:00:00',
            'end_date' => '2024-02-14  12:00:00',
        ];

        return Booking::create($data);
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

    /**
     * @priority 1
     */
    public function testStoreBooking()
    {
        $data = [
            'room_id' => $this->room->id,
            'customer_id' => $this->customer->id,
            'price' => 255.55,
            'start_date' => '2024-02-12 12:00:00',
            'end_date' => '2024-02-14  12:00:00',
        ];

        $controller = new BookingController();
        $method = new \ReflectionMethod($controller, 'store');
        $method->setAccessible(true);

        $request = new Request();
        $request->setMethod('POST');
        $request->replace($data);

        $response = $method->invoke($controller, $request);
        $get = json_decode($response->getContent(), true);

        $this->assertTrue($response->getStatusCode() === 201);
        $this->assertTrue($get['success'] === true);

        $check = Booking::where([['room_id', $this->room->id], ['customer_id', $this->customer->id]])->first();
        $this->assertNotNull($check);
        $this->assertEquals($data['price'], $check->price);
    }

    /**
     * @priority 2
     */
    public function testUpdateBooking()
    {
        $booking = $this->createBooking();
        $this->assertNotNull($booking);

        $data = [
            'room_id' => $this->room->id,
            'customer_id' => $this->customer->id,
            'price' => 355.55,
            'start_date' => '2024-02-11 12:00:00',
            'end_date' => '2024-02-13  12:00:00',
        ];

        $controller = new BookingController();
        $method = new \ReflectionMethod($controller, 'update');
        $method->setAccessible(true);

        $request = new Request();
        $request->setMethod('PUT');
        $request->replace($data);

        $response = $method->invoke($controller, $request, $booking);
        $get = json_decode($response->getContent(), true);

        $this->assertTrue($response->getStatusCode() === 201);
        $this->assertTrue($get['success'] === true);
    }

    /**
     * @priority 3
     */
    public function testDeleteBooking()
    {
        $booking = $this->createBooking();
        $this->assertNotNull($booking);

        $controller = new BookingController();
        $method = new \ReflectionMethod($controller, 'remove');
        $method->setAccessible(true);

        $response = $method->invoke($controller, $booking);
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
