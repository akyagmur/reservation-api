<?php

namespace App\Entity;

use App\Repository\ListingRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;


#[ORM\Entity(repositoryClass: ListingRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ORM\Index(columns: ['reference'])]
#[ORM\Index(columns: ['available_from_date', 'available_to_date'])]
class Listing
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $listing_type = null;

    #[ORM\Column]
    private ?int $rooms = null;

    #[ORM\Column]
    private ?int $capacity = null;

    #[ORM\Column]
    private ?bool $has_wifi = null;

    #[ORM\Column]
    private ?bool $has_private_bathroom = null;

    #[ORM\OneToMany(mappedBy: 'listing', targetEntity: Reservation::class)]
    private Collection $reservations;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updated_at = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $available_from_date = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $available_to_date = null;

    #[ORM\Column(type: UuidType::NAME, unique: true)]
    private ?string $reference = null;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getListingType(): ?string
    {
        return $this->listing_type;
    }

    public function setListingType(string $listing_type): self
    {
        $this->listing_type = $listing_type;

        return $this;
    }

    public function getRooms(): ?int
    {
        return $this->rooms;
    }

    public function setRooms(int $rooms): self
    {
        $this->rooms = $rooms;

        return $this;
    }

    public function getCapacity(): ?int
    {
        return $this->capacity;
    }

    public function setCapacity(int $capacity): self
    {
        $this->capacity = $capacity;

        return $this;
    }

    public function isHasWifi(): ?bool
    {
        return $this->has_wifi;
    }

    public function setHasWifi(bool $has_wifi): self
    {
        $this->has_wifi = $has_wifi;

        return $this;
    }

    public function isHasPrivateBathroom(): ?bool
    {
        return $this->has_private_bathroom;
    }

    public function setHasPrivateBathroom(bool $has_private_bathroom): self
    {
        $this->has_private_bathroom = $has_private_bathroom;

        return $this;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): self
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations->add($reservation);
            $reservation->setListing($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getListing() === $this) {
                $reservation->setListing(null);
            }
        }

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    #[ORM\PrePersist]
    public function setCreatedAtValue(): void
    {
        $this->created_at = new DateTimeImmutable();
    }

    #[ORM\PreUpdate]
    public function setUpdatedAtValue(): void
    {
        $this->updated_at = new DateTimeImmutable();
    }

    public function getAvailableFromDate(): ?\DateTimeInterface
    {
        return $this->available_from_date;
    }

    public function setAvailableFromDate(\DateTimeInterface $available_from_date): self
    {
        $this->available_from_date = $available_from_date;

        return $this;
    }

    public function getAvailableToDate(): ?\DateTimeInterface
    {
        return $this->available_to_date;
    }

    public function setAvailableToDate(\DateTimeInterface $available_to_date): self
    {
        $this->available_to_date = $available_to_date;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(?string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }
    
    #[ORM\PrePersist]
    public function setReferenceValue(): void
    {
        $this->reference = Uuid::v4();
    }
}
