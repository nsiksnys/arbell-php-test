<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\ProfileRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ProfileRepository::class)]
#[ApiResource(
    operations: [ new Get(), new GetCollection() ],
    normalizationContext: ['groups' => ['user_read', 'profile_read']]
)]class Profile
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['profile_read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['user_read', 'profile_read'])]
    private ?string $role = 'ROLE_USER';

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): static
    {
        $this->role = $role;

        return $this;
    }
}
