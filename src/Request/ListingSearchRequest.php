<?php

namespace App\Request;

use Symfony\Component\Validator\Constraints as Assert;

class ListingSearchRequest extends BaseRequest
{
        #[Assert\NotBlank]
        #[Assert\Date]
        public $availableFromDate;

        #[Assert\NotBlank]
        #[Assert\Date]
        public $availableToDate;

        #[Assert\Type('integer')]
        #[Assert\Positive]
        public $rooms;

        #[Assert\Type('integer')]
        #[Assert\Positive]
        public $capacity;

        #[Assert\Type('boolean')]
        public $hasWifi;

        #[Assert\Type('boolean')]
        public $hasPrivateBathroom;

        #[Assert\Choice(['apartment', 'house', 'villa', 'condo', 'cabin', 'hostel', 'hotel', 'resort', 'campsite', 'boat'])]
        public $listingType;
}
