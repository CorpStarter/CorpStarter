<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ProjectStatusRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource]
#[ORM\Entity(repositoryClass: ProjectStatusRepository::class)]
#[ORM\HasLifecycleCallbacks] // <--- TRÈS IMPORTANT : Permet d'utiliser le PrePersist
class ProjectStatus
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $status_name = null;

    #[ORM\Column]
    private ?\DateTime $creation_date = null;

    #[ORM\Column]
    private ?bool $validated = false; // Valeur par défaut pour éviter le Null

    /**
     * @var Collection<int, Project>
     */
    #[ORM\OneToMany(targetEntity: Project::class, mappedBy: 'status')]
    private Collection $projects;

    public function __construct()
    {
        $this->projects = new ArrayCollection();
        $this->creation_date = new \DateTime(); // Initialise la date dès la création de l'objet
    }

    /**
     * Cette méthode remplit la date automatiquement juste avant l'envoi en BDD
     */
    #[ORM\PrePersist]
    public function setCreationDateValue(): void
    {
        if ($this->creation_date === null) {
            $this->creation_date = new \DateTime();
        }
    }

    public function __toString(): string
    {
        return (string) $this->status_name;
    }

    // --- GETTERS & SETTERS ---

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatusName(): ?string
    {
        return $this->status_name;
    }

    public function setStatusName(string $status_name): static
    {
        $this->status_name = $status_name;
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

    public function isValidated(): ?bool
    {
        return $this->validated;
    }

    public function setValidated(bool $validated): static
    {
        $this->validated = $validated;
        return $this;
    }

    public function getProjects(): Collection
    {
        return $this->projects;
    }

    public function addProject(Project $project): static
    {
        if (!$this->projects->contains($project)) {
            $this->projects->add($project);
            $project->setStatus($this);
        }
        return $this;
    }

    public function removeProject(Project $project): static
    {
        if ($this->projects->removeElement($project)) {
            if ($project->getStatus() === $this) {
                $project->setStatus(null);
            }
        }
        return $this;
    }
}