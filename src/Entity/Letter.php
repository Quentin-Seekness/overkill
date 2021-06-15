<?php

namespace App\Entity;

use App\Repository\LetterRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LetterRepository::class)
 */
class Letter
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $jobName;

    /**
     * @ORM\Column(type="integer")
     */
    private $jobDStatus;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $companyName;

    /**
     * @ORM\Column(type="integer")
     */
    private $companyDStatus;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $hrName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $hrGender;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getJobName(): ?string
    {
        return $this->jobName;
    }

    public function setJobName(string $jobName): self
    {
        $this->jobName = $jobName;

        return $this;
    }

    public function getJobDStatus(): ?int
    {
        return $this->jobDStatus;
    }

    public function setJobDStatus(int $jobDStatus): self
    {
        $this->jobDStatus = $jobDStatus;

        return $this;
    }

    public function getCompanyName(): ?string
    {
        return $this->companyName;
    }

    public function setCompanyName(string $companyName): self
    {
        $this->companyName = $companyName;

        return $this;
    }

    public function getCompanyDStatus(): ?int
    {
        return $this->companyDStatus;
    }

    public function setCompanyDStatus(int $companyDStatus): self
    {
        $this->companyDStatus = $companyDStatus;

        return $this;
    }

    public function getHrName(): ?string
    {
        return $this->hrName;
    }

    public function setHrName(?string $hrName): self
    {
        $this->hrName = $hrName;

        return $this;
    }

    public function getHrGender(): ?string
    {
        return $this->hrGender;
    }

    public function setHrGender(?string $hrGender): self
    {
        $this->hrGender = $hrGender;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
