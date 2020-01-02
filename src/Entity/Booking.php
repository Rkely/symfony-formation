<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\PreUpdate;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BookingRepository")
 * @ORM\HasLifecycleCallbacks()
 * 
 */
class Booking
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="bookings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $booker;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Ad", inversedBy="bookings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ad;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\Date(message = "Attention, la date doit d'arrivée être au bon format !")
     * @Assert\GreaterThan("today",message = "la date doit etre ulterieur a today",groups={"front"})
     * 
     */
    private $startDate;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\Date(message = "Attention, la date de départ doit être au bon format !")
     * @Assert\GreaterThan(propertyPath = "startDate",message = "la date arrive doit etre > a date de depart")
     */
    private $endDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="float")
     */
    private $amount;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * 
     */
    private $comment;
    /**
     * 
     * @ORM\PrePersist
     * @ORM\PreUpdate
     * @return void
     */
    public function prePersist(){
        if(empty($this->createdAt)){
            $this->createdAt = new \DateTime();
        }
        if(empty($this->amount)){
            $this->amount = $this->ad->getPrice() * $this->getDuration();
        }
    }
    public function isBookableDates()
    {
        $notAvailableDays = $this->ad->getNotAvailabledays();

        $bookingDays = $this->getDays();
        // tableau des chaines de caractere de mes joures
        $formatdays = function($day){
            return $day->format('Y-m-d');
        };
        $days = array_map($formatdays,$bookingDays);

        $notAvailable = array_map($formatdays,$notAvailableDays);

        foreach($days as $day){
            if(array_search($day, $notAvailable) !== false) return false;
        }

        return true;
    }
    /**
     * Permet
     *
     * @return array un tableau
     */
    public function getDays()
    {
        $resultat = range(
            $this->getStartDate()->getTimestamp(),
            $this->getEndDate()->getTimestamp(),
            24 * 60 * 60
        );

        $days = array_map(function($dateTimestamp) {
            return  new \DateTime(date('Y-m-d', $dateTimestamp));
        },$resultat);
        return $days;
    }
    public function getDuration(){
        $diff = $this->endDate->diff($this->startDate);
        return $diff->days;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBooker(): ?User
    {
        return $this->booker;
    }

    public function setBooker(?User $booker): self
    {
        $this->booker = $booker;

        return $this;
    }

    public function getAd(): ?Ad
    {
        return $this->ad;
    }

    public function setAd(?Ad $ad): self
    {
        $this->ad = $ad;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }
}
