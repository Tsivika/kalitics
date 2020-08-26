<?php

namespace App\Entity;

use App\Repository\SubscriptionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SubscriptionRepository::class)
 */
class Subscription
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private $duration;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private $mode;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private $duration_meeting;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $numberParticipant;

    /**
     * @ORM\Column(type="boolean")
     */
    private $messagingInstant;

    /**
     * @ORM\Column(type="boolean")
     */
    private $screenSharing;

    /**
     * @ORM\Column(type="boolean")
     */
    private $recordingMeeting;

    /**
     * @ORM\Column(type="boolean")
     */
    private $reminderMeeting;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $price;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="subscriptionUser")
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity=UserSubscription::class, mappedBy="subscription")
     */
    private $userSubscriptions;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->userSubscriptions = new ArrayCollection();
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
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return $this
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDuration(): ?string
    {
        return $this->duration;
    }

    /**
     * @param string $duration
     *
     * @return $this
     */
    public function setDuration(?string $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getMode(): ?string
    {
        return $this->mode;
    }

    /**
     * @param string $mode
     *
     * @return $this
     */
    public function setMode(?string $mode): self
    {
        $this->mode = $mode;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDurationMeeting(): ?string
    {
        return $this->duration_meeting;
    }

    /**
     * @param string $duration_meeting
     *
     * @return $this
     */
    public function setDurationMeeting(?string $duration_meeting): self
    {
        $this->duration_meeting = $duration_meeting;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getNumberParticipant(): ?string
    {
        return $this->numberParticipant;
    }

    /**
     * @param string $numberParticipant
     *
     * @return $this
     */
    public function setNumberParticipant(?string $numberParticipant): self
    {
        $this->numberParticipant = $numberParticipant;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getMessagingInstant(): ?bool
    {
        return $this->messagingInstant;
    }

    /**
     * @param bool $messagingInstant
     *
     * @return $this
     */
    public function setMessagingInstant(bool $messagingInstant): self
    {
        $this->messagingInstant = $messagingInstant;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getScreenSharing(): ?bool
    {
        return $this->screenSharing;
    }

    /**
     * @param bool $screenSharing
     *
     * @return $this
     */
    public function setScreenSharing(bool $screenSharing): self
    {
        $this->screenSharing = $screenSharing;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getRecordingMeeting(): ?bool
    {
        return $this->recordingMeeting;
    }

    /**
     * @param bool $recordingMeeting
     *
     * @return $this
     */
    public function setRecordingMeeting(bool $recordingMeeting): self
    {
        $this->recordingMeeting = $recordingMeeting;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getReminderMeeting(): ?bool
    {
        return $this->reminderMeeting;
    }

    /**
     * @param bool $reminderMeeting
     *
     * @return $this
     */
    public function setReminderMeeting(bool $reminderMeeting): self
    {
        $this->reminderMeeting = $reminderMeeting;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getPrice(): ?int
    {
        return $this->price;
    }

    /**
     * @param int $price
     *
     * @return $this
     */
    public function setPrice(?int $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setSubscriptionUser($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getSubscriptionUser() === $this) {
                $user->setSubscriptionUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|UserSubscription[]
     */
    public function getUserSubscriptions(): Collection
    {
        return $this->userSubscriptions;
    }

    public function addUserSubscription(UserSubscription $userSubscription): self
    {
        if (!$this->userSubscriptions->contains($userSubscription)) {
            $this->userSubscriptions[] = $userSubscription;
            $userSubscription->setSubscription($this);
        }

        return $this;
    }

    public function removeUserSubscription(UserSubscription $userSubscription): self
    {
        if ($this->userSubscriptions->contains($userSubscription)) {
            $this->userSubscriptions->removeElement($userSubscription);
            // set the owning side to null (unless already changed)
            if ($userSubscription->getSubscription() === $this) {
                $userSubscription->setSubscription(null);
            }
        }

        return $this;
    }
}
