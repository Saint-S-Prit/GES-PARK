<?php

namespace App\Controller;

use App\Entity\Vehicle;
use App\Repository\DriverRepository;
use App\Repository\InsuranceRepository;
use App\Repository\VehicleRepository;
use App\Service\UpdateEntity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class VehicleController extends AbstractController
{
    private $denormalizer;
    private $vehicleRepo;
    private $driverRepo;
    private $updateService;
    private $insuranceRepo;

    public function __construct(
        DenormalizerInterface $denormalizer,
        VehicleRepository $vehicleRepo,
        DriverRepository $driverRepo,
        UpdateEntity $updateService,
        InsuranceRepository $insuranceRepo
    ) {
        $this->denormalizer = $denormalizer;
        $this->vehicleRepo = $vehicleRepo;
        $this->insuranceRepo = $insuranceRepo;
        $this->driverRepo = $driverRepo;
        $this->updateService = $updateService;
    }



    /**
     * @Route(
     * path="/api/vehicle",
     * name="vehicle_add",
     * methods={"POST"}),
     * defaults={
     *  "__controller="\App\VehicleController::vehicleAdd",
     *  "__api_resource_class"=Vehicle::class,
     *  "__api_collection_operation_name"="driver_read"
     * }
     */
    public function vehicleAdd(Request $request, EntityManagerInterface $entityManager)
    {
        //get data without file format
        $data = $request->request->all();



        //if file avatar is required
        if ($request->files->get("cartRegistration")) {
            $data["cartRegistration"] = fopen(($request->files->get("cartRegistration")), "rb");
        }




        //if field driver is required
        if ($data["driver"]) {

            // dd($data["driver"]);


            //verify if driver id exist in databse
            $driver = $this->driverRepo->findOneBycodeUser($data["driver"]);


            //if exist
            if ($driver) {
                //if exist and not delete
                if ($driver->getStatus() === false) {

                    //if driver have a vehicle
                    if (!$driver->getVehicle()) {
                        unset($data['driver']);

                        //denormalize to transform json data in a object AminSysteme  
                        $vehicle = $this->denormalizer->denormalize($data, 'App\Entity\Vehicle');
                        $vehicle->setDriver($driver);
                        $vehicle->setActif(true);


                        //Générate code unique
                        $codeFullName = str_shuffle(strtoupper($vehicle->getMark()[0] . '' . $vehicle->getModele()[0] . '' . $vehicle->getColor()[0]));
                        $codeDate = str_shuffle(date('dmy'));

                        $codeVehicle = $codeFullName . '-' . $codeDate;

                        $vehicle->setCodeVehicle($codeVehicle);


                        //writte data valide in database
                        $entityManager->persist($vehicle);
                        $entityManager->flush();

                        $response = $this->json("la voiture est ajoutée au park avec un chauffeur ");
                    } else {
                        $response = $this->json("Ce conducteur est déjà pris ", 400);
                    }
                } else {
                    $response = $this->json("Ce conducteur est inactive ", 400);
                }
            } else {
                $response = $this->json("conducteur introuveable", 400);
            }
        } else {

            unset($data['driver']);
            //denormalize to transform json data in a object AminSysteme  
            $vehicle = $this->denormalizer->denormalize($data, 'App\Entity\Vehicle');

            //Générate code unique
            $codeFullName = str_shuffle(strtoupper($vehicle->getMark()[0] . '' . $vehicle->getModele()[0] . '' . $vehicle->getColor()[0]));
            $codeDate = str_shuffle(date('dmy'));

            $codeVehicle = $codeFullName . '-' . $codeDate;

            $vehicle->setCodeVehicle($codeVehicle);

            //writte data valide in database
            $entityManager->persist($vehicle);
            $entityManager->flush();

            $response = new JsonResponse(["message" => "la voiture est ajoutée au park avec succés"]);
        }

        return $response;
    }



    /**
     * @Route(
     * path="/api/vehicle",
     * name="vehicles_get",
     * methods={"GET"}),
     * defaults={
     *  "__controller="\App\VehicleController::getvehicles",
     *  "__api_resource_class"=Vehicle::class,
     *  "__api_collection_operation_name"="vehicle_read"
     * }
     */
    public function getvehicles()
    {
        $users = $this->vehicleRepo->findAll();
        return $this->json($users, 200, [], ['groups' => 'vehicle_read']);
    }


    /**
     * @Route(
     * path="/api/vehicle/{codeVehicle}",
     * name="vehicle_get_by_matricule",
     * methods={"GET"}),
     * defaults={
     *  "__controller="\App\VehicleController::getvehicles",
     *  "__api_resource_class"=Vehicle::class,
     *  "__api_item_operation_name"="vehicle_read"
     * }
     */
    public function getvehicle($codeVehicle)
    {
        $vehicle = $this->vehicleRepo->findOneByCodeVehicle($codeVehicle);
        $insurance = $this->insuranceRepo->findByVehicle($vehicle->getId());
        if ($vehicle) {
            if ($vehicle->getStatus() === false) {
                return $this->json($vehicle, 200, [], ['groups' => 'vehicle_read']);
            } else {
                return $this->json("Cette voiture est inactive ", 400);
            }
        } else {
            return $this->json("voiture introuveable", 400);
        }
    }


    /**
     * @Route(
     * path="/api/vehicle/{codeVehicle}/insurance",
     * name="insurance_read_by_vehicle",
     * methods={"GET"}),
     * defaults={
     *  "__controller="\App\VehicleController::getinsuranceByVehicle",
     *  "__api_resource_class"=Vehicle::class,
     *  "__api_item_operation_name"="insurance_read"
     * }
     */
    public function getinsuranceByVehicle($codeVehicle)
    {
        $vehicle = $this->vehicleRepo->findOneByCodeVehicle($codeVehicle);
        if ($vehicle) {
            $insurance = $this->insuranceRepo->findByVehicle($vehicle->getId());
            return $this->json($insurance, 200, [], ['groups' => 'insurance_read']);
        } else {
            return $this->json("voiture introuveable", 400);
        }
    }




    /**
     * @Route(
     * path="/api/vehicle/{matricule}",
     * name="vehicles_delete_by_matricule",
     * methods={"DELETE"}),
     * defaults={
     *  "__controller="\App\VehicleController::delete",
     *  "__api_resource_class"=Vehicle::class,
     *  "__api_item_operation_name"="vehicle_read"
     * }
     */
    public function delete($matricule, EntityManagerInterface $entityManager)
    {
        $vehicle = $this->vehicleRepo->findOneByMatricule($matricule);
        if ($vehicle) {
            if ($vehicle->getStatus() === false) {
                $vehicle->setStatus(true);
                $entityManager->persist($vehicle);
                $entityManager->flush();

                return $this->json($vehicle, 200, [], ['groups' => 'vehicle_read']);
            } else {
                return $this->json("cette voiture est déja inactive ", 400);
            }
        } else {
            return $this->json("voiture introuveable", 400);
        }
    }




    /**
     * @Route(
     * path="/api/vehicle/{codeVehicle}",
     * name="vehicle_update_by_codeVehicle",
     * methods={"PUT"}),
     * defaults={
     *  "__controller="\App\VehicleController::edit",
     *  "__api_resource_class"=Vehicle::class,
     *  "__api_item_operation_name"="vehicle_read"
     * }
     */
    public function edit($matricule, Request $request, string $fileName = null, EntityManagerInterface $entityManager)
    {

        $vehicle = $this->vehicleRepo->findOneByMatricule($matricule);
        if ($vehicle) {
            $data = $this->updateService->update($request, $fileName);

            foreach ($data as $key => $value) {
                $method = 'set' . ucfirst($key);
                if (method_exists($vehicle, $method)) {
                    $vehicle->$method($value);
                }
            }


            $entityManager->persist($vehicle);
            $entityManager->flush();

            return $this->json($vehicle);
        }
    }


    /**
     * @Route(
     * path="/api/vehicle/{codeVehicle}/panne",
     * name="vehicles_panne_by_codeVehicle",
     * methods={"PUT"}),
     * defaults={
     *  "__controller="\App\VehicleController::panne",
     *  "__api_resource_class"=Vehicle::class,
     *  "__api_item_operation_name"="vehicle_read"
     * }
     */
    public function panne($codeVehicle, EntityManagerInterface $entityManager)
    {
        $vehicle = $this->vehicleRepo->findOneByCodeVehicle($codeVehicle);
        if ($vehicle) {
            if ($vehicle->getStatus() === false) {
                if ($vehicle->getPanne() == null) {
                    $vehicle->setPanne(true);
                    $entityManager->persist($vehicle);
                    $entityManager->flush();

                    return $this->json("voiture est en panne");
                } else {
                    return $this->json("cette voiture est déja mis dans cet statut", 400);
                }
            } else {
                return $this->json("cette voiture est déja inactive ", 400);
            }
        } else {
            return $this->json("voiture introuveable", 400);
        }
    }


    /**
     * @Route(
     * path="/api/vehicle/{codeVehicle}/fonctionnal",
     * name="vehicles_fonctionnal_by_codeVehicle",
     * methods={"PUT"}),
     * defaults={
     *  "__controller="\App\VehicleController::fonctionnal",
     *  "__api_resource_class"=Vehicle::class,
     *  "__api_item_operation_name"="vehicle_read"
     * }
     */
    public function fonctionnal($codeVehicle, EntityManagerInterface $entityManager)
    {
        $vehicle = $this->vehicleRepo->findOneByCodeVehicle($codeVehicle);
        if ($vehicle) {
            if ($vehicle->getStatus() === false) {
                if ($vehicle->getPanne() === true) {
                    $vehicle->setPanne(null);
                    $entityManager->persist($vehicle);
                    $entityManager->flush();

                    return $this->json("voiture est conctionnelle");
                } else {
                    return $this->json("cette voiture est déja conctionnelle", 400);
                }
            } else {
                return $this->json("cette voiture est déja inactive ", 400);
            }
        } else {
            return $this->json("voiture introuveable", 400);
        }
    }
}
