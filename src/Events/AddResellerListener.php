<?php

namespace App\Events;

use App\Entity\Reseller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AddResellerListener implements EventSubscriberInterface
{


    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['createdReseller', EventPriorities::POST_WRITE]
        ];
    }

    public function createdReseller(ViewEvent $event): void
    {
        $result = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();
        if ($result instanceof Reseller && $method === Request::METHOD_POST) {
            if ($result->getRoles() === ['ROLE_RESELLER']) {
                $result->setAdmin(null);
            }
        }
    }
}
