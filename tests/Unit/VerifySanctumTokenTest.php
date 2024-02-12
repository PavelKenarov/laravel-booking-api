<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class VerifySanctumTokenTest extends TestCase
{
    private $headers = [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json'
    ];

    private $token;

    public function setUp(): void
    {
        parent::setUp();

        $user = DB::connection('mysql')->table('users')->where('email', UserControllerTest::$userEmail)->first();
        if(empty($user)){
            $this->testCreateMainUser();
        }
        $this->testLoginUser();
    }

    private function testLoginUser()
    {
        $data = [
            'email' => UserControllerTest::$userEmail,
            'password' => UserControllerTest::$userPassword
        ];

        $response = Http::withHeaders($this->headers)->post(route('login'), $data);

        $this->assertTrue($response->getStatusCode() === 200);
        $this->assertTrue($response->json('success') === true);
        $this->assertNotEmpty($response->json('token'));

        $tokenData = explode('|', $response->json('token'));
        $this->token = $tokenData[1];
    }

    private function testCreateMainUser()
    {
        $data = [
            'name' => UserControllerTest::$userName,
            'email' => UserControllerTest::$userEmail,
            'password' => UserControllerTest::$userPassword,
            'password_confirmation' => UserControllerTest::$userPassword
        ];

        $response = Http::withHeaders($this->headers)->post(route('register'), $data);

        $this->assertTrue($response->getStatusCode() === 201);
        $this->assertTrue($response->json('success') === true);
        $this->assertNotEmpty($response->json('token'));

        $tokenData = explode('|', $response->json('token'));
        $this->token = $tokenData[1];
    }

    public function testMiddlewareAccess()
    {
        $response = Http::withHeaders($this->headers)->post(route('store-room'));
        $this->assertTrue($response->getStatusCode() === 401);
        $this->assertTrue($response->json('error') === 'Invalid Token!');

        $response = Http::withHeaders($this->headers)->post(route('store-customer'));
        $this->assertTrue($response->getStatusCode() === 401);
        $this->assertTrue($response->json('error') === 'Invalid Token!');

        $response = Http::withHeaders($this->headers)->post(route('store-booking'));
        $this->assertTrue($response->getStatusCode() === 401);
        $this->assertTrue($response->json('error') === 'Invalid Token!');

        $response = Http::withHeaders($this->headers)->post(route('store-payment'));
        $this->assertTrue($response->getStatusCode() === 401);
        $this->assertTrue($response->json('error') === 'Invalid Token!');
    }

    public function testRoomsMiddlewareTokenAuthentication()
    {
        $this->headers['Authorization'] = 'Bearer ' . $this->token;
        $response = Http::withHeaders($this->headers)->post(route('store-room'));
        $this->assertTrue($response->getStatusCode() === 422);

        $room = DB::connection('mysql')->table('room')->first();
        if(empty($room)){
            $response = Http::withHeaders($this->headers)->get(route('view-room', ['room' => 1]));
            $this->assertTrue($response->getStatusCode() === 404);

            $response = Http::withHeaders($this->headers)->put(route('update-room', ['room' => 1]));
            $this->assertTrue($response->getStatusCode() === 404);

            $response = Http::withHeaders($this->headers)->delete(route('remove-room', ['room' => 1]));
            $this->assertTrue($response->getStatusCode() === 404);
        }else{
            $response = Http::withHeaders($this->headers)->get(route('view-room', ['room' => $room->id]));
            $this->assertTrue($response->getStatusCode() === 201);
            $this->assertTrue($response->json('success') === true);

            $response = Http::withHeaders($this->headers)->put(route('update-room', ['room' => $room->id]));
            $this->assertTrue($response->getStatusCode() === 422);
        }
    }

    public function testCustomersMiddlewareTokenAuthentication()
    {
        $this->headers['Authorization'] = 'Bearer ' . $this->token;
        $response = Http::withHeaders($this->headers)->post(route('store-customer'));
        $this->assertTrue($response->getStatusCode() === 422);

        $customer = DB::connection('mysql')->table('customer')->first();
        if(empty($customer)){
            $response = Http::withHeaders($this->headers)->get(route('view-customer', ['customer' => 1]));
            $this->assertTrue($response->getStatusCode() === 404);

            $response = Http::withHeaders($this->headers)->put(route('update-customer', ['customer' => 1]));
            $this->assertTrue($response->getStatusCode() === 404);

            $response = Http::withHeaders($this->headers)->delete(route('remove-customer', ['customer' => 1]));
            $this->assertTrue($response->getStatusCode() === 404);
        }else{
            $response = Http::withHeaders($this->headers)->get(route('view-customer', ['customer' => $customer->id]));
            $this->assertTrue($response->getStatusCode() === 201);
            $this->assertTrue($response->json('success') === true);

            $response = Http::withHeaders($this->headers)->put(route('update-customer', ['customer' => $customer->id]));
            $this->assertTrue($response->getStatusCode() === 422);
        }
    }

    public function testBookingsMiddlewareTokenAuthentication()
    {
        $this->headers['Authorization'] = 'Bearer ' . $this->token;
        $response = Http::withHeaders($this->headers)->post(route('store-booking'));
        $this->assertTrue($response->getStatusCode() === 422);

        $booking = DB::connection('mysql')->table('booking')->first();
        if(empty($booking)){
            $response = Http::withHeaders($this->headers)->get(route('view-booking', ['booking' => 1]));
            $this->assertTrue($response->getStatusCode() === 404);

            $response = Http::withHeaders($this->headers)->put(route('update-booking', ['booking' => 1]));
            $this->assertTrue($response->getStatusCode() === 404);

            $response = Http::withHeaders($this->headers)->delete(route('remove-booking', ['booking' => 1]));
            $this->assertTrue($response->getStatusCode() === 404);
        }else{
            $response = Http::withHeaders($this->headers)->get(route('view-booking', ['booking' => $booking->id]));
            $this->assertTrue($response->getStatusCode() === 201);
            $this->assertTrue($response->json('success') === true);

            $response = Http::withHeaders($this->headers)->put(route('update-booking', ['booking' => $booking->id]));
            $this->assertTrue($response->getStatusCode() === 422);
        }
    }

    public function testPaymentsMiddlewareTokenAuthentication()
    {
        $this->headers['Authorization'] = 'Bearer ' . $this->token;
        $response = Http::withHeaders($this->headers)->post(route('store-payment'));
        $this->assertTrue($response->getStatusCode() === 422);

        $payment = DB::connection('mysql')->table('payment')->first();
        if(empty($payment)){
            $response = Http::withHeaders($this->headers)->get(route('view-payment', ['payment' => 1]));
            $this->assertTrue($response->getStatusCode() === 404);

            $response = Http::withHeaders($this->headers)->put(route('update-payment', ['payment' => 1]));
            $this->assertTrue($response->getStatusCode() === 404);

            $response = Http::withHeaders($this->headers)->delete(route('remove-payment', ['payment' => 1]));
            $this->assertTrue($response->getStatusCode() === 404);
        }else{
            $response = Http::withHeaders($this->headers)->get(route('view-payment', ['payment' => $payment->id]));
            $this->assertTrue($response->getStatusCode() === 201);
            $this->assertTrue($response->json('success') === true);

            $response = Http::withHeaders($this->headers)->put(route('update-payment', ['payment' => $payment->id]));
            $this->assertTrue($response->getStatusCode() === 422);
        }
    }

    public function tearDown(): void
    {
        DB::connection('mysql')->table('users')->where('email', UserControllerTest::$userEmail)->limit(1)->delete();
        parent::tearDown();
    }
}
