<?php

namespace App\Entity;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\AdminAgentRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=AdminAgentRepository::class)
 * @ApiResource(
 * normalizationContext={"groups"={"adminAgent_read"}},
 * denormalizationContext={"groups"={"adminAgent_write"}},
 * 
 * 
 *          collectionOperations = {
 *              "POST" = {"path"="/agent"},
 *              "GET" = {"path"="/agent"}
 *           },
 * 
 *          itemOperations = {
 *              "GET" = {"path"="/agent/{id}"},
 *              "PUT" = {
 *                      "path"="/agent/{id}",
 *                      "normalization_context"={"groups"={"edite_adminAgent_read"}},
 *                      "denormalization_context"={"groups"={"edite_adminAgent_write"}}
 *              },
 *              "DELETE" = {"path"="/agent/{id}"}
 *          },
 * )
 * 
 */
class AdminAgent extends User
{

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"adminAgent_read", "adminAgent_write","edite_adminSysteme_read","edite_adminAgent_read"})
     */
    private $post;

    public function getPost(): ?string
    {
        return $this->post;
    }

    public function setPost(string $post): self
    {
        $this->post = $post;

        return $this;
    }
}
