<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class TestController extends AbstractController
{
    #[Route('/test/index', name: 'app_test_index')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/TestController.php',
        ]);
    }

    #[Route('/test/numer', name: 'app_test_numer')]
    public function numer(): JsonResponse
    {
        $number = random_int(0, 100);

        return $this->json([
            'message' => 'Numer: ' . $number,
            'path' => 'src/Controller/TestController.php',
        ]);
    }
}
