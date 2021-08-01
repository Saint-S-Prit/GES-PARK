<?php

namespace App\Controller;

use App\Entity\Notification;
use App\Repository\TechniqueRepository;
use App\Repository\VehicleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class TechniqueController extends AbstractController
{

    private $denormalizer;
    private $vehicleRepo;
    private $techniqueRepo;

    public function __construct(
        DenormalizerInterface $denormalizer,
        VehicleRepository $vehicleRepo,
        TechniqueRepository $techniqueRepo
    ) {
        $this->denormalizer = $denormalizer;
        $this->vehicleRepo = $vehicleRepo;
        $this->techniqueRepo = $techniqueRepo;
    }

    /**
     * @Route(
     * path="/api/technique",
     * name="technique_add",
     * methods={"POST"}),
     * defaults={
     *  "__controller="\App\TechniqueController::techniqueAdd",
     *  "__api_resource_class"=Technique::class,
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


        $vehicle = $this->vehicleRepo->findOneByCodeVehicle(($data["codeVehicle"]));



        if ($vehicle) {

            //recupere some data for sending notification
            $url = 'http://127.0.0.1:8000/api/technique/' . $vehicle->getId();
            $codeVehicle = $vehicle->getCodeVehicle();
            $matricule = $vehicle->getMatricule();

            unset($data['vehicle']);

            //denormalize to transform json data in a object AminSysteme  
            $technique = $this->denormalizer->denormalize($data, 'App\Entity\Technique');

            $technique->setVehicle($vehicle);

            //get date expire for sending notification
            $dateExpire = $technique->getTechniqueExpire();

            // get date one month before expiration date
            $alert =  $dateExpire->modify('-1 month');

            //get date now
            $date = new \DateTime("2021-06-18");


            //convert date into day, month and year to facilitate manupilation
            $alert = $alert->format('Y-m-d');
            $dateExpire = $dateExpire->format('Y-m-d');
            $date = $date->format('Y-m-d');


            $description = "le véhicle de code " . $codeVehicle . " matriculé " . $matricule . " doit passer une visite technique avant le " . $dateExpire . "<br> Voire détails " . $url;

            //if the notification date matches today's date
            if ($date == $alert) {
                $notif = new Notification();
                $notif->setDescription($description);
                $entityManager->persist($notif);
                $entityManager->flush();
            }

            //writte data valide in database
            $entityManager->persist($technique);
            $entityManager->flush();

            return new JsonResponse(["message" => "la voiture est ajoutée au park avec succés"]);
        } else {
            return new JsonResponse(["message" => "une erreur est survenue"]);
        }
    }



    /**
     * @Route(
     * path="/api/technique",
     * name="techique_get",
     * methods={"GET"}),
     * defaults={
     *  "__controller="\App\TechniqueController::getTechnique",
     *  "__api_resource_class"=Technique::class,
     *  "__api_collection_operation_name"="technique_read"
     * }
     */
    public function getTechnique()
    {
        $users = $this->techniqueRepo->findAll();
        return $this->json($users, 200, [], ['groups' => 'technique_read']);
    }


    // /**
    //  * @Route(
    //  * path="/api/vehicle/{matricule}",
    //  * name="vehicle_get_by_matricule",
    //  * methods={"GET"}),
    //  * defaults={
    //  *  "__controller="\App\VehicleController::getvehicles",
    //  *  "__api_resource_class"=Vehicle::class,
    //  *  "__api_item_operation_name"="vehicle_read"
    //  * }
    //  */
    // public function getvehicle($matricule)
    // {
    //     $vehicle = $this->vehicleRepo->findOneByMatricule($matricule);
    //     if ($vehicle) {
    //         if ($vehicle->getStatus() === false) {
    //             return $this->json($vehicle, 200, [], ['groups' => 'vehicle_read']);
    //         } else {
    //             return $this->json("Cette voiture est inactive ", 400);
    //         }
    //     } else {
    //         return $this->json("voiture introuveable", 400);
    //     }
    // }


    // /**
    //  * @Route(
    //  * path="/api/vehicle/{matricule}",
    //  * name="vehicles_delete_by_matricule",
    //  * methods={"DELETE"}),
    //  * defaults={
    //  *  "__controller="\App\VehicleController::delete",
    //  *  "__api_resource_class"=Vehicle::class,
    //  *  "__api_item_operation_name"="vehicle_read"
    //  * }
    //  */
    // public function delete($matricule, EntityManagerInterface $entityManager)
    // {
    //     $vehicle = $this->vehicleRepo->findOneByMatricule($matricule);
    //     if ($vehicle) {
    //         if ($vehicle->getStatus() === false) {
    //             $vehicle->setStatus(true);
    //             $entityManager->persist($vehicle);
    //             $entityManager->flush();

    //             return $this->json($vehicle, 200, [], ['groups' => 'vehicle_read']);
    //         } else {
    //             return $this->json("cette voiture est déja inactive ", 400);
    //         }
    //     } else {
    //         return $this->json("voiture introuveable", 400);
    //     }
    // }
}
