<?php

use App\Services\CustomerImportService;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Laravel\Lumen\Testing\TestCase as BaseTestCase;
use Mockery as m;

class CustomerImportServiceTest extends BaseTestCase
{
    public function createApplication()
    {
        // Load the Lumen application
        $app = require __DIR__ . '/../bootstrap/app.php';

        // Configure the application for testing
        $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

        return $app;
    }

    /**
     * @covers \App\Services\CustomerImportService::importCustomers
     */
    public function testImportCustomers()
    {
        // Mock the Guzzle Client
        $mockHandler = new MockHandler([new Response(200, [], '{"results": []}')]);
        $handlerStack = HandlerStack::create($mockHandler);
        $client = new Client(['handler' => $handlerStack]);

        // Create an instance of the CustomerImportService with the mock client
        $service = new CustomerImportService($this->app['em'], $client);

        // Test the importCustomers method
        $service->importCustomers();

        // Add assertions based on your implementation
        $this->assertTrue(true);
    }
}
