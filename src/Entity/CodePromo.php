<?php

namespace App\Entity;

use App\Entity\TimestampableEntityTrait;
use App\Repository\CodePromoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CodePromoRepository::class)
 */
class CodePromo
{
    Use TimestampableEntityTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $reduction;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status;

    /**
     * @ORM\OneToMany(targetEntity=UserSubscription::class, mappedBy="codePromo")
     */
    private $userSubscriptions;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="codePromos")
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $code;

    /**
     * CodePromo constructor.
     */
    public function __construct()
    {
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
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getReduction(): ?int
    {
        return $this->reduction;
    }

    /**
     * @param int $reduction
     * @return $this
     */
    public function setReduction(int $reduction): self
    {
        $this->reduction = $reduction;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getStatus(): ?bool
    {
        return $this->status;
    }

    /**
     * @param bool $status
     * @return $this
     */
    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection|UserSubscription[]
     */
    public function getUserSubscriptions(): Collection
    {
        return $this->userSubscriptions;
    }

    /**
     * @param UserSubscription $userSubscription
     * @return $this
     */
    public function addUserSubscription(UserSubscription $userSubscription): self
    {
        if (!$this->userSubscriptions->contains($userSubscription)) {
            $this->userSubscriptions[] = $userSubscription;
            $userSubscription->setCodePromo($this);
        }

        return $this;
    }

    /**
     * @param UserSubscription $userSubscription
     * @return $this
     */
    public function removeUserSubscription(UserSubscription $userSubscription): self
    {
        if ($this->userSubscriptions->contains($userSubscription)) {
            $this->userSubscriptions->removeElement($userSubscription);
            // set the owning side to null (unless already changed)
            if ($userSubscription->getCodePromo() === $this) {
                $userSubscription->setCodePromo(null);
            }
        }

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
     * @return $this
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @param string $code
     * @return $this
     */
    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }
}
