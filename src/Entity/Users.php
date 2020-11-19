<?php

namespace App\Entity;

use App\Repository\UsersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UsersRepository::class)
 */
class Users
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
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mail;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\ManyToMany(targetEntity=Roles::class, inversedBy="user")
     */
    private $role;

    /**
     * @ORM\ManyToMany(targetEntity=Articles::class, mappedBy="likes")
     */
    private $likes;

    /**
     * @ORM\ManyToMany(targetEntity=Articles::class, mappedBy="shares")
     */
    private $shares;

    public function __construct()
    {
        $this->role = new ArrayCollection();
        $this->likes = new ArrayCollection();
        $this->shares = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return Collection|Roles[]
     */
    public function getRole(): Collection
    {
        return $this->role;
    }

    public function addRole(Roles $role): self
    {
        if (!$this->role->contains($role)) {
            $this->role[] = $role;
        }

        return $this;
    }

    public function removeRole(Roles $role): self
    {
        $this->role->removeElement($role);

        return $this;
    }

    /**
     * @return Collection|Articles[]
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLike(Articles $like): self
    {
        if (!$this->likes->contains($like)) {
            $this->likes[] = $like;
            $like->addLike($this);
        }

        return $this;
    }

    public function removeLike(Articles $like): self
    {
        if ($this->likes->removeElement($like)) {
            $like->removeLike($this);
        }

        return $this;
    }

    /**
     * @return Collection|Articles[]
     */
    public function getShares(): Collection
    {
        return $this->shares;
    }

    public function addShare(Articles $share): self
    {
        if (!$this->shares->contains($share)) {
            $this->shares[] = $share;
            $share->addShare($this);
        }

        return $this;
    }

    public function removeShare(Articles $share): self
    {
        if ($this->shares->removeElement($share)) {
            $share->removeShare($this);
        }

        return $this;
    }
}
