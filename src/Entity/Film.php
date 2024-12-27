<?php

namespace App\Entity;

use App\Repository\FilmRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    /**
     * @var Collection<int, EmployeeGroup>
     */
    #[ORM\OneToMany(targetEntity: EmployeeGroup::class, mappedBy: 'film')]
    private Collection $employee_groups;

    public function __construct()
    {
        $this->employee_groups = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, EmployeeGroup>
     */
    public function getEmployeeGroups(): Collection
    {
        return $this->employee_groups;
    }

    public function addEmployeeGroup(EmployeeGroup $employeeGroup): static
    {
        if (!$this->employee_groups->contains($employeeGroup)) {
            $this->employee_groups->add($employeeGroup);
            $employeeGroup->setFilm($this);
        }

        return $this;
    }

    public function removeEmployeeGroup(EmployeeGroup $employeeGroup): static
    {
        if ($this->employee_groups->removeElement($employeeGroup)) {
            // set the owning side to null (unless already changed)
            if ($employeeGroup->getFilm() === $this) {
                $employeeGroup->setFilm(null);
            }
        }

        return $this;
    }
}
