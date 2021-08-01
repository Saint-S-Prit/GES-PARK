<?php

namespace App\Controller;

use App\Repository\LicenseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LicenseController extends AbstractController
{
    private $licenseRepo;

    public function __construct(
        LicenseRepository $licenseRepo
    ) {
        $this->licenseRepo = $licenseRepo;
    }
    /**
     * @Route(
     * path="/api/license",
     * name="licenses_get",
     * methods={"GET"}),
     * defaults={
     *  "__controller="\App\LicenseController::getlicenses",
     *  "__api_resource_class"=License::class,
     *  "__api_collection_operation_name"="license_read"
     * }
     */
    public function getvehicles()
    {
        $users = $this->licenseRepo->findAll();
        return $this->json($users, 200, [], ['groups' => 'license_read']);
    }


    /**
     * @Route(
     * path="/api/license/{name}",
     * name="license_delete_by_name",
     * methods={"DELETE"}),
     * defaults={
     *  "__controller="\App\LicenseController::delete",
     *  "__api_resource_class"=License::class,
     *  "__api_item_operation_name"="license_read"
     * }
     */
    public function delete($name, EntityManagerInterface $entityManager)
    {
        $licence = $this->licenseRepo->findOneByName($name);
        if ($licence) {
            if ($licence->getStatus() === false) {
                $licence->setStatus(true);
                $entityManager->persist($licence);
                $entityManager->flush();
                return $this->json($licence, 200, [], ['groups' => 'license_read']);
            } else {
                return $this->json("cet license est inactif ", 400);
            }
        } else {
            return $this->json("license introuveable", 400);
        }
    }
}
