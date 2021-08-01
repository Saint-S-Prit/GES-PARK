<?php

namespace App\Controller;

use App\Repository\DriverRepository;
use App\Repository\LicenseRepository;
use App\Repository\ProfileRepository;
use App\Repository\VehicleRepository;
use App\Service\UpdateEntity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class DriverController extends AbstractController
{
    private $encoder;
    private $denormalizer;
    private $driverRepo;
    private $vehicleRepo;
    private $profileRepo;
    private $licenseRepo;
    private $updateService;


    public function __construct(
        UserPasswordEncoderInterface $encoder,
        DenormalizerInterface $denormalizer,
        DriverRepository $driverRepo,
        VehicleRepository $vehicleRepo,
        ProfileRepository $profileRepo,
        UpdateEntity $updateService,
        LicenseRepository $licenseRepo


    ) {
        $this->encoder = $encoder;
        $this->denormalizer = $denormalizer;
        $this->driverRepo = $driverRepo;
        $this->vehicleRepo = $vehicleRepo;
        $this->profileRepo = $profileRepo;
        $this->updateService = $updateService;
        $this->licenseRepo = $licenseRepo;
    }


    /**
     * @Route(
     * path="/api/driver",
     * name="driver_add",
     * methods={"POST"}),
     * defaults={
     *  "__controller="\App\DriverController::driverAdd",
     *  "__api_resource_class"=Driver::class,
     *  "__api_collection_operation_name"="driver_read"
     * }
     */
    public function driverAdd(Request $request, EntityManagerInterface $entityManager)
    {
        //get data without file format
        $data = $request->request->all();





        //if file avatar is required
        if ($request->files->get("avatar")) {
            $data["avatar"] = fopen(($request->files->get("avatar")), "rb");
        }


        //if file cartRegistration is required
        if ($request->files->get("licenseFile")) {
            $data["licenseFile"] = fopen(($request->files->get("licenseFile")), "rb");
        }

        // dd($data["license"]);





        //set profile driver
        $profile = $this->profileRepo->findOneByName("DRIVER");

        //set name license
        $license = $this->licenseRepo->findOneByName($data["license"]);
        unset($data["license"]);

        if ($data["vehicle"]) {
            $vehicle = $this->vehicleRepo->findOneByCodeVehicle($data["vehicle"]);
            if ($vehicle) {
                if (!$vehicle->getDriver()) {
                    $vehicle->setActif(true);

                    unset($data["vehicle"]);
                    //denormalize to transform json data in a object Dr  iver
                    $driver = $this->denormalizer->denormalize($data, 'App\Entity\Driver');
                    $driver->setProfile($profile);
                    $driver->setLicense($license);
                    //Générate code unique
                    $codeFullName = strtoupper($driver->getFirstname()[0] . '' . $driver->getLastname()[0]);
                    $codeDate = date('dmy');

                    $codeUser = $codeFullName . '-' . $codeDate;
                    $driver->setCodeUser($codeUser);
                    $driver->setPassword('');

                    $driver->setVehicle($vehicle);
                    $vehicle->setDriver($driver);

                    // dd($driver);
                    //writte data valide in database
                    $entityManager->persist($driver);
                    $entityManager->persist($vehicle);
                    $entityManager->flush();
                    $response = $this->json("compte conducteur créé  avec attribution de vehicule.");
                } else {
                    $response = $this->json("Vehicule est déjà pris ", 400);
                }
            } else {
                $response = $this->json("Vehicule introuvable", 400);
            }
        } else {
            unset($data["vehicle"]);

            // dd($data);
            //denormalize to transform json data in a object Dr  iver
            $driver = $this->denormalizer->denormalize($data, 'App\Entity\Driver');
            $driver->setProfile($profile);
            $driver->setLicense($license);

            //Générate code unique
            $codeFullName = strtoupper($driver->getFirstname()[0] . '' . $driver->getLastname()[0]);
            $codeDate = date('dmy');

            $codeUser = $codeFullName . '-' . $codeDate;
            $driver->setCodeUser($codeUser);

            // dd($driver);

            //writte data valide in database
            $entityManager->persist($driver);
            $entityManager->flush();
            $response = new JsonResponse(["message" => "compte conducteur créé"]);
        }

        return $response;
    }


    /**
     * @Route(
     * path="/api/driver",
     * name="driver_get",
     * methods={"GET"}),
     * defaults={
     *  "__controller="\App\DriverController::getAgents",
     *  "__api_resource_class"=Driver::class,
     *  "__api_collection_operation_name"="driver_read"
     * }
     */
    public function getAgents()
    {
        $users = $this->driverRepo->findAll();
        return $this->json($users, 200, [], ['groups' => 'driver_read']);
    }


    /**
     * @Route(
     * path="/api/driver/{codeUser}",
     * name="driver_get_codeUser",
     * methods={"GET"}),
     * defaults={
     *  "__controller="\App\DriverController::getAgent",
     *  "__api_resource_class"=Driver::class,
     *  "__api_item_operation_name"="driver_read"
     * }
     */
    public function getAgent($codeUser)
    {
        $agent = $this->driverRepo->findOneByCodeUser($codeUser);
        if ($agent) {
            if ($agent->getStatus() === false) {
                return $this->json($agent, 200, [], ['groups' => 'driver_read']);
            } else {
                return $this->json("cet admin est inactif ", 400);
            }
        } else {
            return $this->json("agent introuveable", 400);
        }
    }


    /**
     * @Route(
     * path="/api/driver/{codeUser}",
     * name="driver_update_by_codeUser",
     * methods={"PUT"}),
     * defaults={
     *  "__controller="\App\DriverController::edit",
     *  "__api_resource_class"=Driver::class,
     *  "__api_item_operation_name"="driver_read"
     * }
     */
    public function edit($codeUser, Request $request, string $fileName = null, EntityManagerInterface $entityManager)
    {
        $driver = $this->driverRepo->findOneByCodeUser($codeUser);
        if ($driver) {
            $data = $this->updateService->update($request, $fileName);

            foreach ($data as $key => $value) {
                if ($key !== 'profile') {
                    $method = 'set' . ucfirst($key);
                    if (method_exists($driver, $method)) {
                        if ($key == 'password') {
                            $driver->$method($this->encoder->encodePassword($driver, $value));
                        } else {
                            $driver->$method($value);
                        }
                    }
                }
            }

            $entityManager->persist($driver);
            $entityManager->flush();
            return $this->json($driver);
        }
    }


    /**
     * @Route(
     * path="/api/driver/{codeUser}",
     * name="agent_delete_by_codeUser",
     * methods={"DELETE"}),
     * defaults={
     *  "__controller="\App\AdminAgentController::delete",
     *  "__api_resource_class"=AdminAgent::class,
     *  "__api_item_operation_name"="driver_read"
     * }
     */
    public function delete($codeUser, EntityManagerInterface $entityManager)
    {
        $driver = $this->driverRepo->findOneByCodeUser($codeUser);
        if ($driver) {
            if ($driver->getStatus() === false) {
                $driver->setStatus(true);
                $entityManager->persist($driver);
                $entityManager->flush();
                return $this->json($driver, 200, [], ['groups' => 'driver_read']);
            } else {
                return $this->json("cet admin est inactif ", 400);
            }
        } else {
            return $this->json("admin introuveable", 400);
        }
    }
}
