<?php

namespace App\Entity;

use App\Repository\TestimonialRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TestimonialRepository::class)
 */
class Testimonial
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private $name;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $score;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\OneToOne(targetEntity=Media::class, cascade={"persist", "remove"})
     */
    private $image;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $pdc;

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

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(?int $score): self
    {
        $this->score = $score;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getImage(): ?Media
    {
        return $this->image;
    }

    public function setImage(?Media $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getPdc(): ?string
    {
        return $this->pdc;
    }

    public function setPdc(?string $pdc): self
    {
        $this->pdc = $pdc;

        return $this;
    }
}
