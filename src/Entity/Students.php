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

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $age = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $sex = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $quizValue = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $income = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $monthlySpend = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $variableIncome = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $othersIncomes = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $patrimony = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $investimentRecommended = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isNegative = null;

    /**
     * @var Collection<int, InvestimentProfile>
     */
    #[ORM\ManyToMany(targetEntity: InvestimentProfile::class, mappedBy: 'student')]
    private Collection $investimentProfiles;

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

    public function getAge(): ?string
    {
        return $this->age;
    }

    public function setAge(?string $age): static
    {
        $this->age = $age;

        return $this;
    }

    public function getSex(): ?string
    {
        return $this->sex;
    }

    public function setSex(?string $sex): static
    {
        $this->sex = $sex;

        return $this;
    }

    public function getQuizValue(): ?string
    {
        return $this->quizValue;
    }

    public function setQuizValue(?string $quizValue): static
    {
        $this->quizValue = $quizValue;

        return $this;
    }

    public function getIncome(): ?string
    {
        return $this->income;
    }

    public function setIncome(?string $income): static
    {
        $this->income = $income;

        return $this;
    }

    public function getMonthlySpend(): ?string
    {
        return $this->monthlySpend;
    }

    public function setMonthlySpend(?string $monthlySpend): static
    {
        $this->monthlySpend = $monthlySpend;

        return $this;
    }

    public function getVariableIncome(): ?string
    {
        return $this->variableIncome;
    }

    public function setVariableIncome(?string $variableIncome): static
    {
        $this->variableIncome = $variableIncome;

        return $this;
    }

    public function getOthersIncomes(): ?string
    {
        return $this->othersIncomes;
    }

    public function setOthersIncomes(?string $othersIncomes): static
    {
        $this->othersIncomes = $othersIncomes;

        return $this;
    }

    public function getPatrimony(): ?string
    {
        return $this->patrimony;
    }

    public function setPatrimony(?string $patrimony): static
    {
        $this->patrimony = $patrimony;

        return $this;
    }

    public function getInvestimentRecommended(): ?string
    {
        return $this->investimentRecommended;
    }

    public function setInvestimentRecommended(?string $investimentRecommended): static
    {
        $this->investimentRecommended = $investimentRecommended;

        return $this;
    }

    public function isNegative(): ?bool
    {
        return $this->isNegative;
    }

    public function setIsNegative(?bool $isNegative): static
    {
        $this->isNegative = $isNegative;

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
}
