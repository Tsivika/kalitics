<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVerified = false;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private $firstname;

    /**
     * @ORM\OneToOne(targetEntity=Media::class, cascade={"persist", "remove"})
     */
    private $images;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $language;

    /**
     * @ORM\OneToOne(targetEntity=Subscription::class, cascade={"persist", "remove"})
     */
    private $subscription;

    /**
     * @ORM\OneToMany(targetEntity=Meeting::class, mappedBy="user", cascade={"remove"})
     */
    private $meetings;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $pdp;

    /**
     * @ORM\ManyToOne(targetEntity=Subscription::class, inversedBy="users")
     */
    private $subscriptionUser;

    /**
     * @ORM\OneToMany(targetEntity=UserSubscription::class, mappedBy="user")
     */
    private $userSubscriptions;

    /**
     * @ORM\OneToMany(targetEntity=CodePromo::class, mappedBy="user")
     */
    private $codePromos;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $entreprise;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $stripeToken;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->meetings = new ArrayCollection();
        $this->userSubscriptions = new ArrayCollection();
        $this->codePromos = new ArrayCollection();
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
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return $this
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param array $roles
     *
     * @return $this
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    /**
     * @param string $password
     *
     * @return $this
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return bool
     */
    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    /**
     * @param bool $isVerified
     *
     * @return $this
     */
    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
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
     *
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
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
     *
     * @return $this
     */
    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * @return Media|null
     */
    public function getImages(): ?Media
    {
        return $this->images;
    }

    /**
     * @param Media|null $images
     *
     * @return $this
     */
    public function setImages(?Media $images): self
    {
        $this->images = $images;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLanguage(): ?string
    {
        return $this->language;
    }

    /**
     * @param string $language
     *
     * @return $this
     */
    public function setLanguage(string $language): self
    {
        $this->language = $language;

        return $this;
    }

    /**
     * @return Subscription|null
     */
    public function getSubscription(): ?Subscription
    {
        return $this->subscription;
    }

    /**
     * @param Subscription|null $subscription
     *
     * @return $this
     */
    public function setSubscription(?Subscription $subscription): self
    {
        $this->subscription = $subscription;

        return $this;
    }

    /**
     * @return Collection|Meeting[]
     */
    public function getMeetings(): Collection
    {
        return $this->meetings;
    }

    /**
     * @param Meeting $meeting
     *
     * @return $this
     */
    public function addMeeting(Meeting $meeting): self
    {
        if (!$this->meetings->contains($meeting)) {
            $this->meetings[] = $meeting;
            $meeting->setUser($this);
        }

        return $this;
    }

    /**
     * @param Meeting $meeting
     *
     * @return $this
     */
    public function removeMeeting(Meeting $meeting): self
    {
        if ($this->meetings->contains($meeting)) {
            $this->meetings->removeElement($meeting);
            // set the owning side to null (unless already changed)
            if ($meeting->getUser() === $this) {
                $meeting->setUser(null);
            }
        }

        return $this;
    }

    public function getPdp(): ?string
    {
        return $this->pdp;
    }

    public function setPdp(?string $pdp): self
    {
        $this->pdp = $pdp;

        return $this;
    }

    public function getSubscriptionUser(): ?Subscription
    {
        return $this->subscriptionUser;
    }

    public function setSubscriptionUser(?Subscription $subscriptionUser): self
    {
        $this->subscriptionUser = $subscriptionUser;

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
            $userSubscription->setUser($this);
        }

        return $this;
    }

    public function removeUserSubscription(UserSubscription $userSubscription): self
    {
        if ($this->userSubscriptions->contains($userSubscription)) {
            $this->userSubscriptions->removeElement($userSubscription);
            // set the owning side to null (unless already changed)
            if ($userSubscription->getUser() === $this) {
                $userSubscription->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|CodePromo[]
     */
    public function getCodePromos(): Collection
    {
        return $this->codePromos;
    }

    public function addCodePromo(CodePromo $codePromo): self
    {
        if (!$this->codePromos->contains($codePromo)) {
            $this->codePromos[] = $codePromo;
            $codePromo->setUser($this);
        }

        return $this;
    }

    public function removeCodePromo(CodePromo $codePromo): self
    {
        if ($this->codePromos->contains($codePromo)) {
            $this->codePromos->removeElement($codePromo);
            // set the owning side to null (unless already changed)
            if ($codePromo->getUser() === $this) {
                $codePromo->setUser(null);
            }
        }

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getEntreprise(): ?string
    {
        return $this->entreprise;
    }

    public function setEntreprise(?string $entreprise): self
    {
        $this->entreprise = $entreprise;

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
}
