<?php

namespace App\Controller;

use App\Entity\Notification;
use App\Repository\InsuranceRepository;
use App\Repository\VehicleRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class InsuranceController extends AbstractController
{
    private $denormalizer;
    private $vehicleRepo;
    private $insuranceRepo;

    public function __construct(
        DenormalizerInterface $denormalizer,
        VehicleRepository $vehicleRepo,
        InsuranceRepository $insuranceRepo
    ) {
        $this->denormalizer = $denormalizer;
        $this->vehicleRepo = $vehicleRepo;
        $this->insuranceRepo = $insuranceRepo;
    }

    /**
     * @Route(
     * path="/api/insurance",
     * name="insurance_add",
     * methods={"POST"}),
     * defaults={
     *  "__controller="\App\InsuranceController::insuranceAdd",
     *  "__api_resource_class"=Insurance::class,
     * }
     */
    public function insuranceAdd(Request $request, EntityManagerInterface $entityManager)
    {

        //get data without file format
        $data = $request->request->all();

        //if file avatar is required
        if ($request->files->get("invoice")) {
            $data["invoice"] = fopen(($request->files->get("invoice")), "rb");
        }

        //find vehicle by code Vehicle
        $vehicle = $this->vehicleRepo->findOneByCodeVehicle(($data["codeVehicle"]));

        //if vehicle existe
        if ($vehicle) {
            //recupere some data for sending notification
            $url = 'http://127.0.0.1:8000/api/insurance/' . $vehicle->getId();
            $codeVehicle = $vehicle->getCodeVehicle();
            $matricule = $vehicle->getMatricule();

            unset($data['vehicle']);

            //denormalize to transform json data in a object AminSysteme  
            $insurance = $this->denormalizer->denormalize($data, 'App\Entity\Insurance');

            $insurance->setVehicle($vehicle);

            //get date expire for sending notification
            $dateExpire = $insurance->getInsuranceExpire();

            // get date one month before expiration date
            $alert =  $dateExpire->modify('-2 month');

            //get date now
            $date = new \DateTime();


            //convert date into day, month and year to facilitate manupilation
            $alert = $alert->format('Y-m-d');
            $dateExpire = $dateExpire->format('Y-m-d');
            $date = $date->format('Y-m-d');


            $description = "le véhicle de code " . $codeVehicle . " matriculé " . $matricule . " doit passer l'assurance avec le " . $dateExpire . "<br> Voire détails " . $url;


            //writte data valide in database
            $entityManager->persist($insurance);
            $entityManager->flush();

            //if the notification date matches today's date
            if ($date == $alert) {
                $notif = new Notification();
                $notif->setDescription($description);
                $entityManager->persist($notif);
                $entityManager->flush();
            }

            return new JsonResponse(["message" => "la voiture est ajoutée au park avec succés"]);
        } else {
            return new JsonResponse(["message" => "une erreur est survenue"]);
        }
    }


    /**
     * @Route(
     * path="/api/insurance",
     * name="insurance_get",
     * methods={"GET"}),
     * defaults={
     *  "__controller="\App\InsuranceController::getInsurances",
     *  "__api_resource_class"=Insurance::class,
     *  "__api_item_operation_name"="insurance_read"
     * }
     */
    public function getInsurances()
    {
        $insurances = $this->insuranceRepo->findAll();
        return $this->json($insurances, 200, [], ['groups' => 'insurance_read']);
    }

    /**
     * @Route(
     * path="/api/insurance/{id}",
     * name="insurance_get_by_id",
     * methods={"GET"}),
     * defaults={
     *  "__controller="\App\InsuranceController::getInsurance",
     *  "__api_resource_class"=Insurance::class,
     *  "__api_item_operation_name"="insurance_read"
     * }
     */
    public function getInsurance($id)
    {
        $insurance = $this->insuranceRepo->findOneById($id);



        if ($insurance) {

            $response = $this->json($insurance, 200, [], ['groups' => 'insurance_read']);
        } else {
            $response = $this->json("insurance introuveable", 400);
        }

        return $response;
    }
}
