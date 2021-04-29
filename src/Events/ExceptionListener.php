<?php
	
namespace App\Events;


use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ExceptionListener
{
	public function onKernelException(ExceptionEvent $event)
	{
		$exception = $event->getThrowable();
		$result['body'] = [
			'code' => $exception->getStatusCode(),
			'message' => $exception->getMessage()
		];
		$response = new JsonResponse($result['body'], $result['body']['code']);
		if ($exception instanceof HttpExceptionInterface) {
			return $event->setResponse($response);
		}
		return $event->setResponse($response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR));
	}
}