<?php

namespace App\Controller;

use App\Http\ApiResponse;
use App\Repository\ListingRepository;
use App\Request\ListingSearchRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ListingController extends AbstractController
{
    private ListingRepository $listingRepository;

    public function __construct(ListingRepository $listingRepository)
    {
        $this->listingRepository = $listingRepository;
    }

    #[Route('/listing/search', name: 'app_listing_search', methods: ['POST'])]
    public function search(ListingSearchRequest $request): JsonResponse
    {
        $request->validate();

        $listings = $this->listingRepository->searchInListings(
            availableFromDate: $request->availableFromDate,
            availableToDate: $request->availableToDate,
            rooms: $request->rooms,
            capacity: $request->capacity,
            hasWifi: $request->hasWifi,
            hasPrivateBathroom: $request->hasPrivateBathroom,
            listingType: $request->listingType
        );

        return new ApiResponse('Listing search', $listings, [], 200);
    }

    #[Route('/listing/detail/{reference}', name: 'app_listing_detail', requirements: ['reference' => '[a-z0-9-]+'], methods: ['GET'])]
    public function detail($reference): JsonResponse
    {
        $listing = $this->listingRepository->findByReference($reference);

        return new ApiResponse('Listing detail', $listing, [], 200);
    }
}
