<?php

namespace App\Controller;

use App\Entity\AdminSysteme;
use App\Service\SendMessage;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\AdminSystemeRepository;
use App\Repository\ProfileRepository;
use App\Service\UpdateEntity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminSystemeController extends AbstractController
{
    private $encoder;
    private $denormalizer;
    private $sendMessage;
    private $mailer;
    private $adminRepo;
    private $profileRepo;
    private $updateService;


    public function __construct(
        UserPasswordEncoderInterface $encoder,
        DenormalizerInterface $denormalizer,
        SendMessage $sendMessage,
        \Swift_Mailer $mailer,
        AdminSystemeRepository $adminRepo,
        ProfileRepository $profileRepo,
        UpdateEntity $updateService


    ) {
        $this->encoder = $encoder;
        $this->denormalizer = $denormalizer;
        $this->sendMessage = $sendMessage;
        $this->mailer = $mailer;
        $this->adminRepo = $adminRepo;
        $this->updateService = $updateService;
        $this->profileRepo = $profileRepo;
    }


    /**
     * @Route(
     * path="/api/admin",
     * name="admin_add",
     * methods={"POST"}),
     * defaults={
     *  "__controller="\App\AdminSystemeController::AdminAdd",
     *  "__api_resource_class"=AdminSysteme::class,
     *  "__api_collection_operation_name"="adminSysteme_read"
     * }
     */
    public function AdminAdd(Request $request, EntityManagerInterface $entityManager)
    {



        // if (!$this->security->isGranted('ROLE_ADMINSYSTEME')) {
        //     return $this->json("seul les admins peuvent avoir accés a cette ressource", 400);
        // }
        //get data without file format
        $data = $request->request->all();


        //set profile admin systeme
        $profile = $this->profileRepo->findOneByName("ADMINSYSTEME");


        //if file avatar is required
        if ($request->files->get("avatar")) {
            $data["avatar"] = fopen(($request->files->get("avatar")), "rb");
        }

        //denormalize to transform json data in a object AminSysteme  
        $adminSysteme = $this->denormalizer->denormalize($data, 'App\Entity\AdminSysteme');
        $adminSysteme->setProfile($profile);

        //Générate code unique
        $codeFullName = strtoupper($adminSysteme->getFirstname()[0] . '' . $adminSysteme->getLastname()[0]);
        $codeDate = date('dmy');

        $codeUser = $codeFullName . '-' . $codeDate;
        $adminSysteme->setCodeUser($codeUser);

        //use password Eencoder to hash password
        $adminSysteme->setPassword($this->encoder->encodePassword($adminSysteme, $adminSysteme->getPassword()));
        //writte data valide in database
        $entityManager->persist($adminSysteme);
        $entityManager->flush();

        //send massage to user;an accound is create form him
        $this->sendMessage->sendMessageActivatorAccount($this->mailer, $adminSysteme);


        return new JsonResponse(["message" => "Compte administrateur systeme est créé avec succés"]);
    }



    /**
     * @Route(
     * path="/api/admin",
     * name="admins_get",
     * methods={"GET"}),
     * defaults={
     *  "__controller="\App\AdminSystemeController::getAdmins",
     *  "__api_resource_class"=AdminSysteme::class,
     *  "__api_collection_operation_name"="adminSysteme_read"
     * }
     */
    public function getAdmins()
    {
        $users = $this->adminRepo->findAll();

        return $this->json($users, 200, [], ['groups' => 'adminSysteme_read']);
    }


    /**
     * @Route(
     * path="/api/admin/{codeUser}",
     * name="admin_get_by_codeUser",
     * methods={"GET"}),
     * defaults={
     *  "__controller="\App\AdminSystemeController::getAdmin",
     *  "__api_resource_class"=AdminSysteme::class,
     *  "__api_item_operation_name"="adminSysteme_read"
     * }
     */
    public function getAdmin($codeUser)
    {
        // if ($this->security->isGranted('ROLE_ADMINSYSTEME')) {
        //     $response = $this->json("seul les admins peuvent avoir accés a cette ressource", 400);
        // }
        $admin = $this->adminRepo->findOneByCodeUser($codeUser);


        if ($admin) {

            if ($admin->getStatus() === false) {
                $response = $this->json($admin, 200, [], ['groups' => 'adminSysteme_read']);
            } else {
                $response = $this->json("cet admin est inactif ", 400);
            }
        } else {
            $response = $this->json("admin introuveable", 400);
        }

        return $response;
    }


    /**
     * @Route(
     * path="/api/admin/{codeUser}",
     * name="admins_delete_by_codeUser",
     * methods={"DELETE"}),
     * defaults={
     *  "__controller="\App\AdminSystemeController::delete",
     *  "__api_resource_class"=AdminSysteme::class,
     *  "__api_item_operation_name"="adminSysteme_read"
     * }
     */
    public function delete($codeUser, EntityManagerInterface $entityManager)
    {
        $admin = $this->adminRepo->findOneByCodeUser($codeUser);
        if ($admin) {
            if ($admin->getStatus() === false) {
                $admin->setStatus(true);
                $entityManager->persist($admin);
                $entityManager->flush();
                $response = $this->json($admin, 200, [], ['groups' => 'adminSysteme_read']);
            } else {
                $response = $this->json("cet admin est inactif ", 400);
            }
        } else {
            $response = $this->json("admin introuveable", 400);
        }
        return $response;
    }


    /**
     * @Route(
     * path="/api/admin/{codeUser}",
     * name="admins_update_by_codeUser",
     * methods={"PUT"}),
     * defaults={
     *  "__controller="\App\AdminSystemeController::edit",
     *  "__api_resource_class"=AdminSysteme::class,
     *  "__api_item_operation_name"="edite_adminSysteme_write"
     * }
     */
    public function edit($codeUser, Request $request, string $fileName = null, EntityManagerInterface $entityManager)
    {

        // if (!$this->security->isGranted('ROLE_ADMINSYSTEME')) {
        //     return $this->json("seul les admins peuvent avoir accés a cette ressource", 400);
        // }

        $admin = $this->adminRepo->findOneByCodeUser($codeUser);
        if ($admin) {
            $data = $this->updateService->update($request, $fileName);

            foreach ($data as $key => $value) {
                if ($key !== 'profile') {
                    $method = 'set' . ucfirst($key);
                    if (method_exists($admin, $method)) {
                        if ($key == 'password') {
                            $admin->$method($this->encoder->encodePassword($admin, $value));
                        } else {
                            $admin->$method($value);
                        }
                    }
                }
            }


            $entityManager->persist($admin);
            $entityManager->flush();
            return $this->json($admin);
        }
    }
}
