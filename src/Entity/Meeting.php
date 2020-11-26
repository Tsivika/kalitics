<?php

namespace App\Entity;

use DateTime;
use App\Repository\MeetingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=MeetingRepository::class)
 */
class Meeting
{
    Use TimestampableEntityTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $subject;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $identifiant;

    /**
     * @ORM\Column(type="text")
     */
    private $link;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="meetings")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=Participant::class, mappedBy="meeting", cascade={"persist", "remove"})
     */
    private $participants;

    /**
     * @Assert\PositiveOrZero(message="L'heure ne peu pas être négative.")
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $durationH;

    /**
     * @Assert\PositiveOrZero(message="La minute ne peu pas être négative.")
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $durationM;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $passwordModerator;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $state;

    /**
     * Meeting constructor.
     */
    public function __construct()
    {
        $this->participants = new ArrayCollection();
        $this->status = 0;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getSubject(): ?string
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     *
     * @return $this
     */
    public function setSubject(?string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return $this
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    /**
     * @param \DateTimeInterface $date
     *
     * @return $this
     */
    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string|null $password
     *
     * @return $this
     */
    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getIdentifiant(): ?string
    {
        return $this->identifiant;
    }

    /**
     * @param string $identifiant
     *
     * @return $this
     */
    public function setIdentifiant(string $identifiant): self
    {
        $this->identifiant = $identifiant;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLink(): ?string
    {
        return $this->link;
    }

    /**
     * @param string $link
     *
     * @return $this
     */
    public function setLink(?string $link): self
    {
        $this->link = $link;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User|null $user
     *
     * @return $this
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|Participant[]
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    /**
     * @param Participant $participant
     *
     * @return $this
     */
    public function addParticipant(Participant $participant): self
    {
        if (!$this->participants->contains($participant)) {
            $this->participants[] = $participant;
            $participant->setMeeting($this);
        }

        return $this;
    }

    /**
     * @param Participant $participant
     *
     * @return $this
     */
    public function removeParticipant(Participant $participant): self
    {
        if ($this->participants->contains($participant)) {
            $this->participants->removeElement($participant);
            // set the owning side to null (unless already changed)
            if ($participant->getMeeting() === $this) {
                $participant->setMeeting(null);
            }
        }

        return $this;
    }

    public function getDurationH(): ?int
    {
        return $this->durationH;
    }

    public function setDurationH(?int $durationH): self
    {
        $this->durationH = $durationH;

        return $this;
    }

    public function getDurationM(): ?int
    {
        return $this->durationM;
    }

    public function setDurationM(?int $durationM): self
    {
        $this->durationM = $durationM;

        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getPasswordModerator(): ?string
    {
        return $this->passwordModerator;
    }

    public function setPasswordModerator(?string $passwordModerator): self
    {
        $this->passwordModerator = $passwordModerator;

        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return DateTime|null
     */
    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    public function getState(): ?int
    {
        return $this->state;
    }

    public function setState(?int $state): self
    {
        $this->state = $state;

        return $this;
    }
}
