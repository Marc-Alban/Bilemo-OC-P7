<?php

namespace App\Events;

use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpFoundation\JsonResponse;
use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use App\Entity\Customer;
use App\Entity\Reseller;


final class DeleteCustomerListener implements EventSubscriberInterface
{

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['checkUserDeleted', EventPriorities::PRE_WRITE],
        ];
    }

    public function checkUserDeleted(ViewEvent $event): void
    {
        $customer = $event->getControllerResult();
        $pathInfo = $event->getRequest()->getPathInfo();
        $resellerRole = $this->security->getUser()->getRoles() === 'ROLE_RESELLER';
        $customerRole = $customer->getRoles() === 'ROLE_USER';
        $currentUserRefClient = $this->security->getUser()->getCustomers();
        $customerRefClient = $customer->getResellers();

        if ($this->security->getUser()->getId() === $customer->getId()) 
        {
            throw new AccessDeniedException("Prohibited operation. You can not delete your own account.");
        }elseif($resellerRole && !$customerRole) 
        {
            throw new AccessDeniedException("Prohibited operation. You can only delete a customer defined with the property ROLE_USER.");
        }elseif($resellerRole && $currentUserRefClient !== $customerRefClient) 
        {
            throw new AccessDeniedException("Prohibited operation. You can only delete a customer of your client reference.");
        }elseif(strpos($pathInfo, '/api/customers/') !== false && $event->getRequest()->getMethod() === 'DELETE') {
            $data['data'] = array(
                'message' => 'Customer has deleted !',
            );
            $response = new JsonResponse(
                $data,
                '200'
            );
            $response->headers->set('Content-Type', 'application/ld+json');
            $event->setResponse($response);
        }
        else {
            return;
        }
    }
}