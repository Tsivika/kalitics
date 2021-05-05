<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"registerNumber"}, message="Il existe déjà un compte avec cette matricule")
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastname;

    /**
     * @ORM\Column(type="integer", unique=true)
     */
    private $registerNumber;

    /**
     * @ORM\ManyToOne(targetEntity=Chantier::class, inversedBy="users")
     */
    private $chantier;

    /**
     * @ORM\OneToMany(targetEntity=Pointing::class, mappedBy="user")
     */
    private $pointings;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->pointings = new ArrayCollection();
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
    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    /**
     * @param string $firstname
     * @return $this
     */
    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    /**
     * @param string $lastname
     * @return $this
     */
    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getRegisterNumber(): ?int
    {
        return $this->registerNumber;
    }

    /**
     * @param int $registerNumber
     * @return $this
     */
    public function setRegisterNumber(int $registerNumber): self
    {
        $this->registerNumber = $registerNumber;

        return $this;
    }

    /**
     * @return Chantier|null
     */
    public function getChantier(): ?Chantier
    {
        return $this->chantier;
    }

    /**
     * @param Chantier|null $chantier
     * @return $this
     */
    public function setChantier(?Chantier $chantier): self
    {
        $this->chantier = $chantier;

        return $this;
    }

    /**
     * @return Collection|Pointing[]
     */
    public function getPointings(): Collection
    {
        return $this->pointings;
    }

    /**
     * @param Pointing $pointing
     * @return $this
     */
    public function addPointing(Pointing $pointing): self
    {
        if (!$this->pointings->contains($pointing)) {
            $this->pointings[] = $pointing;
            $pointing->setUser($this);
        }

        return $this;
    }

    /**
     * @param Pointing $pointing
     * @return $this
     */
    public function removePointing(Pointing $pointing): self
    {
        if ($this->pointings->removeElement($pointing)) {
            // set the owning side to null (unless already changed)
            if ($pointing->getUser() === $this) {
                $pointing->setUser(null);
            }
        }

        return $this;
    }
}
