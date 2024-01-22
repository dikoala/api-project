<?php

namespace App\Services;

use App\Entity\Customer;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class CustomerImportService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function importCustomers()
    {
        try {
            $client = new Client();
            $apiUrl = config('customers.api_url');
            $importLimit = config('customers.import_limit');

            $apiUrl .= $importLimit ? '/?nat=au&results=' . $importLimit : '';

            $response = $client->get($apiUrl);
            $data = json_decode($response->getBody()->getContents(), true)['results'];

            foreach ($data as $user) {
                $this->importCustomer($user);
            }

            Log::info('Customers imported successfully!');
        } catch (\Exception $e) {
            Log::error('Error importing customers: ' . $e->getMessage());
        }
    }

    private function importCustomer(array $userData)
    {
        try {
            $customer = $this->entityManager
                ->getRepository(Customer::class)
                ->findOneBy(['email' => $userData['email']]);

            if ($customer) {
                $this->updateCustomer($customer, $userData);
            } else {
                $this->createCustomer($userData);
            }
        } catch (\Exception $e) {
            Log::error('Error importing customer: ' . $e->getMessage());
        }
    }

    private function createCustomer(array $userData)
    {
        try {
            $customer = new Customer();
            $this->updateCustomer($customer, $userData, true);
            $this->entityManager->persist($customer);
            $this->entityManager->flush();
        } catch (\Exception $e) {
            Log::error('Error creating customer: ' . $e->getMessage());
        }
    }

    private function updateCustomer(Customer $customer, array $userData, bool $isNewCustomer = false)
    {
        try {
            // Set properties on the Customer entity based on $userData
            $customer->setUsername($userData['login']['username'] ?? '');
            $customer->setPassword(md5($userData['login']['password'] ?? ''));

            // Set other properties as needed
            $customer->setFirstName($userData['name']['first'] ?? '');
            $customer->setLastName($userData['name']['last'] ?? '');
            $customer->setGender($userData['gender'] ?? '');
            $customer->setCountry($userData['location']['country'] ?? '');
            $customer->setCity($userData['location']['city'] ?? '');
            $customer->setPhone($userData['phone'] ?? '');
            $customer->setEmail($userData['email'] ?? '');

            // Set timestamps
            $now = new \DateTime();
            $customer->setUpdatedAt($now);

            if ($isNewCustomer) {
                // Set created_at only for new customers
                $customer->setCreatedAt($now);
            }

            $this->entityManager->persist($customer);
            $this->entityManager->flush();
        } catch (\Exception $e) {
            Log::error('Error updating customer: ' . $e->getMessage());
        }
    }
}
