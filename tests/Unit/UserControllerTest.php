<?php

namespace Tests\Unit;

use App\Http\Controllers\Api\UserController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * @group first
 */
class UserControllerTest extends TestCase
{
    public static $userName     = 'Unit test user';
    public static $userEmail    = 'unit@test.com';
    public static $userPassword = 'password@!^';

    public function testРegister()
    {
        DB::statement('TRUNCATE TABLE users');

        $data = [
            'name' => self::$userName,
            'email' => self::$userEmail,
            'password' => self::$userPassword,
            'password_confirmation' => self::$userPassword
        ];

        $controller = new UserController();
        $method = new \ReflectionMethod($controller, 'register');
        $method->setAccessible(true);

        $request = new Request();
        $request->setMethod('POST');
        $request->replace($data);

        $response = $method->invoke($controller, $request);
        $get = json_decode($response->getContent(), true);

        $this->assertTrue($response->getStatusCode() === 201);
        $this->assertTrue($get['success'] === true);

        $user = User::where('email', $data['email'])->first();

        $this->assertTrue($user->exists());
        $this->assertEquals($data['name'], $user->name);
        $this->assertEquals($data['email'], $user->email);
    }

    public function testLogin()
    {
        $controller = new UserController();
        $method = new \ReflectionMethod($controller, 'login');
        $method->setAccessible(true);

        $request = new Request();
        $request->setMethod('POST');
        $request->replace([
            'email' => self::$userEmail,
            'password' => self::$userPassword
        ]);

        $response = $method->invoke($controller, $request);
        $get = json_decode($response->getContent(), true);

        $this->assertTrue($response->getStatusCode() === 200);
        $this->assertTrue($get['success'] === true);
    }

}
