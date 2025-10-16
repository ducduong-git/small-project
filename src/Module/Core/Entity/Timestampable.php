<?php

namespace App\Module\Core\Entity;

use Doctrine\ORM\Mapping as ORM;
use DateTimeImmutable;

trait Timestampable
{
    #[ORM\Column(type: "datetime_immutable")]
    private DateTimeImmutable $createdAt;

    #[ORM\Column(type: "datetime_immutable", nullable: true)]
    private ?DateTimeImmutable $updatedAt = null;

    #[ORM\Column(type: "datetime_immutable", nullable: true)]
    private ?DateTimeImmutable $deletedAt = null;

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function getDeletedAt(): ?DateTimeImmutable
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?DateTimeImmutable $deletedAt): self
    {
        $this->deletedAt = $deletedAt;
        return $this;
    }

    public function setCreatedBy(string $createdBy): self
    {
        $this->createdBy = $createdBy;
        return $this;
    }

    public function getCreatedBy(): ?string
    {
        return $this->createdBy;
    }

    public function setUpdatedBy(string $updatedBy): self
    {
        $this->updatedBy = $updatedBy;
        return $this;
    }

    public function getUpdatedBy(): ?string
    {
        return $this->updatedBy;
    }

    #[ORM\PrePersist]
    public function onPrePersistSetCreatedAt(): void
    {
        $this->createdAt = new DateTimeImmutable();
    }

    #[ORM\PreUpdate]
    public function onPreUpdateSetUpdatedAt(): void
    {
        $this->updatedAt = new DateTimeImmutable();
    }
}
