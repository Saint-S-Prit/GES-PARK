<?php

namespace App\Entity;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\DriverRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=DriverRepository::class)
 *  @ApiResource(
 * normalizationContext={"groups"={"driver_read"}},
 * denormalizationContext={"groups"={"driver_write"}},
 *          collectionOperations = {
 *           "POST" =
 *             {
 *                "path" = "/driver"
 *             },
 *           "GET" =
 *             {
 *                 "path" = "/driver"
 *             }
 * 
 * },
 *          itemOperations = {
 *          "GET" =
 *             {
 *                "path" = "/driver/{id}"
 *             },
 *          "PUT" =
 *             {
 *                 "path" = "/driver/{id}",
 *                      "normalization_context"={"groups"={"edite_driver_read"}},
 *                      "denormalization_context"={"groups"={"edite_driver_write"}}
 *             },
 *           "DELETE" =
 *             {
 *                 "path" = "/driver/{id}"
 *             }
 * },
 * )
 */
class Driver extends User
{

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"driver_read","driver_write","vehicle_read","edite_driver_read","edite_driver_write"})
     */
    private $natureLicense;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"driver_read","driver_write","vehicle_read","edite_driver_read","edite_driver_write"})
     */
    private $dateLicense;

    /**
     * @ORM\Column(type="blob", nullable=true)
     * @Groups({"driver_read","driver_write","vehicle_read","edite_driver_read","edite_driver_write"})
     */
    private $licenseFile;

    /**
     * @ORM\ManyToOne(targetEntity=License::class, inversedBy="drivers")
     * @Groups({"driver_read","driver_write","vehicle_read","edite_driver_read","edite_driver_write"})
     */
    private $license;

    /**
     * @ORM\OneToOne(targetEntity=Vehicle::class, inversedBy="driver", cascade={"persist", "remove"})
     *  @ORM\JoinColumn(nullable=true)
     * @Groups({"driver_read","driver_write"})
     */
    private $vehicle;



    public function getNatureLicense(): ?string
    {
        return $this->natureLicense;
    }

    public function setNatureLicense(string $natureLicense): self
    {
        $this->natureLicense = $natureLicense;

        return $this;
    }

    public function getDateLicense(): ?string
    {
        return $this->dateLicense;
    }

    public function setDateLicense(string $dateLicense): self
    {
        $this->dateLicense = $dateLicense;

        return $this;
    }

    public function getLicenseFile()
    {

        if ($this->licenseFile != null) {
            return $this->licenseFile != null ? \base64_encode(stream_get_contents($this->licenseFile)) : null;
        }
    }

    public function setLicenseFile($licenseFile): self
    {
        $this->licenseFile = $licenseFile;

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

    public function getLicense(): ?License
    {
        return $this->license;
    }

    public function setLicense(?License $license): self
    {
        $this->license = $license;

        return $this;
    }
}
