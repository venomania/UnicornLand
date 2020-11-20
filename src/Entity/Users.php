<?php

namespace App\Entity;

use App\Entity\Roles;
use App\Entity\Articles;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UsersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\User\UserInterface;


/**
 * @ORM\Entity(repositoryClass=UsersRepository::class)
 */
class Users implements UserInterface
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
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $mail;

    /**
     * @var Collection
     * @ORM\ManyToMany(targetEntity=Roles::class, inversedBy="user")
     * @ORM\JoinTable(name="users_roles")
     */
    private $roles;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

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
        $this->roles = new ArrayCollection();
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->mail;
    }

    /**
     * @see UserInterface
     * @return Collection|Roles[]
     */
    public function getRoles(): Collection
    {
        return $this->roles;
    }

    public function addRoles(Roles $roles): self
    {
        if (!$this->roles->contains($roles)) {
            $this->roles[] = $roles;
            $roles->addUser($this);
        }

        return $this;
    }

    public function removeRoles(Roles $roles): self
    {
        $this->roles->removeElement($roles);

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

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
    /**
     * Set the value of likes
     *
     * @return  self
     */ 
    public function setLikes($likes)
    {
        $this->likes = $likes;

        return $this;
    }

}
