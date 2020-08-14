<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
class Category
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
     * @ORM\OneToMany(targetEntity=Guide::class, mappedBy="category")
     */
    private $guides;

    /**
     * Category constructor.
     */
    public function __construct()
    {
        $this->guides = new ArrayCollection();
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
     * @return Collection|Guide[]
     */
    public function getGuides(): Collection
    {
        return $this->guides;
    }

    /**
     * @param Guide $guide
     *
     * @return $this
     */
    public function addGuide(Guide $guide): self
    {
        if (!$this->guides->contains($guide)) {
            $this->guides[] = $guide;
            $guide->setCategory($this);
        }

        return $this;
    }

    /**
     * @param Guide $guide
     *
     * @return $this
     */
    public function removeGuide(Guide $guide): self
    {
        if ($this->guides->contains($guide)) {
            $this->guides->removeElement($guide);
            // set the owning side to null (unless already changed)
            if ($guide->getCategory() === $this) {
                $guide->setCategory(null);
            }
        }

        return $this;
    }
}
