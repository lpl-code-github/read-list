<?php

namespace App\Controller;

use App\Util\Result;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

class ErrorController extends AbstractController
{
    #[Route('/error', name: 'app_error')]
    public function show(Throwable $exception): JsonResponse
    {
        $result = new Result();
        return match ($exception->getCode()) {
            400 => $this->json($result->paramError($exception->getMessage())),
            404 => $this->json($result->notFound($exception->getMessage())),
            default => $this->json($result->serverError($exception->getMessage())),
        };
    }
}
