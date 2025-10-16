<?php
// src/Module/Core/Entity/EntityInterface.php
namespace App\Module\Core\Entity;

use DateTimeImmutable;

interface EntityInterface
{
    public function setCreatedBy(string $createdBy): self;
    public function getCreatedBy(): ?string;

    public function setUpdatedBy(string $updatedBy): self;
    public function getUpdatedBy(): ?string;

    public function getCreatedAt(): ?DateTimeImmutable;
    public function setCreatedAt(DateTimeImmutable $createdAt): self;

    public function getUpdatedAt(): ?DateTimeImmutable;
    public function setUpdatedAt(DateTimeImmutable $updatedAt): self;

    public function getDeletedAt(): ?DateTimeImmutable;
    public function setDeletedAt(?DateTimeImmutable $deletedAt): self;
}
