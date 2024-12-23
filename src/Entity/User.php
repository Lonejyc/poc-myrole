<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity('email')]
#[UniqueEntity('nss')]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    private ?string $lastname = null;

    #[ORM\Column]
    private ?int $age = null;

    #[ORM\Column]
    private ?bool $sex = null;

    #[ORM\Column(length: 255)]
    #[Assert\Regex('/[^@ \t\r\n]+@[^@ \t\r\n]+\.[^@ \t\r\n]+/', message: 'Email Invalide')]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $adress = null;

    #[ORM\Column(length: 255)]
    #[Assert\Regex('/^[12]\d{2}(0[1-9]|1[0-2])(0[1-9]|[12]\d|3[01])\d{6}$/', message: 'Numéro de sécurité sociale Invalide')]
    private ?string $nss = null;

    #[ORM\Column(length: 255)]
    #[Assert\Regex('/^(\+33\s?[1-9](?:\s?\d{2}){4}|0[1-9](?:\s?\d{2}){4})$/', message: 'Numéro de téléphone Invalide')]
    private ?string $phone = null;

    #[ORM\Column(length: 255)]
    private ?string $permit = null;

    #[ORM\Column]
    private ?bool $intermittent = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(int $age): static
    {
        $this->age = $age;

        return $this;
    }

    public function isSex(): ?bool
    {
        return $this->sex;
    }

    public function setSex(bool $sex): static
    {
        $this->sex = $sex;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(string $adress): static
    {
        $this->adress = $adress;

        return $this;
    }

    public function getNss(): ?string
    {
        return $this->nss;
    }

    public function setNss(string $nss): static
    {
        $this->nss = $nss;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getPermit(): ?string
    {
        return $this->permit;
    }

    public function setPermit(string $permit): static
    {
        $this->permit = $permit;

        return $this;
    }

    public function isIntermittent(): ?bool
    {
        return $this->intermittent;
    }

    public function setIntermittent(bool $intermittent): static
    {
        $this->intermittent = $intermittent;

        return $this;
    }
}
