<?php

namespace App\Entity;

use App\Repository\StudentsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StudentsRepository::class)]
class Students
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'students')]
    private Collection $student;

    // private ?string $age = null;
    // private ?string $sex = null;
    // private ?string $quizValue = null;
    // private ?string $income = null;
    // private ?string $monthlySpend = null;
    // private ?string $variableIncome = null;
    // private ?string $othersIncomes = null;
    // private ?string $patrimony = null;
    // private ?string $investimentRecommended = null;
    // private ?bool $isNegative = null;

    /**
     * @var Collection<int, InvestimentProfile>
     */
    #[ORM\ManyToMany(targetEntity: InvestimentProfile::class, mappedBy: 'student')]
    private Collection $investimentProfiles;

    #[ORM\Column(nullable: true)]
    private ?array $studentInfo = null;

    public function __construct()
    {
        $this->student = new ArrayCollection();
        $this->investimentProfiles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, User>
     */
    public function getStudent(): Collection
    {
        return $this->student;
    }

    public function addStudent(User $student): static
    {
        if (!$this->student->contains($student)) {
            $this->student->add($student);
        }

        return $this;
    }

    public function removeStudent(User $student): static
    {
        $this->student->removeElement($student);

        return $this;
    }

    /**
     * @return Collection<int, InvestimentProfile>
     */
    public function getInvestimentProfiles(): Collection
    {
        return $this->investimentProfiles;
    }

    public function addInvestimentProfile(InvestimentProfile $investimentProfile): static
    {
        if (!$this->investimentProfiles->contains($investimentProfile)) {
            $this->investimentProfiles->add($investimentProfile);
            $investimentProfile->addStudent($this);
        }

        return $this;
    }

    public function removeInvestimentProfile(InvestimentProfile $investimentProfile): static
    {
        if ($this->investimentProfiles->removeElement($investimentProfile)) {
            $investimentProfile->removeStudent($this);
        }

        return $this;
    }

    public function getStudentInfo(): ?array
    {
        return $this->studentInfo;
    }

    public function setStudentInfo(?array $studentInfo): static
    {
        $this->studentInfo = $studentInfo;

        return $this;
    }
}
