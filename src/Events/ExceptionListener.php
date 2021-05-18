<?php

namespace App\Events;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $code = $this->getCodeError($exception);

        $result['body'] = [
            'code' => $code,
            'message' => $exception->getMessage()
        ];

        $response = new JsonResponse($result['body'], $code);

        $event->setResponse($response);
    }

    public function getCodeError(\Throwable $exception): int
    {
        if (!$exception->getCode() && $exception instanceof HttpExceptionInterface) {
            $code = $exception->getStatusCode();
        } elseif (!$exception->getCode()) {
            $code = Response::HTTP_INTERNAL_SERVER_ERROR;
        } else {
            $code = $exception->getCode();
        }
        return $code;
    }
}
