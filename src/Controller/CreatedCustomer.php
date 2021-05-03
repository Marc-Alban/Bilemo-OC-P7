<?php

namespace App\Controller;

use App\Entity\Customer;
use Symfony\Component\Security\Core\Security;


class CreatedCustomer
{
    
    /**
     * @var Security
     */
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function __invoke(Customer $data): Customer
    {
	    dd('test');
        $data->setRole("ROLE_USER");
        $data->setResellers($this->security->getUser());
        return $data;
    }

}
