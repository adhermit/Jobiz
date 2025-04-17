<?php

namespace App\Entity;

use App\Repository\JobRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: JobRepository::class)]
class Job
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $city = null;

    #[ORM\Column(length: 255)]
    private ?string $country = null;

    #[ORM\Column]
    private ?bool $type = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $postDate = null;

    #[ORM\Column(nullable: true)]
    private ?int $minSalary = null;

    #[ORM\Column(nullable: true)]
    private ?int $maxSalary = null;

    /**
     * @var Collection<int, Category>
     */
    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'jobs')]
    private Collection $category;

    #[ORM\Column(type: 'string', length: 255)]    
    private ?string $company = null;

    public function __construct()
    {
        $this->category = new ArrayCollection();
        $this->minSalary = 0;
        $this->maxSalary = 0;
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
    
    public function getCompany(): string
    {
        return $this->company;
    }

    public function setCompany(?string $company): self
    {
        $this->company = $company;

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

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }


    public function getPostDate(): ?\DateTimeImmutable
    {
        return $this->postDate;
    }

    public function setPostDate(\DateTimeImmutable $postDate): static
    {
        $this->postDate = $postDate;

        return $this;
    }
    
    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): static
    {
        $this->country = $country;

        return $this;
    }

    public function isType(): ?bool
    {
        return $this->type;
    }

    public function setType(bool $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getMinSalary(): ?int
    {
        return $this->minSalary;
    }

    public function setMinSalary(int $minSalary): static
    {
        $this->minSalary = $minSalary;

        return $this;
    }

    public function getMaxSalary(): ?int
    {
        return $this->maxSalary;
    }

    public function setMaxSalary(int $maxSalary): static
    {
        $this->maxSalary = $maxSalary;

        return $this;
    }

    public function getSalaryRange(): string
    {
        return $this->minSalary . '-' . $this->maxSalary;
    }

    public function setSalaryRange(string $salaryRange): static
    {
        [$min, $max] = explode('-', $salaryRange);
        $this->minSalary = (int)$min;
        $this->maxSalary = (int)$max;

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategories(): Collection
    {
        return $this->category;
    }

    public function addCategory(Category $category): static
    {
        if (!$this->category->contains($category)) {
            $this->category->add($category);
        }

        return $this;
    }

    public function removeCategory(Category $category): static
    {
        $this->category->removeElement($category);

        return $this;
    }
}
