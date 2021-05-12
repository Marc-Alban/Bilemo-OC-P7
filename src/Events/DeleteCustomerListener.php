<?php

namespace App\Events;

use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpFoundation\JsonResponse;
use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Reseller;
use App\Entity\Customer;

final class DeleteCustomerListener implements EventSubscriberInterface
{

    private EntityManagerInterface $em;
    public function __construct(Security $security, EntityManagerInterface $em)
    {
        $this->security = $security;
        $this->em = $em;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['checkUserDeleted', EventPriorities::PRE_WRITE],
        ];
    }

    public function checkUserDeleted(ViewEvent $event)
    {
        $customer = $event->getControllerResult();

        $resellerRole = $this->security->getUser()->getRoles();
        $customerRole = $customer->getRoles();

        $reseller = $this->security->getUser();

        if ($customer->getCustomersResellers()->getId() !== $reseller->getId())
        {
            throw new AccessDeniedException("Prohibited operation. You can not delete other customer  if is not your.");
        }
        elseif($resellerRole !== ['ROLE_RESELLER'] && $customerRole !== ['ROLE_USER']) 
        {
            throw new AccessDeniedException("Prohibited operation. You can only delete a customer defined with the property ROLE_USER.");
        }
        elseif($event->getRequest()->getMethod() !== "DELETE")
        {
            throw new AccessDeniedException("Prohibited operation. You can only delete a customer with the method DELETE.");
        }

        $response = new JsonResponse(
            ['message' => 'Customer has deleted !'],
            '200',
            ['Content-Type' => 'application/json']
        );       
        
        $event->setResponse($response);
        $this->em->remove($customer);
        $this->em->flush();
    }
}