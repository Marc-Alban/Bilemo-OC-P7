<?php

namespace App\Events;

use App\Entity\Customer;
use App\Entity\Reseller;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AddCustomerListener implements EventSubscriberInterface
{

    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['createdCustomer', EventPriorities::PRE_WRITE]
        ];
    }

    public function createdCustomer(ViewEvent $event): void
    {
        $result = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();
        $reseller = $this->security->getUser();
        $method = $event->getRequest()->getMethod();
        if($reseller instanceof Reseller === false && $method === Request::METHOD_POST){
            throw new Exception("Forbiden: Just Reseller can created a Customer", 403);         
        }
        if ($result instanceof Customer && $method === Request::METHOD_POST) {
            $result->setCustomersResellers($reseller);
        }
    }
}
