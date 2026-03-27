<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\UsersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource]
#[ORM\Entity(repositoryClass: UsersRepository::class)]
class Users
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $username = null;

    #[ORM\Column(length: 255)]
    private ?string $last_name = null;

    #[ORM\Column(length: 255)]
    private ?string $first_name = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password_hash = null;

    #[ORM\Column]
    private ?bool $email_confirmed = null;

    #[ORM\Column]
    private ?bool $terms_accepted = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    private ?UserTypes $user_type = null;

    #[ORM\Column]
    private ?\DateTime $creation_date = null;

    /**
     * @var Collection<int, Project>
     */
    #[ORM\ManyToMany(targetEntity: Project::class, mappedBy: 'requester')]
    private Collection $projects;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $conection_token = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $token_date = null;

    public function __construct()
    {
        $this->projects = new ArrayCollection();
    }

    public function getId(): ?int
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

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): static
    {
        $this->last_name = $last_name;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): static
    {
        $this->first_name = $first_name;

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

    public function getPasswordHash(): ?string
    {
        return $this->password_hash;
    }

    public function setPasswordHash(string $password_hash): static
    {
        $this->password_hash = $password_hash;

        return $this;
    }

    public function isEmailConfirmed(): ?bool
    {
        return $this->email_confirmed;
    }

    public function setEmailConfirmed(bool $email_confirmed): static
    {
        $this->email_confirmed = $email_confirmed;

        return $this;
    }

    public function isTermsAccepted(): ?bool
    {
        return $this->terms_accepted;
    }

    public function setTermsAccepted(bool $terms_accepted): static
    {
        $this->terms_accepted = $terms_accepted;

        return $this;
    }

    public function getUserType(): ?UserTypes
    {
        return $this->user_type;
    }

    public function setUserType(?UserTypes $user_type): static
    {
        $this->user_type = $user_type;

        return $this;
    }

    public function getCreationDate(): ?\DateTime
    {
        return $this->creation_date;
    }

    public function setCreationDate(): static
    {
        date_default_timezone_set('Europe/Paris');
        $this->creation_date = new \DateTime();

        return $this;
    }

    /**
     * @return Collection<int, Project>
     */
    public function getProjects(): Collection
    {
        return $this->projects;
    }

    public function addProject(Project $project): static
    {
        if (!$this->projects->contains($project)) {
            $this->projects->add($project);
            $project->addRequester($this);
        }

        return $this;
    }

    public function removeProject(Project $project): static
    {
        if ($this->projects->removeElement($project)) {
            $project->removeRequester($this);
        }

        return $this;
    }

    public function addProjectApproval(Project $project): static
    {
        if (!$this->projects->contains($project)) {
            $this->projects->add($project);
            $project->setApprover($this);
        }

        return $this;
    }

    public function removeProjectApproval(Project $project): static
    {
        if ($this->projects->removeElement($project)) {
            $project->removeRequester($this);
        }

        return $this;
    }

    public function getConectionToken(): ?string
    {
        return $this->conection_token;
    }

    public function setConectionToken(?string $conection_token): static
    {
        $this->conection_token = $conection_token;

        return $this;
    }

    public function getTokenDate(): ?\DateTime
    {
        return $this->token_date;
    }

    public function setTokenDate(?\DateTime $token_date): static
    {
        $this->token_date = $token_date;

        return $this;
    }
}
