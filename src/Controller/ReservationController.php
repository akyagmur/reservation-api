<?php

namespace App\Controller;

use App\Entity\Guest;
use App\Entity\Reservation;
use App\Http\ApiResponse;
use App\Repository\GuestRepository;
use App\Repository\ListingRepository;
use App\Repository\ReservationRepository;
use App\Request\CreateReservationRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ReservationController extends AbstractController
{
    private ReservationRepository $reservationRepository;
    private GuestRepository $guestRepository;
    private ListingRepository $listingRepository;

    public function __construct(ReservationRepository $reservationRepository, GuestRepository $guestRepository, ListingRepository $listingRepository)
    {
        $this->reservationRepository = $reservationRepository;
        $this->guestRepository = $guestRepository;
        $this->listingRepository = $listingRepository;
    }

    #[Route('/reservation/create', name: 'app_reservation_create', methods: ['POST'])]
    public function create(CreateReservationRequest $request): JsonResponse
    {
        $request->validate();

        $guest = $this->guestRepository->find($request->guestId); // TODO: Implement JWT authentication
        $listing = $this->listingRepository->findByReference($request->listingReference);

        $reservation = new Reservation();
        $reservation->setGuest($guest);
        $reservation->setListing($listing);
        $reservation->setStartDate(\DateTime::createFromFormat('Y-m-d', $request->startDate));
        $reservation->setEndDate(\DateTime::createFromFormat('Y-m-d', $request->endDate));

        $reservation = $this->reservationRepository->save($reservation, true);

        return new ApiResponse('Reservation created', $reservation, [], 200);
    }

    #[Route('/reservation/{reference}', name: 'app_reservation_get', requirements: ['reference' => '[a-z0-9-]+'], methods: ['GET'])]
    public function get(string $reference): JsonResponse
    {
        $reservation = $this->reservationRepository->findByReference($reference);

        return new ApiResponse('Reservation found', $reservation, [], 200);
    }

    #[Route('/reservations/guest/{guest}', name: 'app_get_reservations_by_guest', requirements: ['guest' => '[0-9]+'], methods: ['GET'])]
    public function getReservationsByGuest(Guest $guest): JsonResponse
    {
        $reservations = $this->reservationRepository->findByGuest($guest);

        return new ApiResponse('Reservations found', $reservations, [], 200);
    }

    #[Route('/reservation/{reference}/cancel', name: 'app_reservation_cancel', requirements: ['reference' => '[a-z0-9-]+'], methods: ['PATCH'])]
    public function cancel(string $reference): JsonResponse
    {
        $reservation = $this->reservationRepository->findByReference($reference);
        $reservation->setIsCancelled(true);

        $this->reservationRepository->save($reservation, true);

        return new ApiResponse('Reservation cancelled', $reservation, [], 200);
    }
}
