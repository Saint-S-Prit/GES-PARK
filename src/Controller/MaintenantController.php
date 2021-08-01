<?php

namespace App\Controller;

use App\Repository\VehicleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class MaintenantController extends AbstractController
{
    private $denormalizer;
    private $vehicleRepo;

    public function __construct(
        DenormalizerInterface $denormalizer,
        VehicleRepository $vehicleRepo
    ) {
        $this->denormalizer = $denormalizer;
        $this->vehicleRepo = $vehicleRepo;
    }

    /**
     * @Route(
     * path="/api/maintenance",
     * name="maintenance_add",
     * methods={"POST"}),
     * defaults={
     *  "__controller="\App\MaintenanceController::maintenanceAdd",
     *  "__api_resource_class"=Maintenance::class,
     *  "__api_collection_operation_name"="maintenance_read"
     * }
     */
    public function techniqueAdd(Request $request, EntityManagerInterface $entityManager)
    {
        //get data without file format
        $data = $request->request->all();


        //if file avatar is required
        if ($request->files->get("invoice")) {
            $data["invoice"] = fopen(($request->files->get("invoice")), "rb");
        }


        $vehicle = $this->vehicleRepo->findOneByMatricule(($data["vehicle"]));


        $provider['nom'] = $data['nom'];
        $provider['address'] = $data['address'];
        $provider['phone'] = $data['phone'];
        $provider['email'] = $data['email'];


        if ($vehicle) {
            unset($data['vehicle']);

            //denormalize to transform json data in a object Provider  
            $provider = $this->denormalizer->denormalize($provider, 'App\Entity\Provider');

            //denormalize to transform json data in a object Maintenance  
            $maintenance = $this->denormalizer->denormalize($data, 'App\Entity\Maintenance');

            $maintenance->setVehicle($vehicle);
            $maintenance->setProvider($provider);


            //writte data valide in database
            $entityManager->persist($provider);
            $entityManager->persist($maintenance);
            $entityManager->flush();

            return new JsonResponse(["message" => "la voiture est ajoutée au park avec succés"]);
        } else {
            return new JsonResponse(["message" => "une erreur est survenue"]);
        }
    }
}
