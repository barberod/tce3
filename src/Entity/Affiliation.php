<?php

namespace App\Entity;

use App\Repository\AffiliationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AffiliationRepository::class)]
class Affiliation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'affiliations')]
    private ?User $facstaff = null;

    #[ORM\ManyToOne(inversedBy: 'affiliations')]
    private ?Department $department = null;

    #[ORM\Column(length: 48, nullable: true)]
    private ?string $loadedFrom = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFacstaff(): ?User
    {
        return $this->facstaff;
    }

    public function setFacstaff(?User $facstaff): static
    {
        $this->facstaff = $facstaff;

        return $this;
    }

    public function getDepartment(): ?Department
    {
        return $this->department;
    }

    public function setDepartment(?Department $department): static
    {
        $this->department = $department;

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
}
