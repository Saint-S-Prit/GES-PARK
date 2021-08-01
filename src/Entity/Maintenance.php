<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\MaintenanceRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=MaintenanceRepository::class)
 * @ApiResource(
 *              normalizationContext={"groups"={"maintenance_read"}},
 *              denormalizationContext={"disable_type_enforcement"=true},
 *          collectionOperations = {
 *              
 *           "POST" =
 *             {
 *                "path" = "/maintenance"
 *             },
 *           "GET" =
 *             {
 *                 "path" = "/maintenance"
 *             }
 * 
 * },
 *          itemOperations = {
 *          "GET" =
 *             {
 *                "path" = "/maintenance/{id}"
 *             }
 * },
 * )
 */
class Maintenance
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"maintenance_read", "maintenance_write"})
     */
    private $nature;


    /**
     * @ORM\Column(type="datetime")
     * @Assert\DateTime(message="La date doit être au format YYYY-MM-DD")
     */
    private $maintenanceDate;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"maintenance_read", "maintenance_write"})
     */
    private $cost;

    /**
     * @ORM\Column(type="blob")
     * @Groups({"maintenance_read", "maintenance_write"})
     */
    private $invoice;

    /**
     * @ORM\ManyToOne(targetEntity=Vehicle::class, inversedBy="maintenance")
     * @Groups({"maintenance_read", "maintenance_write"})
     */
    private $vehicle;



    /**
     * @ORM\Column(type="text")
     * @Groups({"maintenance_read", "maintenance_write"})
     */
    private $description;

    /**
     * @ORM\OneToOne(targetEntity=Provider::class, cascade={"persist", "remove"})
     */
    private $provider;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getMaintenanceDate(): ?string
    {
        return $this->maintenanceDate;
    }

    public function setMaintenanceDate(string $maintenanceDate): self
    {
        $this->maintenanceDate = $maintenanceDate;

        return $this;
    }

    public function getCost(): ?string
    {
        return $this->cost;
    }

    public function setCost(string $cost): self
    {
        $this->cost = $cost;

        return $this;
    }

    public function getInvoice()
    {
        if ($this->invoice != null) {
            return $this->invoice != null ? \base64_encode(stream_get_contents($this->invoice)) : null;
        }
    }

    public function setInvoice($invoice): self
    {
        $this->invoice = $invoice;

        return $this;
    }

    public function getVehicle(): ?Vehicle
    {
        return $this->vehicle;
    }

    public function setVehicle(?Vehicle $vehicle): self
    {
        $this->vehicle = $vehicle;

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

    public function getProvider(): ?Provider
    {
        return $this->provider;
    }

    public function setProvider(?Provider $provider): self
    {
        $this->provider = $provider;

        return $this;
    }
}
