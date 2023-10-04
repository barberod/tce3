<?php

namespace App\Entity;

use App\Repository\CourseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CourseRepository::class)]
class Course
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 12, nullable: true)]
    private ?string $slug = null;

    #[ORM\Column(length: 9, nullable: true)]
    private ?string $subjectCode = null;

    #[ORM\Column(length: 9, nullable: true)]
    private ?string $courseNumber = null;

    #[ORM\Column(nullable: true, options: ["default" => 1])]
    private ?int $status = null;

    #[ORM\Column(nullable: true)]
    private ?int $d7Nid = null;

    #[ORM\Column(length: 120, nullable: true)]
    private ?string $loadedFrom = null;

    #[ORM\OneToMany(mappedBy: 'finalEquiv1Course', targetEntity: Evaluation::class)]
    private Collection $finalEquiv1Course;

    #[ORM\OneToMany(mappedBy: 'finalEquiv2Course', targetEntity: Evaluation::class)]
    private Collection $finalEquiv2Course;

    #[ORM\OneToMany(mappedBy: 'finalEquiv3Course', targetEntity: Evaluation::class)]
    private Collection $finalEquiv3Course;

    #[ORM\OneToMany(mappedBy: 'finalEquiv4Course', targetEntity: Evaluation::class)]
    private Collection $finalEquiv4Course;

    public function __construct()
    {
        $this->finalEquiv1Course = new ArrayCollection();
        $this->finalEquiv2Course = new ArrayCollection();
        $this->finalEquiv3Course = new ArrayCollection();
        $this->finalEquiv4Course = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getSubjectCode(): ?string
    {
        return $this->subjectCode;
    }

    public function setSubjectCode(?string $subjectCode): static
    {
        $this->subjectCode = $subjectCode;

        return $this;
    }

    public function getCourseNumber(): ?string
    {
        return $this->courseNumber;
    }

    public function setCourseNumber(?string $courseNumber): static
    {
        $this->courseNumber = $courseNumber;

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
    public function getFinalEquiv1Course(): Collection
    {
        return $this->finalEquiv1Course;
    }

    public function addFinalEquiv1Course(Evaluation $finalEquiv1Course): static
    {
        if (!$this->finalEquiv1Course->contains($finalEquiv1Course)) {
            $this->finalEquiv1Course->add($finalEquiv1Course);
            $finalEquiv1Course->setFinalEquiv1Course($this);
        }

        return $this;
    }

    public function removeFinalEquiv1Course(Evaluation $finalEquiv1Course): static
    {
        if ($this->finalEquiv1Course->removeElement($finalEquiv1Course)) {
            // set the owning side to null (unless already changed)
            if ($finalEquiv1Course->getFinalEquiv1Course() === $this) {
                $finalEquiv1Course->setFinalEquiv1Course(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Evaluation>
     */
    public function getFinalEquiv2Course(): Collection
    {
        return $this->finalEquiv2Course;
    }

    public function addFinalEquiv2Course(Evaluation $finalEquiv2Course): static
    {
        if (!$this->finalEquiv2Course->contains($finalEquiv2Course)) {
            $this->finalEquiv2Course->add($finalEquiv2Course);
            $finalEquiv2Course->setFinalEquiv2Course($this);
        }

        return $this;
    }

    public function removeFinalEquiv2Course(Evaluation $finalEquiv2Course): static
    {
        if ($this->finalEquiv2Course->removeElement($finalEquiv2Course)) {
            // set the owning side to null (unless already changed)
            if ($finalEquiv2Course->getFinalEquiv2Course() === $this) {
                $finalEquiv2Course->setFinalEquiv2Course(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Evaluation>
     */
    public function getFinalEquiv3Course(): Collection
    {
        return $this->finalEquiv3Course;
    }

    public function addFinalEquiv3Course(Evaluation $finalEquiv3Course): static
    {
        if (!$this->finalEquiv3Course->contains($finalEquiv3Course)) {
            $this->finalEquiv3Course->add($finalEquiv3Course);
            $finalEquiv3Course->setFinalEquiv3Course($this);
        }

        return $this;
    }

    public function removeFinalEquiv3Course(Evaluation $finalEquiv3Course): static
    {
        if ($this->finalEquiv3Course->removeElement($finalEquiv3Course)) {
            // set the owning side to null (unless already changed)
            if ($finalEquiv3Course->getFinalEquiv3Course() === $this) {
                $finalEquiv3Course->setFinalEquiv3Course(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Evaluation>
     */
    public function getFinalEquiv4Course(): Collection
    {
        return $this->finalEquiv4Course;
    }

    public function addFinalEquiv4Course(Evaluation $finalEquiv4Course): static
    {
        if (!$this->finalEquiv4Course->contains($finalEquiv4Course)) {
            $this->finalEquiv4Course->add($finalEquiv4Course);
            $finalEquiv4Course->setFinalEquiv4Course($this);
        }

        return $this;
    }

    public function removeFinalEquiv4Course(Evaluation $finalEquiv4Course): static
    {
        if ($this->finalEquiv4Course->removeElement($finalEquiv4Course)) {
            // set the owning side to null (unless already changed)
            if ($finalEquiv4Course->getFinalEquiv4Course() === $this) {
                $finalEquiv4Course->setFinalEquiv4Course(null);
            }
        }

        return $this;
    }
}
