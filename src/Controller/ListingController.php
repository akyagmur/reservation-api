<?php

namespace App\Controller;

use App\Http\ApiResponse;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;

class ListingController extends AbstractController
{
    #[Route('/listing', name: 'app_listing')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ListingController.php',
        ]);
    }

    #[Route('/listing/detail/{id}', name: 'app_listing_detail', requirements: ['id' => '\d+'])]
    public function detail($id): JsonResponse
    {
        if ($id === "1") {
            throw new Exception('Listing not found');
        }
        return new ApiResponse('Listing detail', ['id' => $id], [], 200);
    }
}
