<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\HouseRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: HouseRepository::class)]

class House
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'string', length: 255)]
    private $type;

    #[ORM\Column(type: 'string', length: 255)]
    private $full_address;

    #[ORM\Column(type: 'integer')]
    private $street_number;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $street_sub_number;

    #[ORM\Column(type: 'string', length: 255)]
    private $street_label;

    #[ORM\Column(type: 'string', length: 255)]
    private $city_name;

    #[ORM\Column(type: 'string', length: 255)]
    private $postal_code;

    #[ORM\Column(type: 'integer')]
    private $nbr_accepted;

    #[ORM\Column(type: 'integer')]
    private $nbr_beds;

    #[ORM\Column(type: 'integer')]
    private $nbr_rooms;

    #[ORM\Column(type: 'integer')]
    private $nbr_showeroom;

    #[ORM\Column(type: 'text', nullable: true)]
    private $description;

    #[ORM\Column(type: 'float')]
    private $price;

    #[ORM\Column(type: 'integer')]
    private $calendar_id;

    #[ORM\Column(type: 'json')]
    private $equiments = [];

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'house')]
    #[ORM\JoinColumn(nullable: false)]
    private $host;

    #[ORM\OneToMany(mappedBy: 'house', targetEntity: Reservation::class)]
    private $reservation;

    #[ORM\OneToMany(mappedBy: 'house', targetEntity: Comment::class)]
    private $comment;

    #[ORM\OneToMany(mappedBy: 'house', targetEntity: Attachment::class, orphanRemoval: true)]
    private $attachments;

    public function __construct()
    {
        $this->reservation = new ArrayCollection();
        $this->comment = new ArrayCollection();
        $this->attachments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getFullAddress(): ?string
    {
        return $this->full_address;
    }

    public function setFullAddress(string $full_address): self
    {
        $this->full_address = $full_address;

        return $this;
    }

    public function getStreetNumber(): ?int
    {
        return $this->street_number;
    }

    public function setStreetNumber(int $street_number): self
    {
        $this->street_number = $street_number;

        return $this;
    }

    public function getStreetSubNumber(): ?string
    {
        return $this->street_sub_number;
    }

    public function setStreetSubNumber(?string $street_sub_number): self
    {
        $this->street_sub_number = $street_sub_number;

        return $this;
    }

    public function getStreetLabel(): ?string
    {
        return $this->street_label;
    }

    public function setStreetLabel(string $street_label): self
    {
        $this->street_label = $street_label;

        return $this;
    }

    public function getCityName(): ?string
    {
        return $this->city_name;
    }

    public function setCityName(string $city_name): self
    {
        $this->city_name = $city_name;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postal_code;
    }

    public function setPostalCode(string $postal_code): self
    {
        $this->postal_code = $postal_code;

        return $this;
    }

    public function getNbrAccepted(): ?int
    {
        return $this->nbr_accepted;
    }

    public function setNbrAccepted(int $nbr_accepted): self
    {
        $this->nbr_accepted = $nbr_accepted;

        return $this;
    }

    public function getNbrBeds(): ?int
    {
        return $this->nbr_beds;
    }

    public function setNbrBeds(int $nbr_beds): self
    {
        $this->nbr_beds = $nbr_beds;

        return $this;
    }

    public function getNbrRooms(): ?int
    {
        return $this->nbr_rooms;
    }

    public function setNbrRooms(int $nbr_rooms): self
    {
        $this->nbr_rooms = $nbr_rooms;

        return $this;
    }

    public function getNbrShoweroom(): ?int
    {
        return $this->nbr_showeroom;
    }

    public function setNbrShoweroom(int $nbr_showeroom): self
    {
        $this->nbr_showeroom = $nbr_showeroom;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getCalendarId(): ?int
    {
        return $this->calendar_id;
    }

    public function setCalendarId(int $calendar_id): self
    {
        $this->calendar_id = $calendar_id;

        return $this;
    }

    public function getEquiments(): ?array
    {
        return $this->equiments;
    }

    public function setEquiments(array $equiments): self
    {
        $this->equiments = $equiments;

        return $this;
    }

    public function getHost(): ?User
    {
        return $this->host;
    }

    public function setHost(?User $host): self
    {
        $this->host = $host;

        return $this;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservation(): Collection
    {
        return $this->reservation;
    }

    public function addReservation(Reservation $reservation): self
    {
        if (!$this->reservation->contains($reservation)) {
            $this->reservation[] = $reservation;
            $reservation->setHouse($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservation->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getHouse() === $this) {
                $reservation->setHouse(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComment(): Collection
    {
        return $this->comment;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comment->contains($comment)) {
            $this->comment[] = $comment;
            $comment->setHouse($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comment->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getHouse() === $this) {
                $comment->setHouse(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Attachment>
     */
    public function getAttachments(): Collection
    {
        return $this->attachments;
    }

    public function addAttachment(Attachment $attachment): self
    {
        if (!$this->attachments->contains($attachment)) {
            $this->attachments[] = $attachment;
            $attachment->setHouse($this);
        }

        return $this;
    }

    public function removeAttachment(Attachment $attachment): self
    {
        if ($this->attachments->removeElement($attachment)) {
            // set the owning side to null (unless already changed)
            if ($attachment->getHouse() === $this) {
                $attachment->setHouse(null);
            }
        }

        return $this;
    }
}
