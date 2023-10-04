<?php

namespace App\Entity;

use App\Repository\DepartmentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DepartmentRepository::class)]
class Department
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 64, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(nullable: true, options: ["default" => 1])]
    private ?int $status = null;

    #[ORM\Column(nullable: true)]
    private ?int $d7Nid = null;

    #[ORM\Column(length: 48, nullable: true)]
    private ?string $loadedFrom = null;

    #[ORM\OneToMany(mappedBy: 'department', targetEntity: Affiliation::class)]
    private Collection $affiliations;

    public function __construct()
    {
        $this->affiliations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getD7Nid(): ?int
    {
        return $this->d7Nid;
    }

    public function setD7Nid(?int $d7Nid): static
    {
        $this->d7Nid = $d7Nid;

        return $this;
    }

    public function getLoadedFrom(): ?string
    {
        return $this->loadedFrom;
    }

    public function setLoadedFrom(string $loadedFrom): static
    {
        $this->loadedFrom = $loadedFrom;

        return $this;
    }

    /**
     * @return Collection<int, Affiliation>
     */
    public function getAffiliations(): Collection
    {
        return $this->affiliations;
    }

    public function addAffiliation(Affiliation $affiliation): static
    {
        if (!$this->affiliations->contains($affiliation)) {
            $this->affiliations->add($affiliation);
            $affiliation->setDepartment($this);
        }

        return $this;
    }

    public function removeAffiliation(Affiliation $affiliation): static
    {
        if ($this->affiliations->removeElement($affiliation)) {
            // set the owning side to null (unless already changed)
            if ($affiliation->getDepartment() === $this) {
                $affiliation->setDepartment(null);
            }
        }

        return $this;
    }
}
