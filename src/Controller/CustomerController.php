<?php

namespace App\Controller;

use Exception;
use App\Repository\CustomerRepository;
use App\Entity\Customer;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class CustomerController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }


    /**
     * @Route(
     *  path="api/customers",
     *  name="relation_customer_to_reseller",
     *  methods={"GET"},
     * )
     */
    public function relations(CustomerRepository $customerRepository): JsonResponse
    {
        $user = $this->security->getUser()->getId();
        $nbCustomerToAReseller = $customerRepository->findByReseller($user);
        return $this->json($nbCustomerToAReseller);

    }

    /**
     * @Route(
     *  path="api/customers/{id}",
     *  name="customer_one_reseller",
     *  methods={"GET"}
     * )
     */
    public function relationDetail(CustomerRepository $customerRepository, Request $request)
    {
        $user = $this->security->getUser()->getId();
        $nbCustomerToAReseller = $customerRepository->findByReseller($user);
        foreach ($nbCustomerToAReseller as $customerTab) {
            if($customerTab['id'] === (int) $request->get('id')){
                 return $this->json($customerTab);
            }
        }
       return throw new Exception("No customer found !!");
    }
}
