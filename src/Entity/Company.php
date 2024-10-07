<?php

namespace App\Entity;

use App\Repository\CompanyRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CompanyRepository::class)]
class Company
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $companyName = null;

    #[ORM\Column(nullable: true)]
    private ?int $numberSiren = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $companyAddress = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $companyCity = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $companyPostalCode = null;

    #[ORM\Column(nullable: true)]
    private ?int $creditPoints = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCompanyName(): ?string
    {
        return $this->companyName;
    }

    public function setCompanyName(?string $companyName): static
    {
        $this->companyName = $companyName;

        return $this;
    }

    public function getNumberSiren(): ?int
    {
        return $this->numberSiren;
    }

    public function setNumberSiren(?int $numberSiren): static
    {
        $this->numberSiren = $numberSiren;

        return $this;
    }

    public function getCompanyAddress(): ?string
    {
        return $this->companyAddress;
    }

    public function setCompanyAddress(?string $companyAddress): static
    {
        $this->companyAddress = $companyAddress;

        return $this;
    }

    public function getCompanyCity(): ?string
    {
        return $this->companyCity;
    }

    public function setCompanyCity(?string $companyCity): static
    {
        $this->companyCity = $companyCity;

        return $this;
    }

    public function getCompanyPostalCode(): ?string
    {
        return $this->companyPostalCode;
    }

    public function setCompanyPostalCode(?string $companyPostalCode): static
    {
        $this->companyPostalCode = $companyPostalCode;

        return $this;
    }

    public function getCreditPoints(): ?int
    {
        return $this->creditPoints;
    }

    public function setCreditPoints(?int $creditPoints): static
    {
        $this->creditPoints = $creditPoints;

        return $this;
    }
}
