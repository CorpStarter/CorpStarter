<?php

namespace App\Entity;

use App\Repository\UsersRepository;
use Doctrine\ORM\Mapping as ORM;

use ApiPlatform\Metadata\ApiResource;

#[ORM\Entity(repositoryClass: UsersRepository::class)]
#[ApiResource]
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

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $token = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $token_date = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password_hash = null;

    #[ORM\Column]
    private ?bool $email_confirmed = null;

    #[ORM\Column]
    private ?bool $terms_accepted = null;

    #[ORM\Column]
    private ?\DateTime $creation_date = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    private ?UserTypes $user_type = null;

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

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): static
    {
        $this->token = $token;

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

    public function getCreationDate(): ?\DateTime
    {
        return $this->creation_date;
    }

    public function setCreationDate(\DateTime $creation_date): static
    {
        $this->creation_date = $creation_date;

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
}
