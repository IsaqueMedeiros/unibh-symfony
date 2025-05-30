<?php

namespace App\Entity;

use App\Repository\InvestimentProfileRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InvestimentProfileRepository::class)]
class InvestimentProfile
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $conservativeProfile = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $moderateProfile = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $boldProfile = null;

    /**
     * @var Collection<int, Students>
     */
    #[ORM\ManyToMany(targetEntity: Students::class, inversedBy: 'investimentProfiles')]
    private Collection $student;

    /**
     * @var Collection<int, InvestimentWallet>
     */
    #[ORM\ManyToMany(targetEntity: InvestimentWallet::class, mappedBy: 'profile')]
    private Collection $investimentWallets;

    public function __construct()
    {
        $this->student = new ArrayCollection();
        $this->investimentWallets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getConservativeProfile(): ?string
    {
        return $this->conservativeProfile;
    }

    public function setConservativeProfile(?string $conservativeProfile): static
    {
        $this->conservativeProfile = $conservativeProfile;

        return $this;
    }

    public function getModerateProfile(): ?string
    {
        return $this->moderateProfile;
    }

    public function setModerateProfile(?string $moderateProfile): static
    {
        $this->moderateProfile = $moderateProfile;

        return $this;
    }

    public function getBoldProfile(): ?string
    {
        return $this->boldProfile;
    }

    public function setBoldProfile(?string $boldProfile): static
    {
        $this->boldProfile = $boldProfile;

        return $this;
    }

    /**
     * @return Collection<int, Students>
     */
    public function getStudent(): Collection
    {
        return $this->student;
    }

    public function addStudent(Students $student): static
    {
        if (!$this->student->contains($student)) {
            $this->student->add($student);
        }

        return $this;
    }

    public function removeStudent(Students $student): static
    {
        $this->student->removeElement($student);

        return $this;
    }

    /**
     * @return Collection<int, InvestimentWallet>
     */
    public function getInvestimentWallets(): Collection
    {
        return $this->investimentWallets;
    }

    public function addInvestimentWallet(InvestimentWallet $investimentWallet): static
    {
        if (!$this->investimentWallets->contains($investimentWallet)) {
            $this->investimentWallets->add($investimentWallet);
            $investimentWallet->addProfile($this);
        }

        return $this;
    }

    public function removeInvestimentWallet(InvestimentWallet $investimentWallet): static
    {
        if ($this->investimentWallets->removeElement($investimentWallet)) {
            $investimentWallet->removeProfile($this);
        }

        return $this;
    }
}
