<?php

namespace App\Entity;

use App\Repository\UserSubscriptionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserSubscriptionRepository::class)
 */
class UserSubscription
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $begin;

    /**
     * @ORM\Column(type="datetime")
     */
    private $end;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="userSubscriptions")
     */
    private $user;

    /**
     * @ORM\Column(type="boolean")
     */
    private $enabled;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $notification;

    /**
     * @ORM\Column(type="boolean")
     */
    private $renew;

    /**
     * @ORM\Column(type="decimal", precision=7, scale=2)
     */
    private $amount;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $stripeToken;

    /**
     * @ORM\ManyToOne(targetEntity=CodePromo::class, inversedBy="userSubscriptions")
     */
    private $codePromo;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBegin(): ?\DateTimeInterface
    {
        return $this->begin;
    }

    public function setBegin(\DateTimeInterface $begin): self
    {
        $this->begin = $begin;

        return $this;
    }

    public function getEnd(): ?\DateTimeInterface
    {
        return $this->end;
    }

    public function setEnd(\DateTimeInterface $end): self
    {
        $this->end = $end;

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

    public function getEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function getNotification(): ?string
    {
        return $this->notification;
    }

    public function setNotification(?string $notification): self
    {
        $this->notification = $notification;

        return $this;
    }

    public function getRenew(): ?bool
    {
        return $this->renew;
    }

    public function setRenew(bool $renew): self
    {
        $this->renew = $renew;

        return $this;
    }

    public function getAmount(): ?string
    {
        return $this->amount;
    }

    public function setAmount(string $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getStripeToken(): ?string
    {
        return $this->stripeToken;
    }

    public function setStripeToken(?string $stripeToken): self
    {
        $this->stripeToken = $stripeToken;

        return $this;
    }

    public function getCodePromo(): ?CodePromo
    {
        return $this->codePromo;
    }

    public function setCodePromo(?CodePromo $codePromo): self
    {
        $this->codePromo = $codePromo;

        return $this;
    }
}
