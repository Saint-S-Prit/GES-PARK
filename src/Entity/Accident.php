<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\AccidentRepository;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ORM\Entity(repositoryClass=AccidentRepository::class)
 * @ApiResource(
 *          collectionOperations = {
 *           "POST" =
 *             {
 *                "path" = "/accident/add"
 *             },
 *           "GET" =
 *             {
 *                 "path" = "/accident"
 *             }
 * 
 * },
 *          itemOperations = {
 *          "GET" =
 *             {
 *                "path" = "/accident/{id}"
 *             },
 *          "PUT" =
 *             {
 *                 "path" = "/accident/{id}/edite"
 *             },
 *           "DELETE" =
 *             {
 *                 "path" = "/accident/{id}/supprimer"
 *             }
 * },
 * )
 */
class Accident
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
    private $place;

    /**
     * @ORM\Column(type="datetime")
     */
    private $accidentDate;



    /**
     * @ORM\ManyToOne(targetEntity=Vehicle::class, inversedBy="accident")
     */
    private $vehicle;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlace(): ?string
    {
        return $this->place;
    }

    public function setPlace(string $place): self
    {
        $this->place = $place;

        return $this;
    }

    public function getAccidentDate(): ?string
    {
        return $this->accidentDate;
    }

    public function setAccidentDate(string $accidentDate): self
    {
        $this->accidentDate = $accidentDate;

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
