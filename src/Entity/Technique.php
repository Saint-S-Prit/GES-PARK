<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\TechniqueRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=TechniqueRepository::class)
 * @ApiResource(
 * normalizationContext={"groups"={"technique_read"}},
 * denormalizationContext={"disable_type_enforcement"=true},
 *          collectionOperations = {
 *           "POST" =
 *             {
 *                "path" = "/technique"
 *             },
 *              "GET" =
 *             {
 *                "path" = "/technique"
 *             }
 * 
 * },
 *          itemOperations = {
 *          "GET" =
 *             {
 *                "path" = "/technique/{id}"
 *             }
 * },
 * )
 */
class Technique
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"technique_read", "technique_write"})
     */
    private $nature;



    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"technique_read"})
     */
    private $agence;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"technique_read"})
     */
    private $numberAgence;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"technique_read"})
     * 
     */
    private $dateVisite;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"technique_read"})
     */
    private $techniqueExpire;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"technique_read"})
     */
    private $cost;

    /**
     * @ORM\Column(type="blob")
     * @Groups({"technique_read"})

     */
    private $invoice;

    /**
     * @ORM\ManyToOne(targetEntity=Vehicle::class, inversedBy="technique")
     * @Groups({"technique_read"})
     */
    private $vehicle;

    /**
     * @ORM\Column(type="text")
     * @Groups({"technique_read"})
     * 
     */
    private $description;

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


    public function getAgence(): ?string
    {
        return $this->agence;
    }

    public function setAgence(string $agence): self
    {
        $this->agence = $agence;

        return $this;
    }

    public function getNumberAgence(): ?string
    {
        return $this->numberAgence;
    }

    public function setNumberAgence(string $numberAgence): self
    {
        $this->numberAgence = $numberAgence;

        return $this;
    }

    public function getDateVisite(): ?\DateTimeInterface
    {
        return $this->dateVisite;
    }

    public function setDateVisite($dateVisite): self
    {
        $this->dateVisite = $dateVisite;

        return $this;
    }

    public function getTechniqueExpire(): ?\DateTimeInterface
    {
        return $this->techniqueExpire;
    }

    public function setTechniqueExpire($techniqueExpire): self
    {
        $this->techniqueExpire = $techniqueExpire;

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
}
