<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\VehicleRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=VehicleRepository::class)
 * 
 * @ApiResource(
 * normalizationContext={"groups"={"vehicle_read"}},
 * denormalizationContext={"groups"={"vehicle_write"}},
 * 
 *           collectionOperations = {
 *           "POST" =
 *             {
 *                "path" = "/vehicle"
 *             },
 *           "GET" =
 *             {
 *                 "path" = "/vehicle"
 *             }
 * 
 * },
 *          itemOperations = {
 *          "GET" =
 *             {
 *                "path" = "/vehicle/{id}"
 *             },
 *          "PUT" =
 *             {
 *                 "path" = "/vehicle/{id}",
 *                      "normalization_context"={"groups"={"edite_vehicle_read"}},
 *                      "denormalization_context"={"groups"={"edite_vehicle_write"}}
 *             },
 *           "DELETE" =
 *             {
 *                 "path" = "/vehicle/{id}"
 *             },
 *           "PANNE" =
 *             {
 *                 "method" = "PUT",
 *                 "path" = "/vehicle/{id}/panne",
 *                 "controller" = VehicleController::class,
 *             },
 *           "FONCTIONNAL" =
 *             {
 *                 "method" = "PUT",
 *                 "path" = "/vehicle/{id}/fonctionnal",
 *                 "controller" = VehicleController::class,
 *             }
 * },
 * )
 */
