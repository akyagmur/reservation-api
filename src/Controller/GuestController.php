<?php

namespace App\Controller;

use App\Http\ApiResponse;
use App\Repository\GuestRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class GuestController extends AbstractController
{
    private GuestRepository $guestRepository;

    public function __construct(GuestRepository $guestRepository)
    {
        $this->guestRepository = $guestRepository;
    }

    #[Route('/guests/list', name: 'app_guests_list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $guests = $this->guestRepository->findAll();

        return new ApiResponse('Guests list', $guests, [], 200);
    }
}
