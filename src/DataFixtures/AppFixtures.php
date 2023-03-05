<?php

namespace App\DataFixtures;

use App\Entity\Guest;
use App\Entity\Listing;
use App\Entity\Reservation;
use App\Repository\GuestRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public $guestRepository;

    public function __construct(GuestRepository $guestRepository)
    {
        $this->guestRepository = $guestRepository;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create();

        $listings = [];
        for ($i = 0; $i < 10; $i++) {
            $listing = new Listing();
            $listing->setListingType($faker->randomElement(["apartment", "house", "villa", "condo", "cabin", "hostel", "hotel", "resort", "campsite", "boat"]));
            $rooms = $faker->numberBetween(1, 5);
            $listing->setRooms($faker->numberBetween(1, 5));
            $listing->setCapacity($faker->numberBetween($rooms, $rooms * 2));
            $listing->setHasWifi($faker->boolean());
            $listing->setHasPrivateBathroom($faker->boolean());
            $listings[] = $listing;
            $manager->persist($listing);
        }

        $guests = [];
        for ($i = 0; $i < 10; $i++) {
            $guest = new Guest();
            $guest->setName($faker->firstName());
            $guest->setSurname($faker->lastName());
            $guest->setEmail($faker->email());
            $guest->setPassword(hash('sha256', $faker->password()));
            $guests[] = $guest;
            $manager->persist($guest);
        }

        for ($i = 0; $i < 10; $i++) {
            $guest = $guests[$faker->numberBetween(0, 9)];
            if(!$guest) {
                throw new \Exception('Guest not found');
            }
            $reservation = new Reservation();
            $reservation->setListing($listings[$faker->unique()->numberBetween(0, 9)]);
            $reservation->setGuest($guest);
            $reservation->setStartDate($faker->dateTimeBetween('-1 months', 'now'));
            $reservation->setEndDate($faker->dateTimeBetween('now', '+1 months'));
            $manager->persist($reservation);
        }

        $manager->flush();
    }
}
