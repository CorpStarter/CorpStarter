<?php

namespace App\Entity;

use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

use ApiPlatform\Metadata\ApiResource;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
#[ApiResource]
class Project
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $requested_budget = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $allocated_budget = null;

    #[ORM\Column(length: 255)]
    private ?string $illustration_path = null;

    #[ORM\Column]
    private ?\DateTime $creation_date = null;

    #[ORM\ManyToOne(inversedBy: 'projects')]
    private ?ProjectStatus $status = null;

    #[ORM\ManyToOne]
    private ?Users $requester = null;

    #[ORM\ManyToOne]
    private ?Users $approver = null;

    /**
     * @var Collection<int, Users>
     */
    #[ORM\ManyToMany(targetEntity: Users::class)]
    private Collection $attendees;

    public function __construct()
    {
        $this->attendees = new ArrayCollection();
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

    public function setRequestedBudget(string $requested_budget): static
    {
        $this->requested_budget = $requested_budget;

        return $this;
    }

    public function getAllocatedBudget(): ?string
    {
        return $this->allocated_budget;
    }

    public function setAllocatedBudget(string $allocated_budget): static
    {
        $this->allocated_budget = $allocated_budget;

        return $this;
    }

    public function getIllustrationPath(): ?string
    {
        return $this->illustration_path;
    }

    public function setIllustrationPath(string $illustration_path): static
    {
        $this->illustration_path = $illustration_path;

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

    public function getStatus(): ?ProjectStatus
    {
        return $this->status;
    }

    public function setStatus(?ProjectStatus $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getRequester(): ?Users
    {
        return $this->requester;
    }

    public function setRequester(?Users $requester): static
    {
        $this->requester = $requester;

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

    /**
     * @return Collection<int, Users>
     */
    public function getAttendees(): Collection
    {
        return $this->attendees;
    }

    public function addAttendee(Users $attendee): static
    {
        if (!$this->attendees->contains($attendee)) {
            $this->attendees->add($attendee);
        }

        return $this;
    }

    public function removeAttendee(Users $attendee): static
    {
        $this->attendees->removeElement($attendee);

        return $this;
    }
}
