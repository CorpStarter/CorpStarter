<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource]
#[ORM\Entity(repositoryClass: ProjectRepository::class)]
class Project
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2, nullable: true)]
    private ?string $requested_budget = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $illustration_path = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2, nullable: true)]
    private ?string $allocated_budget = null;

    #[ORM\Column]
    private ?\DateTime $creation_date = null;

    /**
     * @var Collection<int, Users>
     */
    #[ORM\ManyToMany(targetEntity: Users::class, inversedBy: 'projects')]
    private Collection $requester;

    #[ORM\ManyToOne(inversedBy: 'projects')]
    private ?Users $approver = null;

    #[ORM\ManyToOne(inversedBy: 'projects')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ProjectStatus $status = null;

    public function __construct()
    {
        $this->requester = new ArrayCollection();
    }

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

    public function getRequestedBudget(): ?string
    {
        return $this->requested_budget;
    }

    public function setRequestedBudget(?string $requested_budget): static
    {
        $this->requested_budget = $requested_budget;

        return $this;
    }

    public function getIllustrationPath(): ?string
    {
        return $this->illustration_path;
    }

    public function setIllustrationPath(?string $illustration_path): static
    {
        $this->illustration_path = $illustration_path;

        return $this;
    }

    public function getAllocatedBudget(): ?string
    {
        return $this->allocated_budget;
    }

    public function setAllocatedBudget(?string $allocated_budget): static
    {
        $this->allocated_budget = $allocated_budget;

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
     * @return Collection<int, Users>
     */
    public function getRequester(): Collection
    {
        return $this->requester;
    }

    public function addRequester(Users $requester): static
    {
        if (!$this->requester->contains($requester)) {
            $this->requester->add($requester);
        }

        return $this;
    }

    public function removeRequester(Users $requester): static
    {
        $this->requester->removeElement($requester);

        return $this;
    }

    public function getApprover(): ?Users
    {
        return $this->approver;
    }

    public function setApprover(?Users $approver): static
    {
        $this->approver = $approver;

        return $this;
    }

    public function getStatus(): ?ProjectStatus
    {
        return $this->status;
    }

    public function setStatus(?ProjectStatus $status): static
    {
        $this->status = $status;

        return $this;
    }
}
