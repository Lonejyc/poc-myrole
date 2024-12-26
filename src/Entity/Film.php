<?php

namespace App\Entity;

use App\Repository\FilmRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: FilmRepository::class)]
class Film
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $rcs = null;

    #[ORM\Column]
    #[Assert\Positive()]
    private ?int $capital = null;

    #[ORM\Column(length: 255)]
    private ?string $address = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getRcs(): ?string
    {
        return $this->rcs;
    }

    public function setRcs(string $rcs): static
    {
        $this->rcs = $rcs;

        return $this;
    }

    public function getCapital(): ?int
    {
        return $this->capital;
    }

    public function setCapital(int $capital): static
    {
        $this->capital = $capital;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }
}
