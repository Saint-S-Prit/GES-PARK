<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\LicenseRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=LicenseRepository::class)
 * @ApiResource(
 * normalizationContext={"groups"={"license_read"}},
 * denormalizationContext={"groups"={"license_write"}},
 * 
 *           collectionOperations = {
 *           "POST" =
 *             {
 *                "path" = "/license"
 *             },
 *           "GET" =
 *             {
 *                 "path" = "/license"
 *             }
 * 
 * },
 *          itemOperations = {
 *          "GET" =
 *             {
 *                "path" = "/license/{id}"
 *             },
 *          "PUT" =
 *             {
 *                 "path" = "/license/{id}",
 *             },
 *           "DELETE" =
 *             {
 *                 "path" = "/license/{id}"
 *             },
 * },
 * )
 */
class License
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"license_read","license_write"})
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     * @Groups({"license_read","license_write"})
     */
    private $description;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"license_read"})
     */
    private $status = false;

    /**
     * @ORM\OneToMany(targetEntity=Driver::class, mappedBy="license")
     */
    private $drivers;



    public function __construct()
    {
        $this->drivers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection|Driver[]
     */
    public function getDrivers(): Collection
    {
        return $this->drivers;
    }

    public function addDriver(Driver $driver): self
    {
        if (!$this->drivers->contains($driver)) {
            $this->drivers[] = $driver;
            $driver->setLicense($this);
        }

        return $this;
    }

    public function removeDriver(Driver $driver): self
    {
        if ($this->drivers->removeElement($driver)) {
            // set the owning side to null (unless already changed)
            if ($driver->getLicense() === $this) {
                $driver->setLicense(null);
            }
        }

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

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
}
