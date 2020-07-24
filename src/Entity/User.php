<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User contain the login data to the backend a user cant have hostels or ads and many more.
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields={"email"}, message="acount.with.email.exist")
 * @ORM\HasLifecycleCallbacks()
 */
class User implements UserInterface
{

    /**
     * One user has many hostels. This is the inverse side.
     * @ORM\OneToMany(targetEntity=Hostel::class, mappedBy="user")
     */
    private $hostels;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank
     * @Assert\Email
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = ['ROLE_USER'];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     *
     * @ORM\Column(type="integer", unique=true)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $partner_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $status;

    /**
     * @ORM\Column(type="json")
     */
    private $user_privileges = [];

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isUserMadeChanges;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isHeWantsUpgrade;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $run_time;

    #
    # Connection to the hostel entity
    #
    public function __construct()
    {
        $this->hostels = new ArrayCollection();
    }

    public function getHostels(): Collection
    {
        return $this->hostels;
    }


    public function setHostels(Hostel $hostels): self
    {
        $this->hostels = $hostels;

        return $this;
    }

    public function addHostel(Hostel $hostels): self
    {
        if (!$this->hostels->contains($hostels)) {
            $this->hostels[] = $hostels;
            $hostels->setUser($this);
        }

        return $this;
    }

    public function removeHostel(Hostel $hostels): self
    {
        if ($this->hostels->contains($hostels)) {
            $this->hostels->removeElement($hostels);
            // set the owning side to null (unless already changed)
            if ($hostels->getUser() === $this) {
                $hostels->setUser(null);
            }
        }

        return $this;
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * Email Address is login user name
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string)$this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string)$this->password;
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

    public function getPartnerId(): ?int
    {
        return $this->partner_id;
    }

    public function setPartnerId(?int $partner_id): self
    {
        $this->partner_id = $partner_id;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(?bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getUserPrivileges(): ?array
    {
        return $this->user_privileges;
    }

    public function setUserPrivileges(array $user_privileges): self
    {
        $this->user_privileges = $user_privileges;

        return $this;
    }

    public function getIsUserMadeChanges(): ?bool
    {
        return $this->isUserMadeChanges;
    }

    public function setIsUserMadeChanges(?bool $isUserMadeChanges): self
    {
        $this->isUserMadeChanges = $isUserMadeChanges;

        return $this;
    }

    public function getIsHeWantsUpgrade(): ?bool
    {
        return $this->isHeWantsUpgrade;
    }

    public function setIsHeWantsUpgrade(?bool $isHeWantsUpgrade): self
    {
        $this->isHeWantsUpgrade = $isHeWantsUpgrade;

        return $this;
    }

    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->createAt;
    }

    public function setCreateAt(?\DateTimeInterface $createAt): self
    {
        $this->createAt = $createAt;

        return $this;
    }

    /**
     * @ORM\PrePersist
     * Set a default date by transaction if is empty
     */
    public function setCreateAtDefaultValue()
    {
        $this->createAt = new \DateTime();
    }

    public function getRunTime(): ?\DateTimeInterface
    {
        return $this->run_time;
    }

    public function setRunTime(?\DateTimeInterface $run_time): self
    {
        $this->run_time = $run_time;

        return $this;
    }

    public function __toString()
    {
        return "ID:{$this->getId()} {$this->getName()}";
    }
}
