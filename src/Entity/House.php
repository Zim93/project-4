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

    #[ORM\OneToMany(mappedBy: 'house', targetEntity: Event::class)]
    private $events;

    #[ORM\Column(type: 'string', length: 255)]
    private $country;

    #[ORM\Column(type: 'string', length: 255)]
    private $lat;

    #[ORM\Column(type: 'string', length: 255)]
    private $lng;

    #[ORM\Column(type: 'integer')]
    private $nbr_wc;

    #[ORM\Column(type: 'string', length: 255)]
    private $wc_type;

    #[ORM\Column(type: 'string', length: 255)]
    private $shower_room_type;

    #[ORM\Column(type: 'json', nullable: true)]
    private $confort = [];

 
    #[ORM\Column(type: 'json', nullable: true)]
    private $outside = [];

    #[ORM\OneToMany(mappedBy: 'house', targetEntity: Favorite::class, orphanRemoval: true)]
    private $favorites;

    #[ORM\Column(type: 'float', nullable: true)]
    private $note;

    #[ORM\Column(type: 'float')]
    private $house_area;

    #[ORM\Column(type: 'time')]
    private $arrival_time;

    #[ORM\Column(type: 'time')]
    private $departure_time;

    
    #[ORM\Column(type: 'float')]
    private $guarantee;

    #[ORM\Column(type: 'float', nullable: true)]
    private $breakfast_price;

    #[ORM\Column(type: 'boolean')]
    private $breakfast_dispo;

    #[ORM\Column(type: 'time')]
    private $arrival_time_max;

    #[ORM\OneToMany(mappedBy: 'house', targetEntity: Notification::class, orphanRemoval: true)]
    private $notifications;

    #[ORM\Column(type: 'json', nullable: true)]
    private $strong_points = [];

    public function __construct()
    {
        $this->reservation = new ArrayCollection();
        $this->comment = new ArrayCollection();
        $this->attachments = new ArrayCollection();
        $this->events = new ArrayCollection();
        $this->favorites = new ArrayCollection();
        $this->notifications = new ArrayCollection();
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

    // public function __toString(){
    //     return $this->name;
    // }

    /**
     * @return Collection<int, Event>
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): self
    {
        if (!$this->events->contains($event)) {
            $this->events[] = $event;
            $event->setHouse($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        if ($this->events->removeElement($event)) {
            // set the owning side to null (unless already changed)
            if ($event->getHouse() === $this) {
                $event->setHouse(null);
            }
        }

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getLat(): ?string
    {
        return $this->lat;
    }

    public function setLat(string $lat): self
    {
        $this->lat = $lat;

        return $this;
    }

    public function getLng(): ?string
    {
        return $this->lng;
    }

    public function setLng(string $lng): self
    {
        $this->lng = $lng;

        return $this;
    }

    public function getNbrWc(): ?int
    {
        return $this->nbr_wc;
    }

    public function setNbrWc(int $nbr_wc): self
    {
        $this->nbr_wc = $nbr_wc;

        return $this;
    }

    public function getWcType(): ?string
    {
        return $this->wc_type;
    }

    public function setWcType(string $wc_type): self
    {
        $this->wc_type = $wc_type;

        return $this;
    }

    public function getShowerRoomType(): ?string
    {
        return $this->shower_room_type;
    }

    public function setShowerRoomType(string $shower_room_type): self
    {
        $this->shower_room_type = $shower_room_type;

        return $this;
    }

    public function getConfort(): ?array
    {
        return $this->confort;
    }

    public function setConfort(?array $confort): self
    {
        $this->confort = $confort;

        return $this;
    }


    public function getOutside(): ?array
    {
        return $this->outside;
    }

    public function setOutside(?array $outside): self
    {
        $this->outside = $outside;

        return $this;
    }

    /**
     * @return Collection<int, Favorite>
     */
    public function getFavorites(): Collection
    {
        return $this->favorites;
    }

    public function addFavorite(Favorite $favorite): self
    {
        if (!$this->favorites->contains($favorite)) {
            $this->favorites[] = $favorite;
            $favorite->setHouse($this);
        }

        return $this;
    }

    public function removeFavorite(Favorite $favorite): self
    {
        if ($this->favorites->removeElement($favorite)) {
            // set the owning side to null (unless already changed)
            if ($favorite->getHouse() === $this) {
                $favorite->setHouse(null);
            }
        }

        return $this;
    }

    public function getNote(): ?float
    {
        return $this->note;
    }

    public function setNote(?float $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getHouseArea(): ?float
    {
        return $this->house_area;
    }

    public function setHouseArea(float $house_area): self
    {
        $this->house_area = $house_area;

        return $this;
    }

    public function getArrivalTime(): ?\DateTimeInterface
    {
        return $this->arrival_time;
    }

    public function setArrivalTime(\DateTimeInterface $arrival_time): self
    {
        $this->arrival_time = $arrival_time;

        return $this;
    }

    public function getDepartureTime(): ?\DateTimeInterface
    {
        return $this->departure_time;
    }

    public function setDepartureTime(\DateTimeInterface $departure_time): self
    {
        $this->departure_time = $departure_time;

        return $this;
    }

    public function getGuarantee(): ?float
    {
        return $this->guarantee;
    }

    public function setGuarantee(float $guarantee): self
    {
        $this->guarantee = $guarantee;

        return $this;
    }

    public function getBreakfastPrice(): ?float
    {
        return $this->breakfast_price;
    }

    public function setBreakfastPrice(?float $breakfast_price): self
    {
        $this->breakfast_price = $breakfast_price;

        return $this;
    }

    public function getBreakfastDispo(): ?bool
    {
        return $this->breakfast_dispo;
    }

    public function setBreakfastDispo(bool $breakfast_dispo): self
    {
        $this->breakfast_dispo = $breakfast_dispo;

        return $this;
    }

    public function getArrivalTimeMax(): ?\DateTimeInterface
    {
        return $this->arrival_time_max;
    }

    public function setArrivalTimeMax(\DateTimeInterface $arrival_time_max): self
    {
        $this->arrival_time_max = $arrival_time_max;

        return $this;
    }

    /**
     * @return Collection<int, Notification>
     */
    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    public function addNotification(Notification $notification): self
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications[] = $notification;
            $notification->setHouse($this);
        }

        return $this;
    }

    public function removeNotification(Notification $notification): self
    {
        if ($this->notifications->removeElement($notification)) {
            // set the owning side to null (unless already changed)
            if ($notification->getHouse() === $this) {
                $notification->setHouse(null);
            }
        }

        return $this;
    }

    public function getStrongPoints(): ?array
    {
        return $this->strong_points;
    }

    public function setStrongPoints(?array $strong_points): self
    {
        $this->strong_points = $strong_points;

        return $this;
    }
}
