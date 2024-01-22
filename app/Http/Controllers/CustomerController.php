<?php

namespace App\Http\Controllers;

use App\Entity\Customer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException; // Add this import

class CustomerController extends Controller
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getAllCustomers()
    {
        try {
            $customerRepository = $this->entityManager->getRepository(Customer::class);
            $customers = $customerRepository->findAll();

            $formattedCustomers = array_map(function ($customer) {
                return [
                    'full_name' => $customer->getFirstName() . ' ' . $customer->getLastName(),
                    'email' => $customer->getEmail(),
                    'country' => $customer->getCountry(),
                ];
            }, $customers);

            return response()->json($formattedCustomers);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getCustomerDetails($customerId)
    {
        try {
            $customerRepository = $this->entityManager->getRepository(Customer::class);
            $customer = $customerRepository->find($customerId);

            if (!$customer) {
                throw new HttpException(Response::HTTP_NOT_FOUND, 'Customer not found.');
            }

            $customerDetails = [
                'full_name' => $customer->getFirstName() . ' ' . $customer->getLastName(),
                'email' => $customer->getEmail(),
                'username' => $customer->getUsername(),
                'gender' => $customer->getGender(),
                'country' => $customer->getCountry(),
                'city' => $customer->getCity(),
                'phone' => $customer->getPhone(),
            ];

            return response()->json($customerDetails);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
