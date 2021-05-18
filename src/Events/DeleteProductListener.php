<?php

namespace App\Events;

use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpFoundation\JsonResponse;
use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Product;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

final class DeleteProductListener implements EventSubscriberInterface
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
            KernelEvents::VIEW => ['checkProductDeleted', EventPriorities::PRE_WRITE],
        ];
    }

    public function checkProductDeleted(ViewEvent $event): void
    {
        $method = $event->getRequest()->getMethod();
        $product = $event->getControllerResult();
        $admin = $this->security->getUser();
        
        if ($admin->getRoles() !== ['ROLE_ADMIN'] && $product instanceof Product ) {
            throw new AccessDeniedException("Prohibited operation. You can only delete a customer defined with the property ROLE_USER.");
        } 
        
        if (Request::METHOD_DELETE === $method && $product instanceof Product) {
            $data = ['message' => 'Product has deleted !','Content-Type' => 'application/json'];
            $response = new JsonResponse($data, 200);
            $event->setResponse($response);
            $this->em->remove($product);
            $this->em->flush();
        }
        return;
    }
}
