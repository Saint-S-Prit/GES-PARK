<?php

namespace App\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\InsuranceRepository;
use ApiPlatform\Core\Annotation\ApiResource;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=InsuranceRepository::class)
 * @ApiResource(
 * normalizationContext={"groups"={"insurance_read"}},
 * denormalizationContext={"disable_type_enforcement"=true},
 *          collectionOperations = {
 *           "POST" =
 *             {
 *                "path" = "/insurance"
 *             },
 *             "GET" =
 *             {
 *                "path" = "/insurance"
 *             }
 * 
 * },
 *          itemOperations = {
 *          "GET" =
 *             {
 *                "path" = "/insurance/{id}"
 *             }
 * },
 * )
 */
class Insurance
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"insurance_read"})
     */
    private $number;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"insurance_read"})
     */
    private $agence;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\DateTime(message="La date doit être au format YYYY-MM-DD")
     * @Groups({"insurance_read"})
     * 
     */
    private $insuranceDate;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\DateTime(message="La date doit être au format YYYY-MM-DD")
     * @Groups({"insurance_read"})
     * 
     */
    private $insuranceExpire;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"insurance_read"})
     */
    private $cost;

    /**
     * @ORM\Column(type="blob")
     * @Groups({"insurance_read"})
     */
    private $invoice;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"insurance_read"})
     */
    private $nature;

    /**
     * @ORM\ManyToOne(targetEntity=Vehicle::class, inversedBy="insurance")
     */
    private $vehicle;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(string $number): self
    {
        $this->number = $number;

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

    public function getInsuranceDate(): ?\DateTimeInterface
    {
        return $this->insuranceDate;
    }

    public function setInsuranceDate($insuranceDate): self
    {
        $this->insuranceDate = $insuranceDate;

        return $this;
    }

    public function getInsuranceExpire(): ?\DateTimeInterface
    {
        return $this->insuranceExpire;
    }

    public function setInsuranceExpire($insuranceExpire): self
    {
        $this->insuranceExpire = $insuranceExpire;

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

    public function getNature(): ?string
    {
        return $this->nature;
    }

    public function setNature(string $nature): self
    {
        $this->nature = $nature;

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
