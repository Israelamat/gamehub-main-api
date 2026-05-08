<?php

namespace App\Entity;

use App\Repository\CommunityGameRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CommunityGameRepository::class)]
class CommunityGame
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['community_game:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['community_game:read'])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    #[Groups(['community_game:read'])]
    #[Assert\NotBlank]
    private ?string $author = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['community_game:read'])]
    #[Assert\NotBlank]
    private ?string $imageBase64 = null;

    #[ORM\Column]
    #[Groups(['community_game:read'])]
    #[Assert\Range(min: 0, max: 5)]
    private ?float $rating = null;

    #[ORM\Column]
    #[Groups(['community_game:read'])]
    #[Assert\PositiveOrZero]
    private ?float $price = null;

    #[ORM\Column]
    #[Groups(['community_game:read'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'communityGames')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['community_game:read'])]
    private ?User $createdBy = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function getImageBase64(): ?string
    {
        return $this->imageBase64;
    }

    public function setImageBase64(string $imageBase64): static
    {
        $this->imageBase64 = $imageBase64;

        return $this;
    }

    public function getRating(): ?float
    {
        return $this->rating;
    }

    public function setRating(float $rating): static
    {
        $this->rating = $rating;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): static
    {
        $this->createdBy = $createdBy;

        return $this;
    }
}
