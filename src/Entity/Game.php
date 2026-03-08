<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: GameRepository::class)]
#[UniqueEntity(fields: ['appId'], message: 'This Steam AppID is already in our catalog.')]
class Game
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['game:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['game:read', 'order:read'])]
    #[Assert\NotBlank(message: "The game must have a title.")]
    #[Assert\Length(max: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['game:read'])]
    #[Assert\NotBlank]
    private ?string $description = null;

    #[ORM\Column]
    #[Groups(['game:read'])]
    #[Assert\PositiveOrZero(message: "Price cannot be negative.")]
    private ?float $price = null;

    #[ORM\Column]
    #[Groups(['game:read'])]
    #[Assert\Type('integer')]
    #[Assert\GreaterThanOrEqual(0)]
    private ?int $stock = null;


    #[ORM\Column]
    #[Groups(['game:read'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'games')]
    #[ORM\JoinColumn(nullable: true)]
    #[Groups(['game:read'])]
    private ?User $createdBy = null;

    /**
     * @var Collection<int, Review>
     */
    #[ORM\OneToMany(targetEntity: Review::class, mappedBy: 'game')]
    #[Groups(['game:read'])]
    private Collection $reviews;

    #[ORM\Column(unique: true)]
    #[Groups(['game:read'])]
    #[Assert\NotNull]
    private ?int $appId = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['game:read'])]
    #[Assert\Url(message: "The header image must be a valid URL.")]
    private ?string $headerImage = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['game:read'])]
    private ?string $genres = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['game:read'])]
    private ?string $tags = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['game:read'])]
    private ?string $metadata = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['game:read'])]
    private ?string $developer = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['game:read'])]
    private ?string $screenshot = null;

    /**
     * @var Collection<int, Order>
     */
    #[ORM\ManyToMany(targetEntity: Order::class, mappedBy: 'games')]
    private Collection $orders;

    public function __construct()
    {
        $this->reviews = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
        $this->stock = 99;
        $this->orders = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

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

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): static
    {
        $this->stock = $stock;

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

    /**
     * @return Collection<int, Review>
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Review $review): static
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews->add($review);
            $review->setGame($this);
        }

        return $this;
    }

    public function removeReview(Review $review): static
    {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getGame() === $this) {
                $review->setGame(null);
            }
        }

        return $this;
    }

    public function getAppId(): ?int
    {
        return $this->appId;
    }

    public function getHeaderImage(): ?string
    {
        return $this->headerImage;
    }

    public function setHeaderImage(?string $headerImage): static
    {
        $this->headerImage = $headerImage;

        return $this;
    }

    public function getGenres(): ?string
    {
        return $this->genres;
    }

    public function setGenres(?string $genres): static
    {
        $this->genres = $genres;

        return $this;
    }

    public function getTags(): ?string
    {
        return $this->tags;
    }

    public function getMetadata(): ?string
    {
        return $this->metadata;
    }

    public function getDeveloper(): ?string
    {
        return $this->developer;
    }

    public function setDeveloper(?string $developer): static
    {
        $this->developer = $developer;

        return $this;
    }

    public function getScreenshot(): ?string
    {
        return $this->screenshot;
    }

    public function setScreenshot(?string $screenshot): static
    {
        $this->screenshot = $screenshot;

        return $this;
    }

    /**
     * @return Collection<int, Order>
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): static
    {
        if (!$this->orders->contains($order)) {
            $this->orders->add($order);
            $order->addGame($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): static
    {
        if ($this->orders->removeElement($order)) {
            $order->removeGame($this);
        }

        return $this;
    }
}
