<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'users')]
class UserEntity
{
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

    #[ORM\Column(length: 50, nullable: true)]
    private ?int $permission = 0;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $status = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $phone_num = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $address = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $gender_role = null;

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

    public function getPermission(): ?string
    {
        return $this->permission;
    }

    public function setPermission(int $permission): self
    {
        $this->permission = $permission;
        return $this;
    }

    public function getStatus(): ?string
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

    public function getGenderRole(): ?string
    {
        return $this->gender_role;
    }
    public function setGenderRole(?string $gender_role): self
    {
        $this->gender_role = $gender_role;
        return $this;
    }
}
