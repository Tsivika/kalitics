<?php

namespace App\Entity;

use App\Repository\ParameterRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ParameterRepository::class)
 */
class Parameter
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $videoModerator;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $videoParticipant;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $phonePwd;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $soundParticipant;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $messagePublic;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $annotationParticipant;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $boardParticipant;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $recordAuto;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $feedback;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $meetingReminder;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $personalMailbox;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $formatHtmlMail;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="parameters")
     */
    private $user;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $meetingCanceled;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVideoModerator(): ?bool
    {
        return $this->videoModerator;
    }

    public function setVideoModerator(?bool $videoModerator): self
    {
        $this->videoModerator = $videoModerator;

        return $this;
    }

    public function getVideoParticipant(): ?bool
    {
        return $this->videoParticipant;
    }

    public function setVideoParticipant(?bool $videoParticipant): self
    {
        $this->videoParticipant = $videoParticipant;

        return $this;
    }

    public function getPhonePwd(): ?bool
    {
        return $this->phonePwd;
    }

    public function setPhonePwd(?bool $phonePwd): self
    {
        $this->phonePwd = $phonePwd;

        return $this;
    }

    public function getSoundParticipant(): ?bool
    {
        return $this->soundParticipant;
    }

    public function setSoundParticipant(?bool $soundParticipant): self
    {
        $this->soundParticipant = $soundParticipant;

        return $this;
    }

    public function getMessagePublic(): ?bool
    {
        return $this->messagePublic;
    }

    public function setMessagePublic(?bool $messagePublic): self
    {
        $this->messagePublic = $messagePublic;

        return $this;
    }

    public function getAnnotationParticipant(): ?bool
    {
        return $this->annotationParticipant;
    }

    public function setAnnotationParticipant(?bool $annotationParticipant): self
    {
        $this->annotationParticipant = $annotationParticipant;

        return $this;
    }

    public function getBoardParticipant(): ?bool
    {
        return $this->boardParticipant;
    }

    public function setBoardParticipant(?bool $boardParticipant): self
    {
        $this->boardParticipant = $boardParticipant;

        return $this;
    }

    public function getRecordAuto(): ?bool
    {
        return $this->recordAuto;
    }

    public function setRecordAuto(?bool $recordAuto): self
    {
        $this->recordAuto = $recordAuto;

        return $this;
    }

    public function getFeedback(): ?bool
    {
        return $this->feedback;
    }

    public function setFeedback(?bool $feedback): self
    {
        $this->feedback = $feedback;

        return $this;
    }

    public function getMeetingReminder(): ?bool
    {
        return $this->meetingReminder;
    }

    public function setMeetingReminder(?bool $meetingReminder): self
    {
        $this->meetingReminder = $meetingReminder;

        return $this;
    }

    public function getPersonalMailbox(): ?bool
    {
        return $this->personalMailbox;
    }

    public function setPersonalMailbox(?bool $personalMailbox): self
    {
        $this->personalMailbox = $personalMailbox;

        return $this;
    }

    public function getFormatHtmlMail(): ?bool
    {
        return $this->formatHtmlMail;
    }

    public function setFormatHtmlMail(?bool $formatHtmlMail): self
    {
        $this->formatHtmlMail = $formatHtmlMail;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getMeetingCanceled(): ?bool
    {
        return $this->meetingCanceled;
    }

    public function setMeetingCanceled(?bool $meetingCanceled): self
    {
        $this->meetingCanceled = $meetingCanceled;

        return $this;
    }
}
