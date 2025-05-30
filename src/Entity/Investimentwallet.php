<?php

namespace App\Entity;

use App\Repository\InvestimentwalletRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InvestimentwalletRepository::class)]
class Investimentwallet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $totalValue = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isRecommended = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $createdAt = null;

    /**
     * @var Collection<int, InvestimentProfile>
     */
    #[ORM\ManyToMany(targetEntity: InvestimentProfile::class, inversedBy: 'investimentWallets')]
    private Collection $profile;

    public function __construct()
    {
        $this->profile = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getTotalValue(): ?string
    {
        return $this->totalValue;
    }

    public function setTotalValue(?string $totalValue): static
    {
        $this->totalValue = $totalValue;

        return $this;
    }

    public function isRecommended(): ?bool
    {
        return $this->isRecommended;
    }

    public function setIsRecommended(?bool $isRecommended): static
    {
        $this->isRecommended = $isRecommended;

        return $this;
    }

    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?string $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection<int, InvestimentProfile>
     */
    public function getProfile(): Collection
    {
        return $this->profile;
    }

    public function addProfile(InvestimentProfile $profile): static
    {
        if (!$this->profile->contains($profile)) {
            $this->profile->add($profile);
        }

        return $this;
    }

    public function removeProfile(InvestimentProfile $profile): static
    {
        $this->profile->removeElement($profile);

        return $this;
    }
}
