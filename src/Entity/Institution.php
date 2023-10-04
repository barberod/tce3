<?php

namespace App\Entity;

use App\Repository\InstitutionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InstitutionRepository::class)]
class Institution
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 9, nullable: true)]
    private ?string $dapipID = null;

    #[ORM\Column(length: 9, nullable: true)]
    private ?string $usgID = null;

    #[ORM\Column(length: 90, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(length: 2, nullable: true)]
    private ?string $state = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $address = null;

    #[ORM\Column(options: ["default" => 1])]
    private ?int $status = null;

    #[ORM\Column(nullable: true)]
    private ?int $d7Nid = null;

    #[ORM\Column(length: 48, nullable: true)]
    private ?string $loadedFrom = null;

    #[ORM\OneToMany(mappedBy: 'institution', targetEntity: Evaluation::class)]
    private Collection $evaluations;

    public function __construct()
    {
        $this->evaluations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDapipID(): ?string
    {
        return $this->dapipID;
    }

    public function setDapipID(?string $dapipID): static
    {
        $this->dapipID = $dapipID;

        return $this;
    }

    public function getUsgID(): ?string
    {
        return $this->usgID;
    }

    public function setUsgID(?string $usgID): static
    {
        $this->usgID = $usgID;

        return $this;
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

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(?string $state): static
    {
        $this->state = $state;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): static
    {
        $this->address = $address;

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
     * @return Collection<int, Evaluation>
     */
    public function getEvaluations(): Collection
    {
        return $this->evaluations;
    }

    public function addEvaluation(Evaluation $evaluation): static
    {
        if (!$this->evaluations->contains($evaluation)) {
            $this->evaluations->add($evaluation);
            $evaluation->setInstitution($this);
        }

        return $this;
    }

    public function removeEvaluation(Evaluation $evaluation): static
    {
        if ($this->evaluations->removeElement($evaluation)) {
            // set the owning side to null (unless already changed)
            if ($evaluation->getInstitution() === $this) {
                $evaluation->setInstitution(null);
            }
        }

        return $this;
    }
}
