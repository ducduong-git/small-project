<?php

namespace App\Module\User\Entity;

use App\Module\User\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'users')]
#[ORM\HasLifecycleCallbacks] // thông báo cho Doctrine rằng Entity này có các method callback (PrePersist, PreUpdate) để được gọi tự động.
class UserEntity
{
    private const STATUS_ACTIVE = 1;
    private const ROLE_USER = 0;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $fname = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $lname = null;

    #[ORM\Column(length: 180)]
    private ?string $gmail = null;

    #[ORM\Column(length: 255)]
    private ?string $passw = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $permission = self::ROLE_USER;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $status = self::STATUS_ACTIVE;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $phone_num = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $address = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $genderRole = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

    // --- Getters and Setters ---
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFname(): ?string
    {
        return $this->fname;
    }
    public function setFname(string $fname): self
    {
        $this->fname = $fname;
        return $this;
    }

    public function getLname(): ?string
    {
        return $this->lname;
    }

    public function setLname(string $lname): self
    {
        $this->lname = $lname;
        return $this;
    }

    public function getGmail(): ?string
    {
        return $this->gmail;
    }

    public function setGmail(string $gmail): self
    {
        $this->gmail = $gmail;
        return $this;
    }

    public function getPassw(): ?string
    {
        return $this->passw;
    }

    public function setPassw(string $passw): self
    {
        $this->passw = $passw;
        return $this;
    }

    public function getPermission(): ?int
    {
        return $this->permission;
    }

    public function setPermission(int $permission): self
    {
        $this->permission = $permission;
        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function getPhoneNum(): ?string
    {
        return $this->phone_num;
    }
    public function setPhoneNum(?string $phone_num): self
    {
        $this->phone_num = $phone_num;
        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }
    public function setAddress(?string $address): self
    {
        $this->address = $address;
        return $this;
    }

    public function getGenderRole(): ?int
    {
        return $this->genderRole;
    }

    public function setGenderRole(?int $genderRole): self
    {
        $this->genderRole = $genderRole;
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

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    // --- Lifecycle callback methods ---
    #[ORM\PrePersist]
    public function onPrePersist(): void
    {
        $now = new \DateTimeImmutable();
        if ($this->createdAt === null) {
            $this->createdAt = $now;
        }
        $this->updatedAt = $now;
    }

    #[ORM\PreUpdate]
    public function onPreUpdate(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }
}
