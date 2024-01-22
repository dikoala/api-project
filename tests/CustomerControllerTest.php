<?php

// tests/CustomerControllerTest.php

namespace Tests;

use Laravel\Lumen\Testing\TestCase as BaseTestCase;
use Database\Factories\UserFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;

class CustomerControllerTest extends BaseTestCase
{
    public function createApplication()
    {
        return require __DIR__ . '/../bootstrap/app.php';
    }

    public function setUp(): void
    {
        parent::setUp();

        // Migrate the database before each test
        $this->artisan('migrate');
    }

    public function tearDown(): void
    {
        // Rollback the database transactions after each test
        DB::rollBack();

        parent::tearDown();
    }

    /**
     * @covers \App\Http\Controllers\CustomerController::getAllCustomers
     */
    public function testGetAllCustomers()
    {
        // Create a few customers using the factory
        UserFactory::new()->count(3)->create();

        // Make a request to the endpoint to get all customers
        $response = $this->call('GET', '/customers');

        // Assert that the response has a successful status code (HTTP status 200)
        $this->assertEquals(200, $response->status());

        // Assert the JSON structure of the response
        $response->assertJsonStructure([
            '*' => ['full_name', 'email', 'country'],
        ]);
    }

    /**
     * @covers \App\Http\Controllers\CustomerController::getCustomerDetails
     */
    public function testGetCustomerDetails()
    {
        // Create a customer using the factory or other methods
        $customer = UserFactory::new()->create();

        // Make a request to the endpoint to get customer details
        $response = $this->call('GET', '/customers/' . $customer->id);

        // Assert that the response has a successful status code (HTTP status 200)
        $this->assertEquals(Response::HTTP_OK, $response->status());

        // Assert the JSON structure of the response
        $response->assertJsonStructure([
            'full_name',
            'email',
            'username',
            'gender',
            'country',
            'city',
            'phone',
        ]);

        // Additional assertions based on your specific requirements
        $responseData = $response->json();
        $this->assertEquals($customer->getFullName(), $responseData['full_name']);
        $this->assertEquals($customer->getEmail(), $responseData['email']);
        // ... Add more assertions for other properties
    }
}
