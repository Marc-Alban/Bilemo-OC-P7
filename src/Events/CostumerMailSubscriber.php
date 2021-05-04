<?php
	
	namespace App\Events;
	
	use ApiPlatform\Core\EventListener\EventPriorities;
	use App\Entity\Consumer;
	use Symfony\Component\EventDispatcher\EventSubscriberInterface;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpKernel\Event\ViewEvent;
	use Symfony\Component\HttpKernel\KernelEvents;
	
	final class CostumerMailSubscriber implements EventSubscriberInterface
	{
		private $mailer;
		
		public function __construct(\Swift_Mailer $mailer)
		{
			$this->mailer = $mailer;
		}
		
		public static function getSubscribedEvents()
		{
			return [
				KernelEvents::VIEW => ['sendMail', EventPriorities::POST_WRITE],
			];
		}
		
		public function sendMail(ViewEvent $event): void
		{
			$consumer = $event->getControllerResult();
			$method = $event->getRequest()->getMethod();
			
			if (!$consumer instanceof Consumer || Request::METHOD_POST !== $method) {
				return;
			}
			
			$message = (new \Swift_Message('NOUVEAU COMPTE API BILEMO'))
				->setFrom('noreply@bilemo.com')
				->setTo($consumer->getEmail())
				->setBody("Votre compte Api Bilemo vient d'être ajouté. Vous pouvez vous connecter avec le pseudo suivant : {$consumer->getUsername()} ; et le mot de passe que vous nous aviez communiqué. Ce compte utilisateur est rattaché au compte client {$consumer->getClient()->getName()} et est référencé avec l'identifiant n°{$consumer->getId()}. Nous vous remercions pour votre confiance !");
			
			$this->mailer->send($message);
			
		}
		
	}
