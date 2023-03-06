<?php

namespace App\Request;

use App\Repository\ListingRepository;
use App\Repository\ReservationRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateReservationRequest extends BaseRequest
{
        private ListingRepository $listingRepository;
        private ReservationRepository $reservationRepository;

        public function __construct(
                ListingRepository $listingRepository,
                ReservationRepository $reservationRepository,
                protected ValidatorInterface $validator

        ) {
                $this->listingRepository = $listingRepository;
                $this->reservationRepository = $reservationRepository;

                parent::__construct($validator);
        }

        #[Assert\NotBlank]
        #[Assert\Date]
        public $startDate;

        #[Assert\NotBlank]
        #[Assert\Date]
        public $endDate;

        #[Assert\NotBlank]
        #[Assert\Uuid]
        public $listingReference;

        #[Assert\NotBlank]
        #[Assert\Id]
        public $guestId;


        // reservation can not conflict with other reservations
        #[Assert\Callback]
        public function validateDates(ExecutionContextInterface $context)
        {
                $startDate = \DateTime::createFromFormat('Y-m-d', $this->startDate);
                $endDate = \DateTime::createFromFormat('Y-m-d', $this->endDate);

                $listing = $this->listingRepository->findByReference($this->listingReference);

                $conflictingReservations = $this->reservationRepository->findConflictReservations(
                        $listing,
                        $startDate,
                        $endDate
                );

                if (count($conflictingReservations) > 0) {
                        $context->buildViolation('Reservation conflicts with other reservations')
                                ->atPath('startDate')
                                ->addViolation();
                }
        }

        // reservation can not be the outside of listing availability
        #[Assert\Callback]
        public function validateAvailability(ExecutionContextInterface $context)
        {
                $startDate = \DateTime::createFromFormat('Y-m-d', $this->startDate);
                $endDate = \DateTime::createFromFormat('Y-m-d', $this->endDate);

                $listing = $this->listingRepository->findByReference($this->listingReference);

                if ($startDate < $listing->getAvailableFromDate() || $endDate > $listing->getAvailableToDate()) {
                        $context->buildViolation('Reservation is outside of listing availability')
                                ->atPath('startDate')
                                ->addViolation();
                }
        }
}