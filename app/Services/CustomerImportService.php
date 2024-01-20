<?php

namespace App\Services;

use GuzzleHttp\Client;
use App\Models\Customer;

class CustomerImportService
{
    public function importCustomers()
    {
        $client = new Client();
        $response = $client->get(config('customers.third_party_api_url') . '/?nat=au&results=100');

        $data = json_decode($response->getBody()->getContents(), true)['results'];

        foreach ($data as $user) {
            $this->importCustomer($user);
        }
    }

    private function importCustomer(array $userData)
    {
        $userData['clear_password'] = $userData['login']['password'];

        // Check if the customer already exists by email
        $customer = Customer::where('email', $userData['email'])->first();

        if ($customer) {
            // If the customer exists, update the data
            $customer->update($userData);
        } else {
            // If the customer doesn't exist, create a new one
            Customer::create($userData);
        }
    }
}
