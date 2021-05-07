<?php

namespace App\Controller;

use App\Repository\CustomerRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Exception;
use Symfony\Component\Security\Core\Security;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     *  methods={"GET"}
     * )
     */
    public function relations(CustomerRepository $customerRepository)
    {
        $user = $this->security->getUser()->getId();
        $nbCustomerToAReseller = $customerRepository->findCustomerToAReseller($user);
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
        $nbCustomerToAReseller = $customerRepository->findCustomerToAReseller($user);
        $paginator = new Paginator($nbCustomerToAReseller);
        foreach ($paginator->getQuery() as $customerTab) {
            if($customerTab['id'] === (int) $request->get('id')){
                 return $this->json($customerTab);
            }
        }
       return throw new Exception("No customer found !!");
    }
}
