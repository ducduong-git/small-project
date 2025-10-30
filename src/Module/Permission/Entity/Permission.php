<?php

namespace App\Module\Permission\Entity;

use App\Module\Core\Entity\EntityInterface;
use App\Module\Core\Entity\Timestampable;
use App\Module\Core\Entity\UserTrackingTrait;
use App\Module\Permission\Repository\PermissionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PermissionRepository::class)]
#[ORM\Table(name: 'permission')]
#[ORM\HasLifecycleCallbacks] // thông báo cho Doctrine rằng Entity này có các method callback (PrePersist, PreUpdate) để được gọi tự động.
class Permission implements EntityInterface
{
    use Timestampable;
    use UserTrackingTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\Column(type: 'integer')]
    private int $status;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;
        return $this;
    }
}
