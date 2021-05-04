<?php
	

namespace App\Events;


use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Reseller;
use App\Entity\Customer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PasswordEncoderSubscriber implements EventSubscriberInterface
{
	
	private UserPasswordEncoderInterface $encoder;
	
	public function __construct(UserPasswordEncoderInterface $encoder)
	{
		$this->encoder = $encoder;
	}
	
	public static function getSubscribedEvents()
	{
		return [
			KernelEvents::VIEW => ['encodePassword', EventPriorities::PRE_WRITE]
		];
	}
	
	public function encodePassword(ViewEvent $event)
	{
		$result = $event->getControllerResult();
		$method = $event->getRequest()->getMethod();
		
		if($result instanceof Reseller || $result instanceof Customer && $method === "POST"){
			$hash = $this->encoder->encodePassword($result, $result->getPassword());
			$result->setPassword($hash);
		}
	}
}