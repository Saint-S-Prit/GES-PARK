<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\NotificationRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=NotificationRepository::class)
 * @ApiResource(
 * normalizationContext={"groups"={"notification_read"}},
 * denormalizationContext={"groups"={"notification_write"}},
 * 
 *          collectionOperations = {
 *           "POST" =
 *             {
 *                "path" = "/notification"
 *             },
 *          "GET" =
 *             {
 *                "path" = "/notification"
 *             }
 * 
 * },
 *          itemOperations = {
 *          "GET" =
 *             {
 *                "path" = "/notification/{id}"
 *             }
 * },
 * )
 */
class Notification
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     * @Groups({"notification_read"})
     */
    private $description;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"notification_read"})
     */
    private $status = false;

    public function getId(): ?int
    {
        return $this->id;
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
