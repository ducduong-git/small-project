<?php

namespace App\Module\Core\Entity;

use Doctrine\ORM\Mapping as ORM;

trait UserTrackingTrait
{
    #[ORM\Column(type: "string", length: 100, nullable: true)]
    private ?string $createdBy = null;

    #[ORM\Column(type: "string", length: 100, nullable: true)]
    private ?string $updatedBy = null;

    public function getCreatedBy(): ?string
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?string $createdBy): self
    {
        $this->createdBy = $createdBy;
        return $this;
    }

    public function getUpdatedBy(): ?string
    {
        return $this->updatedBy;
    }

    public function setUpdatedBy(?string $updatedBy): self
    {
        $this->updatedBy = $updatedBy;
        return $this;
    }
}
