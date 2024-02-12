<?php

namespace Tests\Unit;

use App\Http\Controllers\Api\CustomerController;
use App\Models\Customer;
use Illuminate\Http\Request;
use Tests\TestCase;

class CustomerControllerTest extends TestCase
{
    public function testStoreCustomer()
    {
        $data = [
            'name' => 'Test Customer',
            'email' => 'test@customer.com',
            'phone' => '0899123455',
        ];

        $controller = new CustomerController();
        $method = new \ReflectionMethod($controller, 'store');
        $method->setAccessible(true);

        $request = new Request();
        $request->setMethod('POST');
        $request->replace($data);

        $response = $method->invoke($controller, $request);
        $get = json_decode($response->getContent(), true);

        $this->assertTrue($response->getStatusCode() === 201);
        $this->assertTrue($get['success'] === true);

        $check = Customer::where('email', $data['email'])->first();
        $this->assertNotNull($check);
        $this->assertEquals($data['email'], $check->email);
    }

    public function testUpdateCustomer()
    {
        $data = [
            'name' => 'Update Customer',
            'email' => 'update@customer.com',
            'phone' => '0899123455',
        ];

        $controller = new CustomerController();
        $method = new \ReflectionMethod($controller, 'update');
        $method->setAccessible(true);

        $request = new Request();
        $request->setMethod('PUT');
        $request->replace($data);

        $customer = Customer::orderBy('id', 'desc')->first();
        $response = $method->invoke($controller, $request, $customer);
        $get = json_decode($response->getContent(), true);

        $this->assertTrue($response->getStatusCode() === 201);
        $this->assertTrue($get['success'] === true);

        $check = Customer::where('email', $data['email'])->first();
        $this->assertNotNull($check);
        $this->assertEquals($data['email'], $check->email);
    }

    public function testDeleteCustomer()
    {
        $controller = new CustomerController();
        $method = new \ReflectionMethod($controller, 'remove');
        $method->setAccessible(true);

        $customer = Customer::orderBy('id', 'desc')->first();
        $response = $method->invoke($controller, $customer);
        $get = json_decode($response->getContent(), true);

        $this->assertTrue($response->getStatusCode() === 201);
        $this->assertTrue($get['success'] === true);
    }
}
