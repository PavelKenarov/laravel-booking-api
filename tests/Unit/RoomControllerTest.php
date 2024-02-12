<?php

namespace Tests\Unit;

use App\Http\Controllers\Api\RoomController;
use App\Models\Room;
use Illuminate\Http\Request;
use Tests\TestCase;

/**
 * @group second
 */
class RoomControllerTest extends TestCase
{
    public function testStoreRoom()
    {
        $controller = new RoomController();
        $method = new \ReflectionMethod($controller, 'store');
        $method->setAccessible(true);

        $request = new Request();
        $request->setMethod('POST');
        $request->replace([
            'number' => 101,
            'name' => 'Standard Room',
            'price' => 100.00,
            'type' => Room::TYPE_ONE,
        ]);

        $response = $method->invoke($controller, $request);
        $get = json_decode($response->getContent(), true);

        $this->assertTrue($response->getStatusCode() === 201);
        $this->assertTrue($get['success'] === true);
    }

    public function testUpdateRoom()
    {
        $controller = new RoomController();
        $method = new \ReflectionMethod($controller, 'update');
        $method->setAccessible(true);

        $request = new Request();
        $request->setMethod('PUT');
        $request->replace([
            'number' => 105,
            'name' => 'Updated Room',
            'price' => 120.00,
            'type' => Room::TYPE_STUDIO,
        ]);

        $room = Room::orderBy('id', 'desc')->first();

        $response = $method->invoke($controller, $request, $room);
        $get = json_decode($response->getContent(), true);

        $this->assertTrue($response->getStatusCode() === 201);
        $this->assertTrue($get['success'] === true);
    }

    public function testDeleteRoom()
    {
        $controller = new RoomController();
        $method = new \ReflectionMethod($controller, 'remove');
        $method->setAccessible(true);

        $room = Room::orderBy('id', 'desc')->first();
        $response = $method->invoke($controller, $room);
        $get = json_decode($response->getContent(), true);

        $this->assertTrue($response->getStatusCode() === 201);
        $this->assertTrue($get['success'] === true);
    }

}
