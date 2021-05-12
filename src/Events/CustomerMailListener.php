<?php
	
	namespace App\Events;
	
	use ApiPlatform\Core\EventListener\EventPriorities;
	use App\Entity\Customer;
	use Symfony\Component\EventDispatcher\EventSubscriberInterface;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpKernel\Event\ViewEvent;
	use Symfony\Component\HttpKernel\KernelEvents;
	
	final class CustomerMailListener implements EventSubscriberInterface
	{
		private $mailer;
		
		public function __construct(\Swift_Mailer $mailer)
		{
			$this->mailer = $mailer;
		}
		
		public static function getSubscribedEvents(): array 
		{
			return [
				KernelEvents::VIEW => ['sendMail', EventPriorities::POST_WRITE],
			];
		}
		
		public function sendMail(ViewEvent $event): void
		{
			$customer = $event->getControllerResult();
			$method = $event->getRequest()->getMethod();
			
			if (!$customer instanceof Customer || Request::METHOD_POST !== $method) {
				return;
			}
			
			$message = (new \Swift_Message('NEW BILEMO API ACCOUNT'))
				->setFrom('noreply@bilemo.com')
				->setTo($customer->getEmail())
				->setBody("Your Api Bilemo account has been added. You can sign in with the following nickname: {$customer->getLastName()} ; and the password you gave us. This user account is attached to the customer account {$customer->getCustomersResellers()->getName()} and is referenced with the identifier n°{$customer->getId()}. Thank you for your confidence!");
			$this->mailer->send($message);
		}
	}
