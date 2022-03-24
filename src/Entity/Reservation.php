<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'integer')]
    private $nbr_voyagers;

    #[ORM\Column(type: 'date')]
    private $start_date;

    #[ORM\Column(type: 'date')]
    private $end_date;

    #[ORM\Column(type: 'float')]
    private $total;

    #[ORM\Column(type: 'integer')]
    private $nbr_nights;

    #[ORM\Column(type: 'datetime_immutable')]
    private $created_at;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private $updated_at;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'reservation')]
    #[ORM\JoinColumn(nullable: false)]
    private $guest;

    #[ORM\ManyToOne(targetEntity: House::class, inversedBy: 'reservation')]
    #[ORM\JoinColumn(nullable: false)]
    private $house;

    #[ORM\OneToOne(mappedBy: 'reservation', targetEntity: Event::class, cascade: ['persist', 'remove'])]
    private $event;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNbrVoyagers(): ?int
    {
        return $this->nbr_voyagers;
    }

    public function setNbrVoyagers(int $nbr_voyagers): self
    {
        $this->nbr_voyagers = $nbr_voyagers;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->start_date;
    }

    public function setStartDate(\DateTimeInterface $start_date): self
    {
        $this->start_date = $start_date;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->end_date;
    }

    public function setEndDate(\DateTimeInterface $end_date): self
    {
        $this->end_date = $end_date;

        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(float $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function getNbrNights(): ?int
    {
        return $this->nbr_nights;
    }

    public function setNbrNights(int $nbr_nights): self
    {
        $this->nbr_nights = $nbr_nights;

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

    public function getGuest(): ?User
    {
        return $this->guest;
    }

    public function setGuest(?User $guest): self
    {
        $this->guest = $guest;

        return $this;
    }

    public function getHouse(): ?House
    {
        return $this->house;
    }

    public function setHouse(?House $house): self
    {
        $this->house = $house;

        return $this;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): self
    {
        // unset the owning side of the relation if necessary
        if ($event === null && $this->event !== null) {
            $this->event->setReservation(null);
        }

        // set the owning side of the relation if necessary
        if ($event !== null && $event->getReservation() !== $this) {
            $event->setReservation($this);
        }

        $this->event = $event;

        return $this;
    }
}
