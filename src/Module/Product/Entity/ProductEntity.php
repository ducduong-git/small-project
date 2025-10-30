<?php

namespace App\Module\Product\Entity;

use App\Module\Core\Entity\EntityInterface;
use App\Module\Core\Entity\Timestampable;
use App\Module\Core\Entity\UserTrackingTrait;
use App\Module\Product\Repository\ProductEntityRepository;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductEntityRepository::class)]
#[ORM\Table(name: 'product')]
#[ORM\HasLifecycleCallbacks] // thông báo cho Doctrine rằng Entity này có các method callback (PrePersist, PreUpdate) để được gọi tự động.
class ProductEntity implements EntityInterface
{
    use Timestampable;
    use UserTrackingTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(nullable: true)]
    private ?int $qty = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $mainImg = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $listImg = null;

    #[ORM\Column(nullable: true)]
    private ?float $price = null;

    #[ORM\Column]
    private ?int $status = null;

    #[ORM\Column]
    private ?int $cateId = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getQty(): ?int
    {
        return $this->qty;
    }

    public function setQty(?int $qty): static
    {
        $this->qty = $qty;

        return $this;
    }

    public function getMainImg(): ?string
    {
        return $this->mainImg;
    }

    public function setMainImg(?string $mainImg): static
    {
        $this->mainImg = $mainImg;

        return $this;
    }

    public function getListImg(): ?string
    {
        return $this->listImg;
    }

    public function setListImg(string $listImg): static
    {
        $this->listImg = $listImg;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getCateId(): ?int
    {
        return $this->cateId;
    }

    public function setCateId(int $cateId): static
    {
        $this->cateId = $cateId;

        return $this;
    }
}
