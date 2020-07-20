<?php

namespace App\Entity;

use App\Repository\SubscriptionRepository;
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
     * @ORM\Column(type="integer")
     */
    private $duration;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private $mode;

    /**
     * @ORM\Column(type="integer")
     */
    private $duration_meeting;

    /**
     * @ORM\Column(type="integer")
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
     * @ORM\Column(type="integer")
     */
    private $price;

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
     * @return int|null
     */
    public function getDuration(): ?int
    {
        return $this->duration;
    }

    /**
     * @param int $duration
     *
     * @return $this
     */
    public function setDuration(int $duration): self
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
    public function setMode(string $mode): self
    {
        $this->mode = $mode;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getDurationMeeting(): ?int
    {
        return $this->duration_meeting;
    }

    /**
     * @param int $duration_meeting
     *
     * @return $this
     */
    public function setDurationMeeting(int $duration_meeting): self
    {
        $this->duration_meeting = $duration_meeting;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getNumberParticipant(): ?int
    {
        return $this->numberParticipant;
    }

    /**
     * @param int $numberParticipant
     *
     * @return $this
     */
    public function setNumberParticipant(int $numberParticipant): self
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
    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }
}
