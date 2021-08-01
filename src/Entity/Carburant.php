<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CarburantRepository;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ORM\Entity(repositoryClass=CarburantRepository::class)
 * @ApiResource(
 *          collectionOperations = {
 *           "POST" =
 *             {
 *                "path" = "/carburant"
 *             },
 *           "GET" =
 *             {
 *                 "path" = "/carburant"
 *             }
 * 
 * },
 *          itemOperations = {
 *          "GET" =
 *             {
 *                "path" = "/carburant/{id}"
 *             },
 *          "PUT" =
 *             {
 *                 "path" = "/carburant/{id}"
 *             },
 *           "DELETE" =
 *             {
 *                 "path" = "/carburant/{id}"
 *             }
 * },
 * )
 */
class Carburant
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
    private $nature;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $carburantDate;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $quantity;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $price;

    /**
     * @ORM\Column(type="blob")
     */
    private $invoice;

    /**
     * @ORM\ManyToOne(targetEntity=Vehicle::class, inversedBy="carburant")
     */
    private $vehicle;



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

    public function getCarburantDate(): ?string
    {
        return $this->carburantDate;
    }

    public function setCarburantDate(string $carburantDate): self
    {
        $this->carburantDate = $carburantDate;

        return $this;
    }

    public function getQuantity(): ?string
    {
        return $this->quantity;
    }

    public function setQuantity(string $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

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
}
