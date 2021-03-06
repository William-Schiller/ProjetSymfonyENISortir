<?php

namespace App\Entity;

use App\Repository\TripRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=TripRepository::class)
 */
class Trip
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $name;

    /**
     * @Assert\Range(
     *     min="now +47 hours"
     * )
     * @ORM\Column(type="datetime")
     */
    private $dateStart;

    /**
     * @ORM\Column(type="integer")
     */
    private $duration;

    /**
     * @Assert\Range(
     *     min="now +23 hours",
     * )
     * @ORM\Column(type="datetime")
     */
    private $dateLimitInscription;

    /**
     * @Assert\Range(
     *     min=2,
     *     minMessage="La sortie doit comprendre au moins 2 participant"
     * )
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nbMaxRegistration;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $informationTrip;

    /**
     * @ORM\ManyToOne(targetEntity=Campus::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $campus;

    /**
     * @ORM\ManyToOne(targetEntity=Status::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity=Adress::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $adress;

    /**
     * @ORM\ManyToOne(targetEntity=Participant::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $promoter;

    /**
     * @ORM\OneToMany(targetEntity=Inscription::class, mappedBy="trip")
     * @ORM\JoinColumn(nullable=false)
     */
    private $inscription;

    /**
     * @return mixed
     */
    public function getInscription()
    {
        return $this->inscription;
    }

    /**
     * @param mixed $inscription
     */
    public function setInscription($inscription): void
    {
        $this->inscription = $inscription;
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

    public function getDateStart(): ?\DateTimeInterface
    {
        return $this->dateStart;
    }

    public function setDateStart(\DateTimeInterface $dateStart): self
    {
        $this->dateStart = $dateStart;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getDateLimitInscription(): ?\DateTimeInterface
    {
        return $this->dateLimitInscription;
    }

    public function setDateLimitInscription(\DateTimeInterface $dateLimitInscription): self
    {
        $this->dateLimitInscription = $dateLimitInscription;

        return $this;
    }

    public function getNbMaxRegistration(): ?int
    {
        return $this->nbMaxRegistration;
    }

    public function setNbMaxRegistration(?int $nbMaxRegistration): self
    {
        $this->nbMaxRegistration = $nbMaxRegistration;

        return $this;
    }

    public function getInformationTrip(): ?string
    {
        return $this->informationTrip;
    }

    public function setInformationTrip(?string $informationTrip): self
    {
        $this->informationTrip = $informationTrip;

        return $this;
    }

    public function getCampus(): ?Campus
    {
        return $this->campus;
    }

    public function setCampus(?Campus $campus): self
    {
        $this->campus = $campus;

        return $this;
    }

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(?Status $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getAdress(): ?Adress
    {
        return $this->adress;
    }

    public function setAdress(?Adress $adress): self
    {
        $this->adress = $adress;

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }

    public function getPromoter(): ?Participant
    {
        return $this->promoter;
    }

    public function setPromoter(?Participant $promoter): self
    {
        $this->promoter = $promoter;

        return $this;
    }


}
