<?php

namespace App\Entity;

use App\Repository\UserRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface
{
    final public const ROLE_ADMIN = 'ROLE_ADMIN';
    final public const ROLE_MANAGER = 'ROLE_MANAGER';
    final public const ROLE_COORDINATOR = 'ROLE_COORDINATOR';
    final public const ROLE_OBSERVER = 'ROLE_OBSERVER';
    final public const ROLE_ASSIGNEE = 'ROLE_ASSIGNEE';
    final public const ROLE_REQUESTER = 'ROLE_REQUESTER';
    final public const ROLE_USER= 'ROLE_USER';

    final public const ROLE_FACULTY = 'ROLE_FACULTY';
    final public const ROLE_STAFF = 'ROLE_STAFF';
    final public const ROLE_GSAPP = 'ROLE_GSAPP';
    final public const ROLE_UGAPP = 'ROLE_UGAPP';
    final public const ROLE_STUDENT = 'ROLE_STUDENT';

    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $username = null;

    #[ORM\Column(length: 9, nullable: true)]
    private ?string $orgID = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $displayName = null;

    #[ORM\Column(length: 180, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(length: 24, nullable: true, options: ["default" => "member"])]
    private ?string $category = 'member';

    #[ORM\Column(nullable: true, options: ["default" => 1])]
    private ?int $status = 1;

    #[ORM\Column(nullable: true, options: ["default" => 0])]
    private ?int $frozen = 0;

    #[ORM\Column(length: 48, nullable: true)]
    private ?string $loadedFrom = null;

    #[Gedmo\Timestampable(on: 'create')]
    #[ORM\Column(nullable: true)]
    private ?DateTime $created = null;

    #[Gedmo\Timestampable(on: 'change', field: ['orgID', 'displayName', 'email', 'category', 'status', 'frozen', 'created'])]
    #[ORM\Column(nullable: true)]
    private ?DateTime $updated = null;

    #[ORM\Column(nullable: true)]
    private array $roles = [];

    #[ORM\Column(nullable: true)]
    private ?int $d7Uid = 0;

    #[ORM\OneToMany(mappedBy: 'requester', targetEntity: Evaluation::class)]
    private Collection $evaluations;

    #[ORM\OneToMany(mappedBy: 'assignee', targetEntity: Evaluation::class)]
    private Collection $assignedEvaluations;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Note::class)]
    private Collection $notes;

    #[ORM\OneToMany(mappedBy: 'facstaff', targetEntity: Affiliation::class)]
    private Collection $affiliations;

    public function __construct()
    {
        $this->evaluations = new ArrayCollection();
        $this->assignedEvaluations = new ArrayCollection();
        $this->notes = new ArrayCollection();
        $this->affiliations = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getOrgID(): ?string
    {
        return $this->orgID;
    }

    public function setOrgID(string $orgID): static
    {
        $this->orgID = $orgID;

        return $this;
    }

    public function getDisplayName(): ?string
    {
        return $this->displayName;
    }

    public function setDisplayName(string $displayName): static
    {
        $this->displayName = $displayName;

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

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): static
    {
        $this->category = $category;

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

    public function getFrozen(): ?int
    {
        return $this->frozen;
    }

    public function setFrozen(int $frozen): static
    {
        $this->frozen = $frozen;

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

    public function getCreated()
    {
        return $this->created;
    }

    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        // $roles = $this->roles;
        $roles = $this->enforceRoleHierarchy($this->roles);

        // guarantees that a user always has at least one role for security
        if (empty($roles)) {
            $roles[] = self::ROLE_USER;
        }
        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;
        return $this;
    }

    public function getD7Uid(): ?int
    {
        return $this->d7Uid;
    }

    public function setD7Uid(int $d7Uid): static
    {
        $this->d7Uid = $d7Uid;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * Used for mimicing a CasUser with data stored inside
     * an "attributes" attribute
     */
    public function attributes() {
        $roles = $this->getRoles();
        array_push($roles, 'ROLE_USER');

        $profile = [
            'id' => $this->getId(),
            'un' => $this->getUsername(),
            'org_id' => $this->getOrgID(),
            'dn' => $this->getDisplayName(),
            'email' => $this->getEmail(),
            'category' => $this->getCategory(),
            'status' => $this->getStatus(),
            'frozen' => $this->getFrozen(),
            'loaded_from' => $this->getLoadedFrom(),
            'created' => $this->getCreated(),
            'updated' => $this->getUpdated(),
            'roles' => $roles,
        ];

        return array('profile' => $profile);
    }

    /**
     * Returns an array of roles. Used to ensure user has not only their necessary
     * highest role but all the roles beneath that role, too.
     * 
     * @param array An array of roles already determined
     * @return array An array of the full set of roles for the user
     */
    public function enforceRoleHierarchy(array $initialRoles): array {
        $roleHierarchy = [  'ROLE_ADMIN',
                            'ROLE_MANAGER',
                            'ROLE_COORDINATOR',
                            'ROLE_ASSIGNEE',
                            'ROLE_OBSERVER',
                            'ROLE_REQUESTER'    ];

        for ($i = 0; $i < count($roleHierarchy); $i++) {
            if (in_array($roleHierarchy[$i], $initialRoles)) {
                break;
            }
        }

        $finalRoles = array();
        for ($j = $i; $j < count($roleHierarchy); $j++) {
            $finalRoles[] = $roleHierarchy[$j];
        }
        return $finalRoles;
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
            $evaluation->setRequester($this);
        }

        return $this;
    }

    public function removeEvaluation(Evaluation $evaluation): static
    {
        if ($this->evaluations->removeElement($evaluation)) {
            // set the owning side to null (unless already changed)
            if ($evaluation->getRequester() === $this) {
                $evaluation->setRequester(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Evaluation>
     */
    public function getAssignedEvaluations(): Collection
    {
        return $this->assignedEvaluations;
    }

    public function addAssignedEvaluation(Evaluation $assignedEvaluation): static
    {
        if (!$this->assignedEvaluations->contains($assignedEvaluation)) {
            $this->assignedEvaluations->add($assignedEvaluation);
            $assignedEvaluation->setAssignee($this);
        }

        return $this;
    }

    public function removeAssignedEvaluation(Evaluation $assignedEvaluation): static
    {
        if ($this->assignedEvaluations->removeElement($assignedEvaluation)) {
            // set the owning side to null (unless already changed)
            if ($assignedEvaluation->getAssignee() === $this) {
                $assignedEvaluation->setAssignee(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Note>
     */
    public function getNotes(): Collection
    {
        return $this->notes;
    }

    public function addNote(Note $note): static
    {
        if (!$this->notes->contains($note)) {
            $this->notes->add($note);
            $note->setAuthor($this);
        }

        return $this;
    }

    public function removeNote(Note $note): static
    {
        if ($this->notes->removeElement($note)) {
            // set the owning side to null (unless already changed)
            if ($note->getAuthor() === $this) {
                $note->setAuthor(null);
            }
        }

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
            $affiliation->setFacstaff($this);
        }

        return $this;
    }

    public function removeAffiliation(Affiliation $affiliation): static
    {
        if ($this->affiliations->removeElement($affiliation)) {
            // set the owning side to null (unless already changed)
            if ($affiliation->getFacstaff() === $this) {
                $affiliation->setFacstaff(null);
            }
        }

        return $this;
    }
}
