<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use App\Repository\UserRepository;
use App\State\UserPasswordHasher;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[ApiResource(
    security: "is_granted('IS_AUTHENTICATED')",
    securityMessage: "You need to be logged in to access this page",
    operations: [
        new Get(),
        new GetCollection(),
        new Post(processor: UserPasswordHasher::class, validationContext: ['groups' => ['Default', 'user_write']]),
        new Patch(processor: UserPasswordHasher::class),
        new Delete(security: "is_granted('ROLE_ADMIN')", securityMessage: "You are not allowed to perform this operation")
    ],
    normalizationContext: ['groups' => ['user_read']],
    denormalizationContext: ['groups' => ['user_write']],
)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['user_read'])]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    #[Groups(['user_read', 'user_write'])]
    private ?string $email = null;

    /**
     * @var Profile The user profile
     */
    #[ORM\ManyToOne(targetEntity: Profile::class)]
    #[Groups(['user_read', 'user_write'])]
    private Profile $profile;

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[Groups(['user_write'])]
    private ?string $plaintextPassword = null;

    #[ORM\Column(length: 255)]
    #[Groups(['user_read', 'user_write'])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups(['user_read', 'user_write'])]
    private ?string $phone = null;

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        // There is only one role per profile
        return [$this->profile->getRole()];
    }

    /**
     * @param list<string> $roles
     */
/*     public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    } */

    public function getProfile(): Profile
    {
        return $this->profile;
    }

    public function setProfile(Profile $profile): static
    {
        $this->profile = $profile;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getPlaintextPassword(): ?string
    {
        return $this->plaintextPassword;
    }

    public function setPlaintextPassword(string $plaintextPassword): static
    {
        $this->plaintextPassword = $plaintextPassword;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        $this->plaintextPassword = null;
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

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }
}
