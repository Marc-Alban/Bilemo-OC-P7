<?php

namespace App\Events;

use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpFoundation\JsonResponse;
use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Customer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

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

    public function checkUserDeleted(ViewEvent $event): void
    {
        $method = $event->getRequest()->getMethod();
        $customer = $event->getControllerResult();
        $reseller = $this->security->getUser();

        if ($customer instanceof Customer && $customer->getcustomersResellers()->getId() !== $reseller->getId()) {
            throw new AccessDeniedException("Prohibited operation. You can not delete other customer  if is not your.");
        } elseif ($reseller->getRoles() !== ['ROLE_RESELLER']) {
            throw new AccessDeniedException("Prohibited operation. You can only delete a customer defined with the property ROLE_USER.");
        } elseif (Request::METHOD_DELETE === $method) {
            $data = ['message' => 'Customer has deleted !','Content-Type' => 'application/json'];
            $response = new JsonResponse($data, 200);
            $event->setResponse($response);
            $this->em->remove($customer);
            $this->em->flush();
        }
        return;
    }
}
