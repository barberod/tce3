<?php

namespace App\Entity;

use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface
{
    final public const ROLE_USER = 'ROLE_USER';
    final public const ROLE_UGAPP = 'ROLE_UGAPP';
    final public const ROLE_GSAPP = 'ROLE_GSAPP';
    final public const ROLE_STUDENT = 'ROLE_STUDENT';
    final public const ROLE_STAFF = 'ROLE_STAFF';
    final public const ROLE_FACULTY = 'ROLE_FACULTY';
    final public const ROLE_ADMIN = 'ROLE_ADMIN';

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

    #[ORM\Column(length: 24, nullable: true)]
    private ?string $category = 'member';

    #[ORM\Column(nullable: true)]
    private ?int $status = 1;

    #[ORM\Column(nullable: true)]
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
        $roles = $this->roles;
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

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /*
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

}
