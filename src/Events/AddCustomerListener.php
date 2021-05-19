<?php

namespace App\Events;

use App\Entity\Customer;
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
        if ($result instanceof Customer && $method === Request::METHOD_POST) {
            if ($reseller->getRoles() === ['ROLE_RESELLER']) {
                $result->setCustomersResellers($this->security->getUser());
                $result->setCustomersAdmin(null);
            } elseif ($reseller->getRoles() === ['ROLE_ADMIN']) {
                $result->setCustomersResellers(null);
                $result->setCustomersAdmin($this->security->getUser());
            }
        }
    }
}
