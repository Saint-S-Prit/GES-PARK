<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping\InheritanceType;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 * 
 * @InheritanceType("SINGLE_TABLE")
 * @DiscriminatorColumn(name="role_user", type="string")
 * @DiscriminatorMap({"user" = "User", "adminSysteme" = "AdminSysteme","adminAgent" = "AdminAgent", "driver" = "Driver"})
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"driver_read","adminSysteme_read","adminAgent_read"})
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups({"driver_read","driver_write","adminSysteme_read", "adminSysteme_write","adminAgent_read", "adminAgent_write","vehicle_read","edite_adminSysteme_read","edite_adminAgent_read"})
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string", nullable=true)
     * @Groups({"driver_write","adminAgent_write","edite_adminSysteme_write"})
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"driver_read","driver_write","adminSysteme_read", "adminSysteme_write","adminAgent_read", "adminAgent_write","vehicle_read", "edite_adminSysteme_write","edite_adminSysteme_read","edite_adminAgent_read"})
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"driver_read","driver_write","adminSysteme_read", "adminSysteme_write","adminAgent_read", "adminAgent_write","vehicle_read", "edite_adminSysteme_write","edite_adminSysteme_read","edite_adminAgent_read"})
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"driver_read","driver_write","adminSysteme_read", "adminSysteme_write","adminAgent_read", "adminAgent_write","vehicle_read", "edite_adminSysteme_write","edite_adminSysteme_read","edite_adminAgent_write","edite_adminAgent_read"})
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"driver_read","driver_write","adminSysteme_read", "adminSysteme_write","adminAgent_read", "adminAgent_write","vehicle_read", "edite_adminSysteme_write","edite_adminSysteme_read","edite_adminAgent_write","edite_adminAgent_read"})
     */
    private $phone;

    /**
     * @ORM\Column(type="blob", nullable=true)
     * @Groups({"driver_read","driver_write","adminSysteme_read", "adminSysteme_write","adminAgent_read", "adminAgent_write","vehicle_read", "edite_adminSysteme_write","edite_adminSysteme_read","edite_adminAgent_write","edite_adminAgent_read"})
     */
    private $avatar;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"driver_read","adminSysteme_read","adminAgent_read","vehicle_read","edite_adminSysteme_read","edite_adminAgent_read"})
     */
    private $status = false;

    /**
     * @ORM\ManyToOne(targetEntity=Profile::class, inversedBy="users")
     */
    private $profile;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"vehicle_read", "vehicle_write","driver_read", "adminSysteme_read","adminAgent_read","edite_adminSysteme_read","edite_adminAgent_read"})
     */
    private $codeUser;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"driver_read","driver_write","adminSysteme_read", "adminSysteme_write","adminAgent_read", "adminAgent_write", "edite_adminSysteme_write","edite_adminSysteme_read","edite_adminAgent_write","edite_adminAgent_read"})
     */
    private $cin;

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
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_' . strtoupper($this->getProfile()->getName());

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
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getAvatar()
    {
        if ($this->avatar != null) {
            return $this->avatar != null ? \base64_encode(stream_get_contents($this->avatar)) : null;
        }
    }

    public function setAvatar($avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getProfile(): ?Profile
    {
        return $this->profile;
    }

    public function setProfile(?Profile $profile): self
    {
        $this->profile = $profile;

        return $this;
    }

    public function getCodeUser(): ?string
    {
        return $this->codeUser;
    }

    public function setCodeUser(string $codeUser): self
    {
        $this->codeUser = $codeUser;

        return $this;
    }

    public function getCin(): ?string
    {
        return $this->cin;
    }

    public function setCin(string $cin): self
    {
        $this->cin = $cin;

        return $this;
    }
}
