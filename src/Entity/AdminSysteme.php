<?php

namespace App\Entity;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\AdminSystemeRepository;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ORM\Entity(repositoryClass=AdminSystemeRepository::class)
 * @ApiResource(
 * normalizationContext={"groups"={"adminSysteme_read"}},
 * denormalizationContext={"groups"={"adminSysteme_write"}},
 * 
 * 
 *          collectionOperations = {
 *              "POST" = {"path"="/admin"},
 *              "GET" = {"path"="/admin"}
 *           },
 * 
 *          itemOperations = {
 *              "GET" = {"path"="/admin/{id}"},
 *              "PUT" = {
 *                      "path"="/admin/{id}",
 *                      "normalization_context"={"groups"={"edite_adminSysteme_read"}},
 *                      "denormalization_context"={"groups"={"edite_adminSysteme_write"}}
 *              },
 *              "DELETE" = {"path"="/admin/{id}"}
 *          },
 * )
 */
class AdminSysteme extends User
{
}