class Vehicle
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"vehicle_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"vehicle_read", "vehicle_write","driver_read","edite_vehicle_read"})
     */
    private $matricule;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"vehicle_read", "vehicle_write","driver_read","edite_vehicle_read","edite_vehicle_write"})
     */
    private $mark;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"vehicle_read", "vehicle_write","driver_read","edite_vehicle_read","edite_vehicle_write"})

     */
    private $modele;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"vehicle_read", "vehicle_write","driver_read","edite_vehicle_read","edite_vehicle_write"})
     */
    private $color;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"vehicle_read", "vehicle_write","driver_read","edite_vehicle_read","edite_vehicle_write"})
     */
    private $numbPlace;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"vehicle_read", "vehicle_write","driver_read","edite_vehicle_read","edite_vehicle_write"})
     */
    private $location;

    /**
     * @ORM\Column(type="blob", nullable=true)
     * @Groups({"vehicle_read", "vehicle_write","driver_read","edite_vehicle_read","edite_vehicle_write"})
     */
    private $cartRegistration;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"vehicle_read","driver_read"})
     */
    private $status = false;

    /**
     * @ORM\OneToOne(targetEntity=Driver::class, mappedBy="vehicle", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     * @Groups({"vehicle_read", "vehicle_write"})
     */
    private $driver;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"vehicle_read", "vehicle_write","driver_read","edite_vehicle_read","edite_vehicle_write"})
     */
    private $nature;

    /**
     * @ORM\OneToMany(targetEntity=Accident::class, mappedBy="vehicle")
     */
    private $accident;

    /**
     * @ORM\OneToMany(targetEntity=Carburant::class, mappedBy="vehicle")
     */
    private $carburant;

    /**
     * @ORM\OneToMany(targetEntity=Insurance::class, mappedBy="vehicle")
     */
    private $insurance;

    /**
     * @ORM\OneToMany(targetEntity=Technique::class, mappedBy="vehicle")
     */
    private $technique;

    /**
     * @ORM\OneToMany(targetEntity=Maintenance::class, mappedBy="vehicle")
     */
    private $maintenance;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"vehicle_read", "vehicle_write","driver_read"})
     */
    private $codeVehicle;

    /**
     * @ORM\Column(type="boolean")
     * 
     */
    private $actif = false;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"vehicle_read"})
     */
    private $panne;

    public function __construct()
    {
        $this->accident = new ArrayCollection();
        $this->carburant = new ArrayCollection();
        $this->insurance = new ArrayCollection();
        $this->technique = new ArrayCollection();
        $this->maintenance = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMatricule(): ?string
    {
        return $this->matricule;
    }

    public function setMatricule(string $matricule): self
    {
        $this->matricule = $matricule;

        return $this;
    }

    public function getMark(): ?string
    {
        return $this->mark;
    }

    public function setMark(string $mark): self
    {
        $this->mark = $mark;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getNumbPlace(): ?string
    {
        return $this->numbPlace;
    }

    public function setNumbPlace(string $numbPlace): self
    {
        $this->numbPlace = $numbPlace;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getCartRegistration()
    {
        if ($this->cartRegistration != null) {
            return $this->cartRegistration != null ? \base64_encode(stream_get_contents($this->cartRegistration)) : null;
        }
    }

    public function setCartRegistration($cartRegistration): self
    {
        $this->cartRegistration = $cartRegistration;
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

    public function getDriver(): ?Driver
    {
        return $this->driver;
    }

    public function setDriver(?Driver $driver): self
    {
        // unset the owning side of the relation if necessary
        if ($driver === null && $this->driver !== null) {
            $this->driver->setVehicle(null);
        }

        // set the owning side of the relation if necessary
        if ($driver !== null && $driver->getVehicle() !== $this) {
            $driver->setVehicle($this);
        }

        $this->driver = $driver;

        return $this;
    }

    public function getModele(): ?string
    {
        return $this->modele;
    }

    public function setModele(string $modele): self
    {
        $this->modele = $modele;

        return $this;
    }

    public function getNature(): ?string
    {
        return $this->nature;
    }

    public function setNature(string $nature): self
    {
        $this->nature = $nature;

        return $this;
    }

    /**
     * @return Collection|Accident[]
     */
    public function getAccident(): Collection
    {
        return $this->accident;
    }

    public function addAccident(Accident $accident): self
    {
        if (!$this->accident->contains($accident)) {
            $this->accident[] = $accident;
            $accident->setVehicle($this);
        }

        return $this;
    }

    public function removeAccident(Accident $accident): self
    {
        if ($this->accident->removeElement($accident)) {
            // set the owning side to null (unless already changed)
            if ($accident->getVehicle() === $this) {
                $accident->setVehicle(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Carburant[]
     */
    public function getCarburant(): Collection
    {
        return $this->carburant;
    }

    public function addCarburant(Carburant $carburant): self
    {
        if (!$this->carburant->contains($carburant)) {
            $this->carburant[] = $carburant;
            $carburant->setVehicle($this);
        }

        return $this;
    }

    public function removeCarburant(Carburant $carburant): self
    {
        if ($this->carburant->removeElement($carburant)) {
            // set the owning side to null (unless already changed)
            if ($carburant->getVehicle() === $this) {
                $carburant->setVehicle(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Insurance[]
     */
    public function getInsurance(): Collection
    {
        return $this->insurance;
    }

    public function addInsurance(Insurance $insurance): self
    {
        if (!$this->insurance->contains($insurance)) {
            $this->insurance[] = $insurance;
            $insurance->setVehicle($this);
        }

        return $this;
    }

    public function removeInsurance(Insurance $insurance): self
    {
        if ($this->insurance->removeElement($insurance)) {
            // set the owning side to null (unless already changed)
            if ($insurance->getVehicle() === $this) {
                $insurance->setVehicle(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Technique[]
     */
    public function getTechnique(): Collection
    {
        return $this->technique;
    }

    public function addTechnique(Technique $technique): self
    {
        if (!$this->technique->contains($technique)) {
            $this->technique[] = $technique;
            $technique->setVehicle($this);
        }

        return $this;
    }

    public function removeTechnique(Technique $technique): self
    {
        if ($this->technique->removeElement($technique)) {
            // set the owning side to null (unless already changed)
            if ($technique->getVehicle() === $this) {
                $technique->setVehicle(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Maintenance[]
     */
    public function getMaintenance(): Collection
    {
        return $this->maintenance;
    }

    public function addMaintenance(Maintenance $maintenance): self
    {
        if (!$this->maintenance->contains($maintenance)) {
            $this->maintenance[] = $maintenance;
            $maintenance->setVehicle($this);
        }

        return $this;
    }

    public function removeMaintenance(Maintenance $maintenance): self
    {
        if ($this->maintenance->removeElement($maintenance)) {
            // set the owning side to null (unless already changed)
            if ($maintenance->getVehicle() === $this) {
                $maintenance->setVehicle(null);
            }
        }

        return $this;
    }

    public function getCodeVehicle(): ?string
    {
        return $this->codeVehicle;
    }

    public function setCodeVehicle(string $codeVehicle): self
    {
        $this->codeVehicle = $codeVehicle;

        return $this;
    }

    public function getActif(): ?bool
    {
        return $this->actif;
    }

    public function setActif(bool $actif): self
    {
        $this->actif = $actif;

        return $this;
    }

    public function getPanne(): ?bool
    {
        return $this->panne;
    }

    public function setPanne(?bool $panne): self
    {
        $this->panne = $panne;

        return $this;
    }
}
