<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\UserTypesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource]
#[ORM\Entity(repositoryClass: UserTypesRepository::class)]
#[ORM\HasLifecycleCallbacks] // Indispensable pour la date auto
class UserTypes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?\DateTime $creation_date = null;

    #[ORM\Column]
    private ?bool $canAcceptProject = false; // Valeur par défaut pour éviter le "Null"

    /**
     * @var Collection<int, Users>
     */
    #[ORM\OneToMany(targetEntity: Users::class, mappedBy: 'user_type')]
    private Collection $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->creation_date = new \DateTime(); // Date par défaut à la création
    }

    // --- MÉTHODES MAGIQUES ---

    public function __toString(): string
    {
        return (string) $this->name;
    }

    #[ORM\PrePersist]
    public function setCreationDateValue(): void
    {
        if ($this->creation_date === null) {
            $this->creation_date = new \DateTime();
        }
    }

    // --- GETTERS & SETTERS ---

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

    public function getCreationDate(): ?\DateTime
    {
        return $this->creation_date;
    }

    public function setCreationDate(\DateTime $creation_date): static
    {
        $this->creation_date = $creation_date;
        return $this;
    }

    public function isCanAcceptProject(): ?bool
    {
        return $this->canAcceptProject;
    }

    public function setCanAcceptProject(bool $canAcceptProject): static
    {
        $this->canAcceptProject = $canAcceptProject;
        return $this;
    }

    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(Users $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->setUserType($this);
        }
        return $this;
    }

    public function removeUser(Users $user): static
    {
        if ($this->users->removeElement($user)) {
            if ($user->getUserType() === $this) {
                $user->setUserType(null);
            }
        }
        return $this;
    }
}