<?php

namespace App\Entity;

use App\Repository\TrailRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: TrailRepository::class)]
class Trail
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'trails')]
    private ?Evaluation $evaluation = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $body = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $bodyAnon = null;

    #[Gedmo\Timestampable(on: 'create')]
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $created = null;

    #[ORM\Column(nullable: true)]
    private ?int $d7Nid = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEvaluation(): ?Evaluation
    {
        return $this->evaluation;
    }

    public function setEvaluation(?Evaluation $evaluation): static
    {
        $this->evaluation = $evaluation;

        return $this;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(?string $body): static
    {
        $this->body = $body;

        return $this;
    }

    public function getBodyAnon(): ?string
    {
        return $this->bodyAnon;
    }

    public function setBodyAnon(?string $bodyAnon): static
    {
        $this->bodyAnon = $bodyAnon;

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(?\DateTimeInterface $created): static
    {
        $this->created = $created;

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
}
